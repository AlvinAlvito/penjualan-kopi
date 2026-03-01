@extends('layouts.app')
@section('content')
<div class="card-pro mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="section-title mb-0">Katalog Produk</h3>
            <span class="muted">Temukan varian sesuai selera Anda</span>
        </div>
        <form class="row g-2 mt-2" method="get" action="{{ route('catalog.index') }}">
            <div class="col-md-5"><input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari produk kopi..."></div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(request('category')===$cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid"><button class="btn btn-primary btn-pill"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        </form>
    </div>
</div>

<div class="row g-3">
@forelse($products as $product)
    <div class="col-md-6 col-xl-4">
        <article class="card-pro h-100">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-cover">
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                <p class="small muted mb-2">{{ Str::limit($product->description, 88) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                    <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye me-1"></i>Lihat Detail</a>
                </div>
            </div>
        </article>
    </div>
@empty
    <div class="col-12">
        <div class="card-pro"><div class="card-body text-center muted">Produk belum tersedia.</div></div>
    </div>
@endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
