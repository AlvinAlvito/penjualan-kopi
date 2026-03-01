@extends('layouts.app')
@section('content')
<h3>Manajemen Kategori</h3>
<form method="post" action="{{ route('admin.categories.store') }}" class="row g-2 mb-3">
    @csrf
    <div class="col-md-4"><input name="name" class="form-control" placeholder="Nama kategori" required></div>
    <div class="col-md-6"><input name="description" class="form-control" placeholder="Deskripsi"></div>
    <div class="col-md-2"><button class="btn btn-dark w-100">Tambah</button></div>
</form>
<table class="table table-bordered">
    <thead><tr><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td>{{ $category->name }}</td>
            <td>{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</td>
            <td class="d-flex gap-1">
                <form method="post" action="{{ route('admin.categories.update', $category) }}" class="d-flex gap-1">
                    @csrf @method('PATCH')
                    <input name="name" value="{{ $category->name }}" class="form-control form-control-sm">
                    <input type="hidden" name="description" value="{{ $category->description }}">
                    <input type="hidden" name="is_active" value="{{ $category->is_active ? 1 : 0 }}">
                    <button class="btn btn-sm btn-outline-dark">Simpan</button>
                </form>
                <form method="post" action="{{ route('admin.categories.destroy', $category) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $categories->links() }}
@endsection
