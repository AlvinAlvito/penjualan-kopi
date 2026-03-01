@extends('layouts.app')
@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <h3>Produk Terbaru</h3>
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-md-6">
                    <div class="card h-100">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="height:180px;object-fit:cover;">
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p class="small text-muted">{{ Str::limit($product->description, 80) }}</p>
                            <p class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-dark">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        <h4>Rekomendasi Cepat</h4>
        @forelse($recommendations as $item)
            <div class="border rounded p-2 mb-2">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" style="width:44px;height:44px;object-fit:cover;border-radius:6px;">
                    <div>{{ $item['rank'] }}. {{ $item['product_name'] }}</div>
                </div>
                <small>Skor: {{ $item['final_score'] }}</small>
            </div>
        @empty
            <p class="text-muted">Login untuk rekomendasi personal.</p>
        @endforelse
    </div>
</div>
@endsection
