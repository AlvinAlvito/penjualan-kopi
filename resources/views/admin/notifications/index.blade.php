@extends('layouts.app')
@section('content')
<h3 class="section-title">Notifikasi Massal</h3>

<div class="card-pro mb-3">
    <div class="card-body">
        <form method="post" action="{{ route('admin.notifications.store') }}" class="row g-2">
            @csrf
            <div class="col-md-3"><label class="form-label">Judul</label><input name="title" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Pesan</label><input name="message" class="form-control" required></div>
            <div class="col-md-2"><label class="form-label">Tipe</label><input name="type" class="form-control" placeholder="promo/order"></div>
            <div class="col-md-3"><label class="form-label">Target</label><select name="target" class="form-select"><option value="all">Semua User</option><option value="single">User Tertentu</option></select></div>
            <div class="col-md-6">
                <label class="form-label">User (opsional)</label>
                <select name="user_id" class="form-select">
                    <option value="">Pilih user</option>
                    @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }} - {{ $u->email }}</option>@endforeach
                </select>
            </div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end"><button class="btn btn-primary btn-pill">Kirim Notifikasi</button></div>
        </form>
    </div>
</div>

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Waktu</th><th>User</th><th>Judul</th><th>Pesan</th><th>Read At</th></tr></thead>
        <tbody>
            @forelse($notifications as $n)
                <tr>
                    <td>{{ $n->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $n->user->name ?? '-' }}</td>
                    <td>{{ $n->title }}</td>
                    <td class="muted">{{ $n->message }}</td>
                    <td>{{ $n->read_at ? $n->read_at->format('d M Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center muted py-4">Belum ada notifikasi terkirim.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
