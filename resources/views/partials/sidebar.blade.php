<div class="d-flex align-items-center gap-2 mb-3 px-1">
    <span class="chip"><i class="bi bi-shield-check me-1"></i>Admin</span>
    <strong>Backoffice</strong>
</div>

<nav class="d-flex flex-column gap-1">
    <a class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a class="admin-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
        <i class="bi bi-tags"></i> Kategori
    </a>
    <a class="admin-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
        <i class="bi bi-box-seam"></i> Produk
    </a>
    <a class="admin-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i class="bi bi-receipt"></i> Pesanan
    </a>
    <a class="admin-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}" href="{{ route('admin.promotions.index') }}">
        <i class="bi bi-ticket-perforated"></i> Promo
    </a>
    <a class="admin-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
        <i class="bi bi-chat-left-text"></i> Review
    </a>
    <a class="admin-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
        <i class="bi bi-bell"></i> Notifikasi
    </a>
    <a class="admin-link {{ request()->routeIs('admin.recommendation-analytics') ? 'active' : '' }}" href="{{ route('admin.recommendation-analytics') }}">
        <i class="bi bi-graph-up"></i> Analitik CBF
    </a>
    <a class="admin-link {{ request()->routeIs('admin.journal-validation') ? 'active' : '' }}" href="{{ route('admin.journal-validation') }}">
        <i class="bi bi-journal-check"></i> Validasi Jurnal
    </a>
</nav>
