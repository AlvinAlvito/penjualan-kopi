<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\InteractionService;
use App\Services\MidtransService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService,
        private readonly InteractionService $interactionService,
        private readonly NotificationService $notificationService,
    ) {}

    public function callback(Request $request)
    {
        $payload = $request->all();

        if (!$this->midtransService->isValidSignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::query()->with('items.product')->where('invoice_no', $payload['order_id'] ?? '')->firstOrFail();
        $transactionStatus = $payload['transaction_status'] ?? 'pending';

        DB::transaction(function () use ($order, $transactionStatus, $payload) {
            $payment = Payment::query()->where('order_id', $order->id)->first();
            if ($payment) {
                $payment->transaction_status = $transactionStatus;
                $payment->gross_amount = (int) ($payload['gross_amount'] ?? $order->total);
                $payment->payload_json = $payload;
                $payment->paid_at = in_array($transactionStatus, ['settlement', 'capture'], true) ? now() : null;
                $payment->save();
            }

            if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
                $order->status = 'paid';
                $order->save();

                foreach ($order->items as $item) {
                    $item->product->decrement('stock', $item->qty);
                    $this->interactionService->log($order->user_id, 'purchase', $item->product_id);
                }

                $this->notificationService->sendToUser(
                    $order->user_id,
                    'Pembayaran berhasil',
                    "Pembayaran untuk {$order->invoice_no} berhasil diverifikasi.",
                    'payment'
                );
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
                $order->status = 'cancelled';
                $order->save();

                $this->notificationService->sendToUser(
                    $order->user_id,
                    'Pembayaran gagal',
                    "Pembayaran untuk {$order->invoice_no} gagal atau dibatalkan.",
                    'payment'
                );
            }
        });

        return response()->json(['message' => 'OK']);
    }
}
