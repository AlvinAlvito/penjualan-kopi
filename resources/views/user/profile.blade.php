@extends('layouts.app')
@section('content')
<h3>Profil</h3>
<form method="post" action="{{ route('profile.update') }}" class="row g-3" style="max-width:700px;">
    @csrf @method('PATCH')
    <div class="col-md-6"><label>Nama</label><input name="name" class="form-control" value="{{ $user->name }}"></div>
    <div class="col-md-6"><label>No HP</label><input name="phone" class="form-control" value="{{ $user->phone }}"></div>
    <div class="col-12"><label>Alamat Default</label><textarea name="default_address" class="form-control">{{ $user->default_address }}</textarea></div>
    <div class="col-12"><button class="btn btn-dark">Simpan</button></div>
</form>
@endsection
