@extends('layouts.app')
@section('content')
<h3>Detail Order {{ $order->invoice_no }}</h3>
<p>User: {{ $order->user->name }} ({{ $order->user->email }})</p>
<p>Status: <b>{{ $order->status }}</b></p>
<p>Subtotal: Rp {{ number_format($order->subtotal,0,',','.') }}</p>
<p>Diskon: Rp {{ number_format($order->discount_amount,0,',','.') }}</p>
@if($order->promotion)
<p>Promo: {{ $order->promotion->code }}</p>
@endif
<p>Total: Rp {{ number_format($order->total,0,',','.') }}</p>
<form method="post" action="{{ route('admin.orders.update-status', $order) }}" class="row g-2 mb-4">
    @csrf @method('PATCH')
    <div class="col-md-3">
        <select name="status" class="form-select">
            @foreach(['pending','paid','processing','shipped','done','cancelled'] as $s)
                <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3"><input name="courier" class="form-control" placeholder="Kurir"></div>
    <div class="col-md-3"><input name="service" class="form-control" placeholder="Service"></div>
    <div class="col-md-3"><input name="tracking_no" class="form-control" placeholder="Resi"></div>
    <div class="col-12"><button class="btn btn-dark">Update Status</button></div>
</form>
<table class="table table-sm">
    <thead><tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>
    <tbody>@foreach($order->items as $item)<tr><td>{{ $item->product->name }}</td><td>{{ $item->qty }}</td><td>{{ number_format($item->subtotal,0,',','.') }}</td></tr>@endforeach</tbody>
</table>
@endsection
