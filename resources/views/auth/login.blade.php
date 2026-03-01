@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hero-panel p-4 p-md-5 mb-4">
            <h2 class="brand-serif mb-2">Masuk ke Akun J2 Kopi</h2>
            <p class="mb-0 text-white-50">Kelola pesanan, wishlist, dan rekomendasi kopi favorit Anda dalam satu dashboard.</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-pro">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Login</h5>
                <form method="post" action="{{ route('login.attempt') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-primary btn-pill">Masuk</button>
                    </div>
                </form>
                <p class="muted mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}" class="link-primary">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
