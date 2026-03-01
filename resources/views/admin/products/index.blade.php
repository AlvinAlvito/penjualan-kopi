@extends('layouts.app')
@section('content')
<h3>Manajemen Produk</h3>
<form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="row g-2 mb-4">
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
    <div class="col-md-4"><input type="file" name="image" class="form-control" accept="image/*"></div>
    <div class="col-12"><button class="btn btn-dark">Tambah Produk</button></div>
</form>
<table class="table table-bordered table-sm">
    <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Proses</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td><img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>{{ number_format($product->price,0,',','.') }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->processing_method }}</td>
            <td>
                <details>
                    <summary class="btn btn-sm btn-outline-dark mb-2">Edit</summary>
                    <form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="row g-1 mb-2">
                        @csrf
                        @method('PATCH')
                        <div class="col-12"><input name="name" class="form-control form-control-sm" value="{{ $product->name }}" required></div>
                        <div class="col-6"><input name="price" type="number" class="form-control form-control-sm" value="{{ $product->price }}" required></div>
                        <div class="col-6"><input name="stock" type="number" class="form-control form-control-sm" value="{{ $product->stock }}" required></div>
                        <div class="col-6"><input name="weight_gram" type="number" class="form-control form-control-sm" value="{{ $product->weight_gram }}" required></div>
                        <div class="col-6">
                            <select name="processing_method" class="form-select form-select-sm" required>
                                @foreach(['full_wash' => 'Full Wash', 'semi_wash' => 'Semi Wash', 'natural' => 'Natural', 'honey' => 'Honey', 'wine' => 'Wine'] as $value => $label)
                                    <option value="{{ $value }}" @selected($product->processing_method === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <select name="category_id" class="form-select form-select-sm" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($product->category_id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12"><textarea name="description" class="form-control form-control-sm" rows="2" required>{{ $product->description }}</textarea></div>
                        <div class="col-12"><input type="file" name="image" class="form-control form-control-sm" accept="image/*"></div>
                        <div class="col-12 form-check ms-1">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="active-{{ $product->id }}" @checked($product->is_active)>
                            <label class="form-check-label" for="active-{{ $product->id }}">Aktif</label>
                        </div>
                        <div class="col-12"><button class="btn btn-sm btn-dark w-100">Simpan</button></div>
                    </form>
                </details>
                <form method="post" action="{{ route('admin.products.destroy', $product) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $products->links() }}
@endsection
