@extends('layouts.app')
@section('content')
<h3>Manajemen Produk</h3>
<form method="post" action="{{ route('admin.products.store') }}" class="row g-2 mb-4">
    @csrf
    <div class="col-md-3"><input name="name" class="form-control" placeholder="Nama" required></div>
    <div class="col-md-2"><input name="price" type="number" class="form-control" placeholder="Harga" required></div>
    <div class="col-md-1"><input name="stock" type="number" class="form-control" placeholder="Stok" required></div>
    <div class="col-md-2"><input name="weight_gram" type="number" class="form-control" placeholder="Berat" required></div>
    <div class="col-md-2">
        <select name="processing_method" class="form-select" required>
            <option value="full_wash">Full Wash</option><option value="semi_wash">Semi Wash</option><option value="natural">Natural</option><option value="honey">Honey</option><option value="wine">Wine</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach
        </select>
    </div>
    <div class="col-12"><textarea name="description" class="form-control" placeholder="Deskripsi" required></textarea></div>
    <div class="col-12"><button class="btn btn-dark">Tambah Produk</button></div>
</form>
<table class="table table-bordered table-sm">
    <thead><tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Proses</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>{{ number_format($product->price,0,',','.') }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->processing_method }}</td>
            <td>
                <form method="post" action="{{ route('admin.products.destroy', $product) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $products->links() }}
@endsection
