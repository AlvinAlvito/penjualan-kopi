@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3" id="create-product">
    <h3 class="section-title mb-0">Manajemen Produk</h3>
    <button class="btn btn-primary btn-pill" data-bs-toggle="modal" data-bs-target="#createProductModal">
        <i class="bi bi-plus-circle me-1"></i>Tambah Produk
    </button>
</div>

<div class="card-pro mb-3">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="fw-semibold">Kelola Data Produk</div>
            <small class="muted">Tambah dan edit produk melalui dialog modal agar lebih ringkas.</small>
        </div>
        <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="bi bi-plus-circle me-1"></i>Buka Form Tambah
        </button>
    </div>
</div>

<div class="table-pro">
<table class="table align-middle">
    <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Proses</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($products as $product)
        <tr>
            <td><img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img"></td>
            <td><strong>{{ $product->name }}</strong></td>
            <td>{{ $product->category->name }}</td>
            <td>Rp {{ number_format($product->price,0,',','.') }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ strtoupper(str_replace('_', ' ', $product->processing_method)) }}</td>
            <td>
                <button
                    type="button"
                    class="btn btn-sm btn-outline-dark mb-2 js-edit-product"
                    data-bs-toggle="modal"
                    data-bs-target="#editProductModal"
                    data-id="{{ $product->id }}"
                    data-name="{{ e($product->name) }}"
                    data-price="{{ $product->price }}"
                    data-stock="{{ $product->stock }}"
                    data-weight="{{ $product->weight_gram }}"
                    data-method="{{ $product->processing_method }}"
                    data-category="{{ $product->category_id }}"
                    data-description="{{ e($product->description) }}"
                    data-active="{{ $product->is_active ? 1 : 0 }}"
                    data-update-url="{{ route('admin.products.update', $product) }}"
                >
                    <i class="bi bi-pencil-square me-1"></i>Edit
                </button>
                <form method="post" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Hapus produk ini?">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center muted py-4">Belum ada produk.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

<div class="mt-3">{{ $products->links() }}</div>

<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createProductForm" method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input name="name" class="form-control" placeholder="Nama produk" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga</label>
                        <input name="price" type="number" class="form-control" placeholder="Harga" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok</label>
                        <input name="stock" type="number" class="form-control" placeholder="Stok" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Berat (gram)</label>
                        <input name="weight_gram" type="number" class="form-control" placeholder="Berat (gr)" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Metode Proses</label>
                        <select name="processing_method" class="form-select" required>
                            <option value="full_wash">Full Wash</option>
                            <option value="semi_wash">Semi Wash</option>
                            <option value="natural">Natural</option>
                            <option value="honey">Honey</option>
                            <option value="wine">Wine</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea name="description" class="form-control" placeholder="Deskripsi produk" required></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="create-active" checked>
                            <label class="form-check-label" for="create-active">Produk aktif</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="createProductForm" class="btn btn-primary btn-pill px-4">
                    <i class="bi bi-check2-circle me-1"></i>Simpan Produk
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="post" action="" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga</label>
                        <input id="edit_price" name="price" type="number" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok</label>
                        <input id="edit_stock" name="stock" type="number" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Berat (gram)</label>
                        <input id="edit_weight_gram" name="weight_gram" type="number" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Metode Proses</label>
                        <select id="edit_processing_method" name="processing_method" class="form-select" required>
                            <option value="full_wash">Full Wash</option>
                            <option value="semi_wash">Semi Wash</option>
                            <option value="natural">Natural</option>
                            <option value="honey">Honey</option>
                            <option value="wine">Wine</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select id="edit_category_id" name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea id="edit_description" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input id="edit_is_active" class="form-check-input" type="checkbox" value="1" name="is_active">
                            <label class="form-check-label" for="edit_is_active">Produk aktif</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="editProductForm" class="btn btn-primary btn-pill px-4">
                    <i class="bi bi-check2-circle me-1"></i>Update Produk
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editForm = document.getElementById('editProductForm');
    if (!editForm) return;

    document.querySelectorAll('.js-edit-product').forEach((btn) => {
        btn.addEventListener('click', () => {
            editForm.action = btn.dataset.updateUrl || '';
            document.getElementById('edit_name').value = btn.dataset.name || '';
            document.getElementById('edit_price').value = btn.dataset.price || '';
            document.getElementById('edit_stock').value = btn.dataset.stock || '';
            document.getElementById('edit_weight_gram').value = btn.dataset.weight || '';
            document.getElementById('edit_processing_method').value = btn.dataset.method || 'full_wash';
            document.getElementById('edit_category_id').value = btn.dataset.category || '';
            document.getElementById('edit_description').value = btn.dataset.description || '';
            document.getElementById('edit_is_active').checked = String(btn.dataset.active || '0') === '1';
        });
    });
});
</script>
@endsection
