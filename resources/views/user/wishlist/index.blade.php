@extends('layouts.app')
@section('content')
<h3>Wishlist Saya</h3>
<div class="row g-3">
@forelse($items as $item)
    <div class="col-md-4">
        <div class="card h-100">
        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="height:180px;object-fit:cover;">
        <div class="card-body">
            <h5>{{ $item->product->name }}</h5>
            <p>{{ Str::limit($item->product->description, 80) }}</p>
            <div class="d-flex gap-2">
                <a href="{{ route('catalog.show', $item->product->slug) }}" class="btn btn-sm btn-outline-dark">Detail</a>
                <form method="post" action="{{ route('wishlist.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </div>
        </div></div>
    </div>
@empty
    <p class="text-muted">Wishlist masih kosong.</p>
@endforelse
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
