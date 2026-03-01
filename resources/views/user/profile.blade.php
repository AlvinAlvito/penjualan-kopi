@extends('layouts.app')
@section('content')
<h3 class="section-title">Profil Saya</h3>
<div class="card-pro">
    <div class="card-body">
        <form method="post" action="{{ route('profile.update') }}" class="row g-3">
            @csrf @method('PATCH')
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No HP</label>
                <input name="phone" class="form-control" value="{{ $user->phone }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat Default</label>
                <textarea name="default_address" class="form-control">{{ $user->default_address }}</textarea>
            </div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end">
                <button class="btn btn-primary btn-pill px-4"><i class="bi bi-save me-1"></i>Simpan Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection
