@extends('layouts.app')
@section('content')
<h3>Katalog Produk</h3>
<form class="row g-2 mb-3" method="get" action="{{ route('catalog.index') }}">
    <div class="col-md-5"><input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari produk"></div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">Semua kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category')===$cat->slug)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3"><button class="btn btn-dark w-100">Filter</button></div>
</form>
<div class="row g-3">
@foreach($products as $product)
    <div class="col-md-4">
        <div class="card h-100"><div class="card-body">
            <h5>{{ $product->name }}</h5>
            <p class="small">{{ Str::limit($product->description, 70) }}</p>
            <p class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-outline-dark">Detail</a>
        </div></div>
    </div>
@endforeach
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
