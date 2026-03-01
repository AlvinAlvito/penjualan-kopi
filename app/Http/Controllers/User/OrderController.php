<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
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
}
