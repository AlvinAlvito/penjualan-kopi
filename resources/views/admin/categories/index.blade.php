@extends('layouts.app')
@section('content')
<h3 class="section-title">Manajemen Kategori</h3>

<div class="card-pro mb-3">
    <div class="card-body">
        <form method="post" action="{{ route('admin.categories.store') }}" class="row g-2">
            @csrf
            <div class="col-md-4"><input name="name" class="form-control" placeholder="Nama kategori" required></div>
            <div class="col-md-6"><input name="description" class="form-control" placeholder="Deskripsi"></div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary btn-pill">Tambah</button></div>
        </form>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{!! $category->is_active ? '<span class="chip">AKTIF</span>' : '<span class="chip" style="background:#fee2e2;border-color:#fecaca;color:#991b1b">NONAKTIF</span>' !!}</td>
                <td class="d-flex gap-2">
                    <form method="post" action="{{ route('admin.categories.update', $category) }}" class="d-flex gap-1">
                        @csrf @method('PATCH')
                        <input name="name" value="{{ $category->name }}" class="form-control form-control-sm">
                        <input type="hidden" name="description" value="{{ $category->description }}">
                        <input type="hidden" name="is_active" value="{{ $category->is_active ? 1 : 0 }}">
                        <button class="btn btn-sm btn-outline-dark">Update</button>
                    </form>
                    <form method="post" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Hapus kategori ini?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center muted py-4">Belum ada kategori.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection
