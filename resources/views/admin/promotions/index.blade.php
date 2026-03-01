@extends('layouts.app')
@section('content')
<h3>Manajemen Promo</h3>
<form method="post" action="{{ route('admin.promotions.store') }}" class="row g-2 mb-4">
    @csrf
    <div class="col-md-2"><input name="code" class="form-control" placeholder="Kode" required></div>
    <div class="col-md-2">
        <select name="discount_type" class="form-select"><option value="percentage">%</option><option value="fixed">Nominal</option></select>
    </div>
    <div class="col-md-2"><input name="discount_value" type="number" class="form-control" placeholder="Nilai" required></div>
    <div class="col-md-2"><input name="quota" type="number" class="form-control" placeholder="Kuota" required></div>
    <div class="col-md-2"><input name="starts_at" type="datetime-local" class="form-control"></div>
    <div class="col-md-2"><input name="ends_at" type="datetime-local" class="form-control"></div>
    <div class="col-md-12">
        <select name="product_ids[]" class="form-select" multiple>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Kosongkan untuk promo global.</small>
    </div>
    <div class="col-12"><button class="btn btn-dark">Tambah Promo</button></div>
</form>

<table class="table table-bordered table-sm">
    <thead><tr><th>Kode</th><th>Tipe</th><th>Nilai</th><th>Kuota</th><th>Terpakai</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
        @foreach($promotions as $promo)
            <tr>
                <td>{{ $promo->code }}</td>
                <td>{{ $promo->discount_type }}</td>
                <td>{{ $promo->discount_value }}</td>
                <td>{{ $promo->quota }}</td>
                <td>{{ $promo->used_count }}</td>
                <td>{{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                <td>
                    <form method="post" action="{{ route('admin.promotions.destroy', $promo) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $promotions->links() }}
@endsection
