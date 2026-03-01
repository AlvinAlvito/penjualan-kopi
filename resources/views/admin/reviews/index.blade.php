@extends('layouts.app')
@section('content')
<h3 class="section-title">Moderasi Ulasan</h3>
<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Tanggal</th><th>User</th><th>Gambar</th><th>Produk</th><th>Rating</th><th>Ulasan</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $review->user->name }}</td>
                    <td><img src="{{ $review->product->image_url }}" alt="{{ $review->product->name }}" class="product-img" style="width:50px;height:50px"></td>
                    <td>{{ $review->product->name }}</td>
                    <td class="text-warning">{{ str_repeat('?', $review->rating) }}</td>
                    <td>{{ $review->review_text }}</td>
                    <td>{!! $review->is_published ? '<span class="chip">PUBLISHED</span>' : '<span class="chip" style="background:#fef9c3;border-color:#fde68a;color:#854d0e">PENDING</span>' !!}</td>
                    <td>
                        <form method="post" action="{{ route('admin.reviews.toggle', $review) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-dark">Toggle</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center muted py-4">Belum ada ulasan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $reviews->links() }}</div>
@endsection
