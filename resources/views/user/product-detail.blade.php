@extends('layouts.app')
@section('content')
<div class="card-pro mb-3">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-lg-5">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 rounded-4" style="height:350px;object-fit:cover;">
            </div>
            <div class="col-lg-7">
                <span class="chip mb-2">{{ strtoupper(str_replace('_', ' ', $product->processing_method)) }}</span>
                <h2 class="fw-bold mb-2">{{ $product->name }}</h2>
                <p class="muted">{{ $product->description }}</p>
                <div class="d-flex align-items-center gap-4 my-3">
                    <h4 class="mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                    <span class="muted">Stok: {{ $product->stock }}</span>
                </div>

                @auth
                @if(!auth()->user()->isAdmin())
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <form method="post" action="{{ route('cart.store') }}" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" min="1" name="qty" value="1" class="form-control" style="max-width:100px;">
                        <button class="btn btn-primary btn-pill">Tambah Keranjang</button>
                    </form>
                    <form method="post" action="{{ route('wishlist.store', $product->slug) }}">
                        @csrf
                        <button class="btn btn-outline-dark">Wishlist</button>
                    </form>
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
@if(!auth()->user()->isAdmin())
<div class="card-pro mb-3">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tulis Ulasan</h5>
        <form method="post" action="{{ route('reviews.store', $product->slug) }}" class="row g-2">
            @csrf
            <div class="col-md-2">
                <select class="form-select" name="rating">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} Bintang</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-8"><input class="form-control" name="review_text" placeholder="Bagaimana pengalaman rasa kopi ini?"></div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary btn-pill">Kirim</button></div>
        </form>
    </div>
</div>
@endif
@endauth

<div class="card-pro">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Ulasan Pengguna</h5>
        @forelse($product->reviews as $review)
            <div class="border-bottom py-2">
                <div class="d-flex justify-content-between">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-warning">{{ str_repeat('?', $review->rating) }}</span>
                </div>
                <div class="muted">{{ $review->review_text }}</div>
            </div>
        @empty
            <p class="muted mb-0">Belum ada ulasan terpublikasi.</p>
        @endforelse
    </div>
</div>
@endsection
