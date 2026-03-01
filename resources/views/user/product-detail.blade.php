@extends('layouts.app')
@section('content')
<h3>{{ $product->name }}</h3>
<p class="badge text-bg-secondary">{{ $product->processing_method }}</p>
<p>{{ $product->description }}</p>
<p>Stok: {{ $product->stock }}</p>
<p class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
@auth
@if(!auth()->user()->isAdmin())
<div class="d-flex gap-2 mb-3">
    <form method="post" action="{{ route('cart.store') }}" class="d-flex gap-2">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="number" min="1" name="qty" value="1" class="form-control" style="max-width:130px">
        <button class="btn btn-dark">Tambah ke Keranjang</button>
    </form>
    <form method="post" action="{{ route('wishlist.store', $product->slug) }}">@csrf<button class="btn btn-outline-dark">Tambah Wishlist</button></form>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>Tulis Ulasan</h5>
        <form method="post" action="{{ route('reviews.store', $product->slug) }}" class="row g-2">
            @csrf
            <div class="col-md-2">
                <select class="form-select" name="rating">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} bintang</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-8"><input class="form-control" name="review_text" placeholder="Tulis pengalaman Anda"></div>
            <div class="col-md-2"><button class="btn btn-dark w-100">Kirim</button></div>
        </form>
    </div>
</div>
@endif
@endauth

<div class="card">
    <div class="card-body">
        <h5>Ulasan Pengguna</h5>
        @forelse($product->reviews as $review)
            <div class="border-bottom py-2">
                <b>{{ $review->user->name }}</b> - {{ str_repeat('?', $review->rating) }}
                <div>{{ $review->review_text }}</div>
            </div>
        @empty
            <div class="text-muted">Belum ada ulasan.</div>
        @endforelse
    </div>
</div>
@endsection
