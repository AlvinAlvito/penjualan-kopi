@extends('layouts.app')
@section('content')
<style>
    .chart-wrap {
        position: relative;
        height: 320px;
        max-height: 320px;
    }

    .chart-wrap canvas {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }

    @media (max-width: 991px) {
        .chart-wrap {
            height: 260px;
            max-height: 260px;
        }
    }
</style>

<h3 class="section-title">Admin Dashboard</h3>

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="muted">Total User</div><h4 class="mb-0">{{ $stats['total_users'] }}</h4></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="muted">Total Produk</div><h4 class="mb-0">{{ $stats['total_products'] }}</h4></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="muted">Total Order</div><h4 class="mb-0">{{ $stats['total_orders'] }}</h4></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="muted">Revenue</div><h4 class="mb-0">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h4></div></div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="stat-card">Promo Aktif<br><strong>{{ $stats['active_promotions'] }}</strong></div></div>
    <div class="col-md-4"><div class="stat-card">Review Pending<br><strong>{{ $stats['pending_reviews'] }}</strong></div></div>
    <div class="col-md-4"><div class="stat-card">Notif Belum Dibaca<br><strong>{{ $stats['unread_notifications'] }}</strong></div></div>
</div>

<div class="card-pro">
    <div class="card-body d-flex flex-wrap gap-2">
        <a class="btn btn-outline-dark" href="{{ route('admin.categories.index') }}">Kategori</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.products.index') }}">Produk</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.orders.index') }}">Order</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.promotions.index') }}">Promo</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.reviews.index') }}">Review</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.notifications.index') }}">Notifikasi</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.recommendation-analytics') }}">Analitik CBF</a>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Distribusi Status Order</h5>
                <div class="chart-wrap">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Top Produk Terjual</h5>
                <div class="chart-wrap">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
    const statusLabels = @json($orderByStatus->keys()->values());
    const statusValues = @json($orderByStatus->values());

    const productLabels = @json($topProducts->pluck('name'));
    const productValues = @json($topProducts->pluck('sold'));

    const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text').trim() || '#0f172a';
    const lineColor = getComputedStyle(document.documentElement).getPropertyValue('--line').trim() || '#e2e8f0';

    const makeBaseOptions = () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: textColor }
            }
        },
        scales: {
            x: { ticks: { color: textColor }, grid: { color: lineColor } },
            y: { ticks: { color: textColor }, grid: { color: lineColor } }
        }
    });

    const statusEl = document.getElementById('orderStatusChart');
    if (statusEl) {
        new Chart(statusEl, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#0ea5e9', '#14b8a6', '#22c55e', '#f59e0b', '#8b5cf6', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor } }
                }
            }
        });
    }

    const topEl = document.getElementById('topProductsChart');
    if (topEl) {
        new Chart(topEl, {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Terjual',
                    data: productValues,
                    backgroundColor: '#14b8a6',
                    borderRadius: 8,
                    maxBarThickness: 36
                }]
            },
            options: makeBaseOptions()
        });
    }
})();
</script>
@endsection
