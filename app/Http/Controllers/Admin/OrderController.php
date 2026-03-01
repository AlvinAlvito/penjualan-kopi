<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function index()
    {
        $orders = Order::query()->with(['user', 'payment'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'payment', 'shipment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,paid,processing,shipped,done,cancelled'],
            'courier' => ['nullable', 'string'],
            'service' => ['nullable', 'string'],
            'tracking_no' => ['nullable', 'string'],
        ]);

        $order->status = $data['status'];
        $order->save();

        if ($data['status'] === 'shipped') {
            Shipment::query()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier' => $data['courier'] ?? null,
                    'service' => $data['service'] ?? null,
                    'tracking_no' => $data['tracking_no'] ?? null,
                    'status' => 'shipped',
                    'shipped_at' => now(),
                ]
            );
        }

        if ($data['status'] === 'done') {
            Shipment::query()->updateOrCreate(
                ['order_id' => $order->id],
                ['status' => 'delivered', 'delivered_at' => now()]
            );
        }

        $this->notificationService->sendToUser(
            $order->user_id,
            'Status pesanan berubah',
            "Status pesanan {$order->invoice_no} sekarang: {$order->status}.",
            'order'
        );

        return back()->with('success', 'Status order diperbarui.');
    }
}
