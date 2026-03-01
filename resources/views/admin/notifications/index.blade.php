@extends('layouts.app')
@section('content')
<h3>Kirim Notifikasi</h3>
<form method="post" action="{{ route('admin.notifications.store') }}" class="row g-2 mb-4">
    @csrf
    <div class="col-md-3"><input name="title" class="form-control" placeholder="Judul" required></div>
    <div class="col-md-4"><input name="message" class="form-control" placeholder="Pesan" required></div>
    <div class="col-md-2"><input name="type" class="form-control" placeholder="Tipe"></div>
    <div class="col-md-3">
        <select name="target" class="form-select" id="targetSelect">
            <option value="all">Semua User</option>
            <option value="single">User Tertentu</option>
        </select>
    </div>
    <div class="col-md-4">
        <select name="user_id" class="form-select">
            <option value="">Pilih user</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->email }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8"><button class="btn btn-dark">Kirim</button></div>
</form>

<table class="table table-bordered table-sm">
    <thead><tr><th>Waktu</th><th>User</th><th>Judul</th><th>Pesan</th><th>Read At</th></tr></thead>
    <tbody>
        @foreach($notifications as $n)
            <tr>
                <td>{{ $n->created_at }}</td>
                <td>{{ $n->user->name ?? '-' }}</td>
                <td>{{ $n->title }}</td>
                <td>{{ $n->message }}</td>
                <td>{{ $n->read_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $notifications->links() }}
@endsection
