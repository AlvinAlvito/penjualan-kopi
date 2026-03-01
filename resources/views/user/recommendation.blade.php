@extends('layouts.app')
@section('content')
<h3 class="section-title">Rekomendasi Produk</h3>
<div class="card-pro mb-3">
    <div class="card-body">
        <form method="get" class="row g-2">
            <div class="col-md-9"><input name="query" class="form-control" value="{{ $query }}" placeholder="Contoh: segar fruity aftertaste lembut"></div>
            <div class="col-md-3 d-grid"><button class="btn btn-primary btn-pill"><i class="bi bi-magic me-1"></i>Proses Rekomendasi</button></div>
        </form>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Rank</th><th>Produk</th><th>Skor Query</th><th>Skor Profil</th><th>Skor Akhir</th></tr></thead>
        <tbody>
            @forelse($result as $row)
                <tr>
                    <td><span class="chip">#{{ $row['rank'] }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $row['image_url'] }}" alt="{{ $row['product_name'] }}" class="product-img" style="width:52px;height:52px;">
                            <strong>{{ $row['product_name'] }}</strong>
                        </div>
                    </td>
                    <td>{{ $row['similarity_query'] }}</td>
                    <td>{{ $row['similarity_profile'] }}</td>
                    <td><strong>{{ $row['final_score'] }}</strong></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center muted py-4">Masukkan query untuk mendapatkan rekomendasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
