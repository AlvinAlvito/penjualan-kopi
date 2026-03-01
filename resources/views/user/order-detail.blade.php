@extends('layouts.app')
@section('content')
<h3 class="section-title">Detail Pesanan</h3>
<div class="card-pro mb-3">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6"><strong>Invoice:</strong> {{ $order->invoice_no }}</div>
            <div class="col-md-6"><strong>Status:</strong> <span class="chip">{{ strtoupper($order->status) }}</span></div>
            <div class="col-md-4"><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
            <div class="col-md-4"><strong>Diskon:</strong> Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</div>
            <div class="col-md-4"><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            @if($order->promotion)
                <div class="col-md-6"><strong>Promo:</strong> {{ $order->promotion->code }}</div>
            @endif
            @if($order->payment)
                <div class="col-md-6"><strong>Pembayaran:</strong> {{ $order->payment->transaction_status }}</div>
            @endif
        </div>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Gambar</th><th>Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td><img src="{{ $item->product->image_url }}" class="product-img" alt="{{ $item->product->name }}"></td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan</a>
</div>
@endsection
