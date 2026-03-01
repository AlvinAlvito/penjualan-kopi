@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="section-title mb-0">Keranjang Belanja</h3>
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark btn-sm">+ Tambah Produk</a>
</div>

<div class="table-pro mb-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>Gambar</th><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
            @forelse($cart->items as $item)
                <tr>
                    <td><img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-img"></td>
                    <td><strong>{{ $item->product->name }}</strong></td>
                    <td>
                        <form method="post" action="{{ route('cart.update', $item) }}" class="d-flex gap-2">
                            @csrf @method('PATCH')
                            <input type="number" min="1" name="qty" value="{{ $item->qty }}" class="form-control" style="max-width:90px">
                            <button class="btn btn-sm btn-outline-dark">Update</button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                    <td>
                        <form method="post" action="{{ route('cart.destroy', $item) }}" data-confirm="Hapus produk ini dari keranjang?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center muted py-4">Keranjang masih kosong.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card-pro">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">Total: Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</h5>
        <a href="{{ route('checkout.show') }}" class="btn btn-primary btn-pill px-4">Lanjut Checkout</a>
    </div>
</div>
@endsection
