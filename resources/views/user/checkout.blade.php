@extends('layouts.app')
@section('content')
<h3>Checkout</h3>
<form method="get" action="{{ route('checkout.show') }}" class="row g-2 mb-3">
    <div class="col-md-9"><input name="promo_code" class="form-control" value="{{ $promoCode ?? '' }}" placeholder="Masukkan kode promo"></div>
    <div class="col-md-3"><button class="btn btn-outline-dark w-100">Cek Promo</button></div>
</form>
@if(isset($promotion) && $promotion)
    <div class="alert alert-info">Promo aktif: <b>{{ $promotion->code }}</b>, potongan Rp {{ number_format($discount, 0, ',', '.') }}</div>
@endif

<form method="post" action="{{ route('checkout.process') }}" class="row g-3">
    @csrf
    <input type="hidden" name="promo_code" value="{{ $promotion->code ?? '' }}">
    <div class="col-12">
        <label>Alamat Pengiriman</label>
        <textarea name="address" class="form-control" required>{{ auth()->user()->default_address }}</textarea>
    </div>
    <div class="col-md-4"><input name="courier" class="form-control" placeholder="Kurir" required></div>
    <div class="col-md-4"><input name="service" class="form-control" placeholder="Service" required></div>
    <div class="col-md-4"><input name="shipping_cost" type="number" min="0" class="form-control" placeholder="Biaya Kirim" required></div>
    <div class="col-12"><textarea name="note" class="form-control" placeholder="Catatan"></textarea></div>
    <div class="col-12">
        <h6>Subtotal: Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</h6>
        <h6>Diskon: Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</h6>
        <button class="btn btn-dark">Buat Pesanan</button>
    </div>
</form>
@endsection
