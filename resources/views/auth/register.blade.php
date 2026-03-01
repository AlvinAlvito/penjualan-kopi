@extends('layouts.app')
@section('content')
<h3>Daftar Akun</h3>
<form method="post" action="{{ route('register.store') }}" class="row g-3" style="max-width:520px;">
    @csrf
    <div><input type="text" name="name" class="form-control" placeholder="Nama" required></div>
    <div><input type="email" name="email" class="form-control" placeholder="Email" required></div>
    <div><input type="text" name="phone" class="form-control" placeholder="No HP"></div>
    <div><input type="password" name="password" class="form-control" placeholder="Password" required></div>
    <div><input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required></div>
    <div><button class="btn btn-dark">Daftar</button></div>
</form>
@endsection
