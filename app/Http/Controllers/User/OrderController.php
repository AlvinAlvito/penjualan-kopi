<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(private readonly MidtransService $midtransService) {}

    public function index()
    {
        $orders = Order::query()->where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function show(string $invoice)
    {
        $order = Order::query()
            ->with(['items.product.primaryImage', 'payment', 'shipment', 'promotion'])
            ->where('invoice_no', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('user.order-detail', compact('order'));
    }

    public function syncPayment(string $invoice): JsonResponse
    {
        $order = Order::query()
            ->with('payment')
            ->where('invoice_no', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$order->payment || $order->payment->provider !== 'midtrans') {
            return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 422);
        }

        try {
            $payload = $this->midtransService->getTransactionStatus($order->invoice_no);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal sinkron status pembayaran.'], 500);
        }

        $transactionStatus = strtolower((string) ($payload['transaction_status'] ?? 'pending'));
        $fraudStatus = strtolower((string) ($payload['fraud_status'] ?? ''));

        DB::transaction(function () use ($order, $payload, $transactionStatus, $fraudStatus) {
            $payment = $order->payment;
            $payment->transaction_status = $transactionStatus;
            $payment->gross_amount = (int) ($payload['gross_amount'] ?? $order->total);
            $payment->payload_json = $payload;
            $payment->paid_at = in_array($transactionStatus, ['settlement', 'capture'], true) ? now() : null;
            $payment->save();

            if ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
                return;
            }

            if (in_array($transactionStatus, ['settlement', 'capture'], true) && $order->status === 'pending') {
                $order->status = 'paid';
                $order->save();
                return;
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true) && $order->status === 'pending') {
                $order->status = 'cancelled';
                $order->save();
            }
        });

        $order->refresh()->load('payment');

        return response()->json([
            'message' => 'Status pembayaran diperbarui.',
            'order_status' => $order->status,
            'payment_status' => $order->payment?->transaction_status,
        ]);
    }
}
