@extends('layouts.app')
@section('content')
<h3>Rekomendasi Produk</h3>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-9"><input name="query" class="form-control" value="{{ $query }}" placeholder="contoh: segar fruity aftertaste lembut"></div>
    <div class="col-md-3"><button class="btn btn-dark w-100">Proses</button></div>
</form>
<table class="table table-bordered">
    <thead><tr><th>Rank</th><th>Gambar</th><th>Produk</th><th>Sim Query</th><th>Sim Profile</th><th>Final</th></tr></thead>
    <tbody>
    @forelse($result as $row)
        <tr>
            <td>{{ $row['rank'] }}</td>
            <td><img src="{{ $row['image_url'] }}" alt="{{ $row['product_name'] }}" style="width:52px;height:52px;object-fit:cover;border-radius:8px;"></td>
            <td>{{ $row['product_name'] }}</td>
            <td>{{ $row['similarity_query'] }}</td>
            <td>{{ $row['similarity_profile'] }}</td>
            <td>{{ $row['final_score'] }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center">Masukkan query untuk melihat hasil.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
