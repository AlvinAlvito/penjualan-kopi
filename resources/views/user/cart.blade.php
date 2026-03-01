@extends('layouts.app')
@section('content')
<h3>Keranjang</h3>
<table class="table">
    <thead><tr><th>Gambar</th><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
        @forelse($cart->items as $item)
            <tr>
                <td><img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;"></td>
                <td>{{ $item->product->name }}</td>
                <td>
                    <form method="post" action="{{ route('cart.update', $item) }}" class="d-flex gap-2">
                        @csrf @method('PATCH')
                        <input type="number" min="1" name="qty" value="{{ $item->qty }}" class="form-control" style="max-width:100px">
                        <button class="btn btn-sm btn-outline-dark">Update</button>
                    </form>
                </td>
                <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                <td>
                    <form method="post" action="{{ route('cart.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Keranjang kosong.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="text-end">
    <h5>Total: Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</h5>
    <a href="{{ route('checkout.show') }}" class="btn btn-dark">Lanjut Checkout</a>
</div>
@endsection
