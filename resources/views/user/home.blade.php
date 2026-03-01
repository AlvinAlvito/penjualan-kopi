@extends('layouts.app')
@section('content')
<style>
    .product-slider-shell {
        border: 1px solid var(--line);
        border-radius: 20px;
        background: linear-gradient(140deg, rgba(15, 118, 110, .08), rgba(20, 184, 166, .05), rgba(14, 165, 233, .06));
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .product-slider-head {
        padding: 1rem 1rem .4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .product-slide-card {
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
        box-shadow: 0 8px 22px rgba(15, 23, 42, .08);
        overflow: hidden;
        height: 100%;
    }

    .product-slide-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 48px;
        height: 48px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(15, 23, 42, .72);
        border-radius: 50%;
        opacity: .9;
    }

    .carousel-control-prev { left: 12px; }
    .carousel-control-next { right: 12px; }

    .carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #0f766e;
    }

    @media (max-width: 991px) {
        .product-slide-img { height: 160px; }
    }

    .gallery-slider-shell {
        border: 1px solid var(--line);
        border-radius: 20px;
        overflow: hidden;
        background: var(--surface);
        box-shadow: var(--shadow);
    }

    .gallery-slide {
        position: relative;
        min-height: 340px;
    }

    .gallery-slide img {
        width: 100%;
        height: 340px;
        object-fit: cover;
        display: block;
    }

    .gallery-caption {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 1rem 1.1rem;
        background: linear-gradient(180deg, rgba(2, 6, 23, 0), rgba(2, 6, 23, .82));
        color: #fff;
    }

    .gallery-caption p {
        margin: 0;
        color: rgba(255, 255, 255, .86);
        font-size: .92rem;
    }

    @media (max-width: 991px) {
        .gallery-slide,
        .gallery-slide img { height: 260px; min-height: 260px; }
        .gallery-caption p { font-size: .84rem; }
    }
</style>

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

<section class="product-slider-shell mb-4">
    <div class="product-slider-head">
        <div>
            <h4 class="fw-bold mb-1">Highlight Produk</h4>
            <small class="muted">Geser kartu produk untuk melihat varian kopi unggulan.</small>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-grid me-1"></i>Lihat Semua
        </a>
    </div>

    @php $slides = $products->chunk(3); @endphp
    <div id="homeProductCarousel" class="carousel slide px-5 pb-4" data-bs-ride="carousel" data-bs-interval="4200">
        <div class="carousel-indicators">
            @foreach($slides as $index => $chunk)
                <button type="button" data-bs-target="#homeProductCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($slides as $index => $chunk)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row g-3">
                        @foreach($chunk as $product)
                            <div class="col-12 col-md-4">
                                <article class="product-slide-card">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-slide-img">
                                    <div class="p-3">
                                        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                        <p class="muted small mb-2">{{ Str::limit($product->description, 80) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                            <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-primary btn-pill">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeProductCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeProductCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<section class="gallery-slider-shell mb-4">
    <div class="product-slider-head">
        <div>
            <h4 class="fw-bold mb-1">Galeri Cita Rasa</h4>
            <small class="muted">Satu gambar per slide dengan deskripsi singkat tiap varian kopi.</small>
        </div>
    </div>
    <div id="homeImageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4600">
        <div class="carousel-indicators mb-2">
            @foreach($products as $index => $product)
                <button type="button" data-bs-target="#homeImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($products as $index => $product)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="gallery-slide">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        <div class="gallery-caption">
                            <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                            <p>{{ Str::limit($product->description, 150) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeImageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeImageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

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
