@extends('layouts.app')
@section('content')
<div class="hero-panel p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="chip mb-2"><i class="bi bi-stars me-1"></i>Specialty Arabika Gayo</span>
            <h1 class="brand-serif mb-2">Belanja Kopi Takengon Dengan Pengalaman Lebih Personal</h1>
            <p class="text-white-50 mb-0">Temukan varian rasa yang paling sesuai melalui rekomendasi berbasis preferensi Anda.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('catalog.index') }}" class="btn btn-light btn-pill px-4"><i class="bi bi-arrow-right-circle me-1"></i>Lihat Katalog</a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="section-title mb-0">Produk Terbaru</h3>
</div>
<div class="row g-3 mb-4">
    @foreach($products as $product)
        <div class="col-md-6 col-xl-3">
            <article class="card-pro h-100">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-cover">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                    <p class="muted small mb-2">{{ Str::limit($product->description, 82) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                        <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye me-1"></i>Detail</a>
                    </div>
                </div>
            </article>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card-pro">
            <div class="card-body">
                <h4 class="section-title">Rekomendasi Cepat</h4>
                <div class="row g-2">
                @forelse($recommendations as $item)
                    <div class="col-md-4">
                        <div class="border rounded-3 p-2 h-100">
                            <div class="d-flex gap-2 align-items-center">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="product-img" style="width:48px;height:48px;">
                                <div>
                                    <div class="fw-semibold">#{{ $item['rank'] }} {{ $item['product_name'] }}</div>
                                    <small class="muted">Skor {{ $item['final_score'] }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="muted mb-0">Login untuk melihat rekomendasi personal.</p>
                @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
