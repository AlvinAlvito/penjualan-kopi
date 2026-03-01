@extends('layouts.app')
@section('content')
<h3>Detail Pesanan: {{ $order->invoice_no }}</h3>
<p>Status: <b>{{ $order->status }}</b></p>
<p>Total: <b>Rp {{ number_format($order->total, 0, ',', '.') }}</b></p>
@if($order->discount_amount > 0)
    <p>Diskon: Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</p>
@endif
@if($order->promotion)
    <p>Promo: <b>{{ $order->promotion->code }}</b></p>
@endif
@if($order->payment)
    <p>Payment status: {{ $order->payment->transaction_status }}</p>
    @if($order->payment->provider_ref)
        <p>Snap token: <code>{{ $order->payment->provider_ref }}</code></p>
    @endif
@endif
<table class="table table-sm">
    <thead><tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>
    <tbody>
        @foreach($order->items as $item)
            <tr><td>{{ $item->product->name }}</td><td>{{ $item->qty }}</td><td>{{ number_format($item->subtotal, 0, ',', '.') }}</td></tr>
        @endforeach
    </tbody>
</table>
@endsection
