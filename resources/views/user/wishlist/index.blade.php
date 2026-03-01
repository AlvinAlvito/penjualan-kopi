@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="section-title mb-0">Wishlist Saya</h3>
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-search me-1"></i>Jelajahi Produk</a>
</div>

<div class="row g-3">
@forelse($items as $item)
    <div class="col-md-6 col-xl-4">
        <article class="card-pro h-100">
            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-cover">
            <div class="card-body">
                <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                <p class="muted small mb-2">{{ Str::limit($item->product->description, 92) }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('catalog.show', $item->product->slug) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye me-1"></i>Detail</a>
                    <form method="post" action="{{ route('wishlist.destroy', $item) }}" data-confirm="Hapus item ini dari wishlist?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                    </form>
                </div>
            </div>
        </article>
    </div>
@empty
    <div class="col-12"><div class="card-pro"><div class="card-body text-center muted">Wishlist Anda masih kosong.</div></div></div>
@endforelse
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
