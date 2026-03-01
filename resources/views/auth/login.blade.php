@extends('layouts.app')
@section('content')
<h3>Login</h3>
<form method="post" action="{{ route('login.attempt') }}" class="row g-3" style="max-width:420px;">
    @csrf
    <div><input type="email" name="email" class="form-control" placeholder="Email" required></div>
    <div><input type="password" name="password" class="form-control" placeholder="Password" required></div>
    <div><button class="btn btn-dark">Masuk</button></div>
</form>
@endsection
