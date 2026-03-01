@extends('layouts.app')
@section('content')
<h3 class="section-title">Detail Order {{ $order->invoice_no }}</h3>

<div class="card-pro mb-3">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6"><strong>User:</strong> {{ $order->user->name }} ({{ $order->user->email }})</div>
            <div class="col-md-6"><strong>Status:</strong> <span class="chip">{{ strtoupper($order->status) }}</span></div>
            <div class="col-md-4"><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal,0,',','.') }}</div>
            <div class="col-md-4"><strong>Diskon:</strong> Rp {{ number_format($order->discount_amount,0,',','.') }}</div>
            <div class="col-md-4"><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</div>
            @if($order->promotion)
                <div class="col-md-6"><strong>Promo:</strong> {{ $order->promotion->code }}</div>
            @endif
        </div>

        <hr>

        <form method="post" action="{{ route('admin.orders.update-status', $order) }}" class="row g-2">
            @csrf @method('PATCH')
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['pending','paid','processing','shipped','done','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($order->status === $s)>{{ strtoupper($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Kurir</label><input name="courier" class="form-control" placeholder="Kurir"></div>
            <div class="col-md-3"><label class="form-label">Service</label><input name="service" class="form-control" placeholder="Service"></div>
            <div class="col-md-3"><label class="form-label">No Resi</label><input name="tracking_no" class="form-control" placeholder="Resi"></div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end"><button class="btn btn-primary btn-pill">Update Status</button></div>
        </form>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Gambar</th><th>Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td><img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-img"></td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
