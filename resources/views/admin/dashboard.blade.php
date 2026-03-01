@extends('layouts.app')
@section('content')
<h3>Admin Dashboard</h3>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body"><div>User</div><h4>{{ $stats['total_users'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div>Produk</div><h4>{{ $stats['total_products'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div>Order</div><h4>{{ $stats['total_orders'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div>Revenue</div><h4>{{ number_format($stats['revenue'], 0, ',', '.') }}</h4></div></div></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body">Promo aktif: <b>{{ $stats['active_promotions'] }}</b></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">Review pending: <b>{{ $stats['pending_reviews'] }}</b></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">Notif belum dibaca: <b>{{ $stats['unread_notifications'] }}</b></div></div></div>
</div>
<div class="d-flex flex-wrap gap-2">
    <a class="btn btn-sm btn-dark" href="{{ route('admin.categories.index') }}">Kategori</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.products.index') }}">Produk</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.orders.index') }}">Orders</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.promotions.index') }}">Promo</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.reviews.index') }}">Review</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.notifications.index') }}">Notifikasi</a>
    <a class="btn btn-sm btn-dark" href="{{ route('admin.recommendation-analytics') }}">Analitik CBF</a>
</div>
@endsection
