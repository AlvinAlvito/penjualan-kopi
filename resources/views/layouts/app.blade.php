<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101828">
    <title>{{ $title ?? 'J2 Kopi Takengon' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script>
        (() => {
            const savedTheme = localStorage.getItem('j2-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <style>
        :root {
            --bg: #f6f8fb;
            --surface: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --primary: #0f766e;
            --primary-2: #14b8a6;
            --danger: #dc2626;
            --shadow: 0 10px 30px rgba(15, 23, 42, .08);
            --radius: 16px;
        }

        [data-theme='dark'] {
            --bg: #0b1220;
            --surface: #111a2b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --line: #223049;
            --shadow: 0 12px 30px rgba(2, 6, 23, .45);
        }

        * { box-sizing: border-box; }
        body {
            background:
                radial-gradient(1200px 420px at 85% -20%, rgba(20,184,166,.12), transparent 60%),
                radial-gradient(1000px 400px at -10% -20%, rgba(15,118,110,.08), transparent 55%),
                var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }

        .brand-serif { font-family: 'Fraunces', serif; }

        .navbar-pro {
            background: linear-gradient(135deg, #0b1220, #111827 50%, #0f172a);
            border-bottom: 1px solid rgba(255,255,255,.06);
            box-shadow: 0 10px 30px rgba(2, 6, 23, .4);
        }

        .navbar-pro .navbar-brand { font-weight: 700; letter-spacing: .2px; }
        .navbar-pro .nav-link { color: rgba(255,255,255,.76); font-weight: 500; }
        .navbar-pro .nav-link:hover, .navbar-pro .nav-link.active { color: #fff; }

        .btn-pill { border-radius: 999px; }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            border: 0;
            box-shadow: 0 8px 20px rgba(15,118,110,.25);
        }

        .btn-outline-dark, .btn-outline-light { border-radius: 12px; }

        .page-shell { padding: 1.4rem 0 2rem; }
        .admin-shell { display: grid; grid-template-columns: 260px minmax(0,1fr); gap: 1rem; }
        .admin-sidebar {
            position: sticky;
            top: 86px;
            align-self: start;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow);
            padding: .8rem;
        }
        .admin-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            border-radius: 10px;
            padding: .55rem .65rem;
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
        }
        .admin-link:hover { background: rgba(148,163,184,.14); }
        .admin-link.active {
            background: linear-gradient(135deg, rgba(15,118,110,.15), rgba(20,184,166,.12));
            color: #0f766e;
        }

        .hero-panel {
            border-radius: 20px;
            background: linear-gradient(120deg, #0f172a, #1f2937);
            color: #fff;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: auto -70px -70px auto;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle at center, rgba(20,184,166,.45), transparent 65%);
            border-radius: 50%;
        }

        .card-pro {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .card-pro .card-body { padding: 1rem 1rem; }
        .card-pro:hover { transform: translateY(-2px); transition: .2s ease; }

        .stat-card {
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            padding: .9rem 1rem;
        }

        .table-pro {
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        .table-pro table { margin: 0; }
        .table-pro th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
            border-bottom: 1px solid var(--line);
        }
        [data-theme='dark'] .table-pro th { background: #172337; color: #cbd5e1; }

        .table-pro td { vertical-align: middle; }

        .product-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--line);
            background: #f1f5f9;
        }

        .product-cover {
            width: 100%;
            height: 190px;
            object-fit: cover;
            border-radius: 14px 14px 0 0;
        }

        .form-control, .form-select, textarea {
            border-radius: 12px !important;
            border-color: #dbe3ee;
            min-height: 44px;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 .18rem rgba(239,68,68,.12) !important;
        }
        .form-control.is-valid, .form-select.is-valid {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 .18rem rgba(34,197,94,.12) !important;
        }
        .invalid-feedback.auto-feedback {
            display: block;
            font-size: .78rem;
            margin-top: .3rem;
        }
        .btn-submit-loading {
            pointer-events: none;
            opacity: .86;
        }

        textarea.form-control { min-height: 120px; }
        .form-control:focus, .form-select:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 .2rem rgba(14,165,233,.15);
        }

        .section-title {
            font-size: 1.28rem;
            font-weight: 800;
            margin-bottom: .8rem;
        }

        .muted { color: var(--muted); }

        [data-theme='dark'] .navbar-pro { background: linear-gradient(135deg, #020617, #0b1220 50%, #111827); }
        [data-theme='dark'] .btn-light { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        [data-theme='dark'] .btn-outline-dark { border-color: #334155; color: #e2e8f0; }
        [data-theme='dark'] .btn-outline-dark:hover { background: #1f2a3d; color: #fff; }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: .3rem .65rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            border: 1px solid #dbeafe;
            color: #0c4a6e;
            background: #f0f9ff;
        }

        .page-reveal {
            opacity: 0;
            animation: reveal .45s ease forwards;
        }

        .modal-backdrop { z-index: 2050 !important; }
        .modal { z-index: 2060 !important; }

        .top-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            z-index: 2000;
            background: linear-gradient(90deg, #06b6d4, #14b8a6);
            box-shadow: 0 0 14px rgba(20,184,166,.45);
            transition: width .25s ease;
        }
        .top-loading.show { width: 65%; }
        .top-loading.done { width: 100%; transition: width .2s ease; }

        .toast-container { z-index: 1500; }
        .toast-pro {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            color: var(--text);
            box-shadow: var(--shadow);
        }

        .skeleton-overlay {
            position: fixed;
            inset: 0;
            background: rgba(246,248,251,.86);
            backdrop-filter: blur(2px);
            z-index: 1040;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: 86px;
        }
        [data-theme='dark'] .skeleton-overlay { background: rgba(11,18,32,.8); }
        .skeleton-overlay.show { display: flex; }
        .skeleton-frame {
            width: min(1120px, calc(100vw - 2rem));
            display: grid;
            gap: .9rem;
        }
        .skeleton-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--surface);
            padding: 1rem;
            box-shadow: var(--shadow);
        }
        .skeleton-line {
            height: 12px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(148,163,184,.18), rgba(148,163,184,.36), rgba(148,163,184,.18));
            background-size: 220% 100%;
            animation: sk 1.2s linear infinite;
        }
        .skeleton-line.h18 { height: 18px; }
        .skeleton-line.w40 { width: 40%; }
        .skeleton-line.w60 { width: 60%; }
        .skeleton-line.w80 { width: 80%; }
        .skeleton-line.w100 { width: 100%; }
        .skeleton-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .75rem;
        }
        .skeleton-box {
            height: 96px;
            border-radius: 12px;
            background: linear-gradient(90deg, rgba(148,163,184,.16), rgba(148,163,184,.3), rgba(148,163,184,.16));
            background-size: 220% 100%;
            animation: sk 1.2s linear infinite;
        }

        @keyframes sk { to { background-position: -220% 0; } }

        .quick-fab-wrap {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .55rem;
        }
        .quick-panel {
            width: 260px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: var(--shadow);
            padding: .75rem;
            display: none;
        }
        .quick-panel.show { display: block; }
        .quick-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            color: var(--text);
            border-radius: 10px;
            padding: .5rem .55rem;
            font-weight: 600;
        }
        .quick-link:hover { background: rgba(148,163,184,.14); }
        .quick-fab {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            box-shadow: 0 12px 22px rgba(15,118,110,.35);
        }

        @keyframes reveal {
            to { opacity: 1; }
        }

        @media (max-width: 991px) {
            .product-cover { height: 170px; }
            .page-shell { padding: 1rem 0 1.6rem; }
            .admin-shell { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-pro sticky-top">
    <div class="container py-1">
        <a class="navbar-brand brand-serif" href="{{ route('home') }}">J2 Kopi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}"><i class="bi bi-grid me-1"></i>Katalog</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('recommendation.*') ? 'active' : '' }}" href="{{ route('recommendation.index') }}"><i class="bi bi-stars me-1"></i>Rekomendasi</a></li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}"><i class="bi bi-cart3 me-1"></i>Keranjang</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-1"></i>Wishlist</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-1"></i>Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-1"></i>Notifikasi</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    @if(auth()->user()->isAdmin() && request()->is('admin*'))
                        <button class="btn btn-sm btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarCanvas">
                            <i class="bi bi-layout-sidebar"></i>
                        </button>
                    @endif
                @endauth
                <button id="themeToggle" class="btn btn-sm btn-outline-light" type="button" title="Toggle Theme">
                    <i class="bi bi-moon-stars"></i>
                </button>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-person-circle me-1"></i>Profil</a>
                    @endif
                    <span class="text-white small fw-semibold d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-light"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-light"><i class="bi bi-person-plus me-1"></i>Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@php
    $isAdminView = auth()->check() && auth()->user()->isAdmin() && request()->is('admin*');
@endphp

@if($isAdminView)
<div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebarCanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Admin Panel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-2">
        @include('partials.sidebar')
    </div>
</div>
@endif

<main class="page-shell">
    <div class="container page-reveal">
        @if($isAdminView)
            <div class="admin-shell">
                <aside class="admin-sidebar d-none d-lg-block">
                    @include('partials.sidebar')
                </aside>
                <section>@yield('content')</section>
            </div>
        @else
            @yield('content')
        @endif
    </div>
</main>

<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
        <div class="toast toast-pro align-items-center show" role="alert" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle text-success me-1"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="toast toast-pro align-items-center show" role="alert" data-bs-delay="6000">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-exclamation-triangle text-danger me-1"></i>{{ $errors->first() }}</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

<div id="topLoading" class="top-loading"></div>
<div id="pageSkeleton" class="skeleton-overlay">
    <div class="skeleton-frame">
        <div class="skeleton-card">
            <div class="skeleton-line h18 w40 mb-2"></div>
            <div class="skeleton-line w80 mb-2"></div>
            <div class="skeleton-line w60"></div>
        </div>
        <div class="skeleton-grid">
            <div class="skeleton-box"></div>
            <div class="skeleton-box"></div>
            <div class="skeleton-box"></div>
        </div>
        <div class="skeleton-card">
            <div class="skeleton-line h18 w60 mb-2"></div>
            <div class="skeleton-line w100 mb-2"></div>
            <div class="skeleton-line w100 mb-2"></div>
            <div class="skeleton-line w80"></div>
        </div>
    </div>
</div>

@if($isAdminView)
<div class="quick-fab-wrap">
    <div class="quick-panel" id="quickPanel">
        <div class="small muted fw-semibold mb-1">Quick Actions</div>
        <a class="quick-link" href="{{ route('admin.products.index') }}#create-product"><i class="bi bi-plus-circle"></i>Tambah Produk</a>
        <a class="quick-link" href="{{ route('admin.promotions.index') }}#create-promo"><i class="bi bi-ticket-perforated"></i>Buat Promo</a>
        <a class="quick-link" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i>Lihat Pesanan</a>
        <a class="quick-link" href="{{ route('admin.notifications.index') }}"><i class="bi bi-bell"></i>Kirim Notifikasi</a>
    </div>
    <button type="button" id="quickFab" class="quick-fab" aria-label="Quick actions"><i class="bi bi-lightning-charge"></i></button>
</div>
@endif

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Konfirmasi Aksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary mb-0" id="confirmModalText">Lanjutkan aksi ini?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmModalOk">Ya, lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (() => {
        const themeToggle = document.getElementById('themeToggle');
        const setThemeIcon = (theme) => {
            if (!themeToggle) return;
            themeToggle.innerHTML = theme === 'dark'
                ? '<i class="bi bi-sun"></i>'
                : '<i class="bi bi-moon-stars"></i>';
        };

        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        setThemeIcon(currentTheme);

        themeToggle?.addEventListener('click', () => {
            const now = document.documentElement.getAttribute('data-theme') || 'light';
            const next = now === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('j2-theme', next);
            setThemeIcon(next);
        });

        const modalEl = document.getElementById('confirmModal');
        if (!modalEl) return;

        const modal = new bootstrap.Modal(modalEl);
        const textEl = document.getElementById('confirmModalText');
        const okBtn = document.getElementById('confirmModalOk');
        let targetForm = null;

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.matches('[data-confirm]')) return;
            if (form.dataset.confirmed === '1') return;
            event.preventDefault();
            targetForm = form;
            textEl.textContent = form.getAttribute('data-confirm') || 'Lanjutkan aksi ini?';
            modal.show();
        });

        okBtn?.addEventListener('click', () => {
            if (targetForm) {
                const f = targetForm;
                targetForm = null;
                modal.hide();
                f.dataset.confirmed = '1';
                if (typeof f.requestSubmit === 'function') {
                    f.requestSubmit();
                } else {
                    f.submit();
                }
            }
        });

        document.querySelectorAll('.toast').forEach((el) => {
            const t = new bootstrap.Toast(el);
            t.show();
        });

        const topLoading = document.getElementById('topLoading');
        const pageSkeleton = document.getElementById('pageSkeleton');
        const beginLoading = () => {
            if (!topLoading) return;
            topLoading.classList.add('show');
            topLoading.classList.remove('done');
            pageSkeleton?.classList.add('show');
        };
        const endLoading = () => {
            if (!topLoading) return;
            topLoading.classList.add('done');
            setTimeout(() => {
                topLoading.classList.remove('show', 'done');
                pageSkeleton?.classList.remove('show');
            }, 220);
        };

        window.addEventListener('pageshow', endLoading);

        document.addEventListener('click', (event) => {
            const link = event.target.closest('a');
            if (!link) return;
            const href = link.getAttribute('href') || '';
            if (href.startsWith('#') || href.startsWith('javascript:')) return;
            if (link.target === '_blank') return;
            beginLoading();
        });

        const getAutoMessage = (field) => {
            const label = field.getAttribute('data-label') || field.getAttribute('placeholder') || field.name || 'Field ini';
            if (field.validity.valueMissing) return `${label} wajib diisi.`;
            if (field.validity.typeMismatch) return `${label} tidak valid.`;
            if (field.validity.patternMismatch) return `${label} tidak sesuai format.`;
            if (field.validity.tooShort) return `${label} terlalu pendek.`;
            if (field.validity.tooLong) return `${label} terlalu panjang.`;
            if (field.validity.rangeUnderflow || field.validity.rangeOverflow) return `${label} di luar batas nilai yang diizinkan.`;
            return `${label} tidak valid.`;
        };

        const setFieldFeedback = (field) => {
            if (!(field instanceof HTMLElement)) return true;
            if (field.matches('[type="hidden"], [disabled]')) return true;
            if (!field.matches('input, select, textarea')) return true;

            const parent = field.parentElement;
            if (!parent) return true;
            const old = parent.querySelector('.invalid-feedback.auto-feedback');
            if (old) old.remove();

            if (field.checkValidity()) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                return true;
            }

            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            const fb = document.createElement('div');
            fb.className = 'invalid-feedback auto-feedback';
            fb.textContent = getAutoMessage(field);
            parent.appendChild(fb);
            return false;
        };

        const enhanceFormSubmitButton = (form) => {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitBtn || submitBtn.dataset.loadingState === '1') return;
            submitBtn.dataset.loadingState = '1';
            const original = submitBtn.innerHTML;
            submitBtn.classList.add('btn-submit-loading');
            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Memproses...';
            form.dataset.submitOriginalHtml = original;
        };

        document.addEventListener('input', (event) => {
            const field = event.target;
            if (!(field instanceof HTMLElement)) return;
            if (!field.matches('input, select, textarea')) return;
            if (field.classList.contains('is-invalid')) setFieldFeedback(field);
        });
        document.addEventListener('change', (event) => {
            const field = event.target;
            if (!(field instanceof HTMLElement)) return;
            if (!field.matches('input, select, textarea')) return;
            if (field.classList.contains('is-invalid')) setFieldFeedback(field);
        });

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (form.method.toUpperCase() === 'GET') return;

            const fields = Array.from(form.querySelectorAll('input, select, textarea'));
            let valid = true;
            fields.forEach((field) => {
                valid = setFieldFeedback(field) && valid;
            });

            if (!valid) {
                event.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid instanceof HTMLElement) firstInvalid.focus();
                return;
            }

            beginLoading();
            enhanceFormSubmitButton(form);
        });

        const quickFab = document.getElementById('quickFab');
        const quickPanel = document.getElementById('quickPanel');
        quickFab?.addEventListener('click', (e) => {
            e.stopPropagation();
            quickPanel?.classList.toggle('show');
        });
        document.addEventListener('click', (e) => {
            if (!quickPanel || !quickFab) return;
            const target = e.target;
            if (!(target instanceof Element)) return;
            if (quickPanel.contains(target) || quickFab.contains(target)) return;
            quickPanel.classList.remove('show');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') quickPanel?.classList.remove('show');
        });
    })();
</script>
</body>
</html>
