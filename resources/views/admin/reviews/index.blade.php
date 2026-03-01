@extends('layouts.app')
@section('content')
<h3>Moderasi Ulasan</h3>
<table class="table table-bordered table-sm">
    <thead><tr><th>Tanggal</th><th>User</th><th>Gambar</th><th>Produk</th><th>Rating</th><th>Ulasan</th><th>Status</th><th></th></tr></thead>
    <tbody>
        @foreach($reviews as $review)
            <tr>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $review->user->name }}</td>
                <td><img src="{{ $review->product->image_url }}" alt="{{ $review->product->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;"></td>
                <td>{{ $review->product->name }}</td>
                <td>{{ $review->rating }}</td>
                <td>{{ $review->review_text }}</td>
                <td>{{ $review->is_published ? 'Published' : 'Pending' }}</td>
                <td>
                    <form method="post" action="{{ route('admin.reviews.toggle', $review) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-dark">Toggle</button></form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $reviews->links() }}
@endsection
