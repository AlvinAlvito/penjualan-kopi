@extends('layouts.app')
@section('content')
<h3 class="section-title">Manajemen Promo</h3>

<div class="card-pro mb-3">
    <div class="card-body">
        <form method="post" action="{{ route('admin.promotions.store') }}" class="row g-2">
            @csrf
            <div class="col-md-2"><input name="code" class="form-control" placeholder="Kode" required></div>
            <div class="col-md-2"><select name="discount_type" class="form-select"><option value="percentage">Persentase</option><option value="fixed">Nominal</option></select></div>
            <div class="col-md-2"><input name="discount_value" type="number" class="form-control" placeholder="Nilai" required></div>
            <div class="col-md-2"><input name="quota" type="number" class="form-control" placeholder="Kuota" required></div>
            <div class="col-md-2"><input name="starts_at" type="datetime-local" class="form-control"></div>
            <div class="col-md-2"><input name="ends_at" type="datetime-local" class="form-control"></div>
            <div class="col-12">
                <select name="product_ids[]" class="form-select" multiple>
                    @foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                </select>
                <small class="muted">Kosongkan pilihan produk jika promo berlaku global.</small>
            </div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end"><button class="btn btn-primary btn-pill">Tambah Promo</button></div>
        </form>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Kode</th><th>Tipe</th><th>Nilai</th><th>Kuota</th><th>Terpakai</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($promotions as $promo)
                <tr>
                    <td><strong>{{ $promo->code }}</strong></td>
                    <td>{{ $promo->discount_type }}</td>
                    <td>{{ $promo->discount_value }}</td>
                    <td>{{ $promo->quota }}</td>
                    <td>{{ $promo->used_count }}</td>
                    <td>{!! $promo->is_active ? '<span class="chip">AKTIF</span>' : '<span class="chip" style="background:#fee2e2;border-color:#fecaca;color:#991b1b">NONAKTIF</span>' !!}</td>
                    <td>
                        <form method="post" action="{{ route('admin.promotions.destroy', $promo) }}" data-confirm="Hapus promo ini?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center muted py-4">Belum ada promo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $promotions->links() }}</div>
@endsection
