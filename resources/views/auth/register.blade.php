@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hero-panel p-4 p-md-5 mb-4">
            <h2 class="brand-serif mb-2">Buat Akun Baru</h2>
            <p class="mb-0 text-white-50">Daftar sebagai pelanggan untuk menikmati pengalaman belanja kopi yang lebih personal.</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-pro">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Registrasi</h5>
                <form method="post" action="{{ route('register.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-primary btn-pill">Buat Akun</button>
                    </div>
                </form>
                <p class="muted mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="link-primary">Masuk</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
