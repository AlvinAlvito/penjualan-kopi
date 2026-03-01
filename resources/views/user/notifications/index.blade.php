@extends('layouts.app')
@section('content')
<h3>Notifikasi Saya</h3>
<table class="table table-bordered">
    <thead><tr><th>Waktu</th><th>Judul</th><th>Pesan</th><th>Status</th><th></th></tr></thead>
    <tbody>
        @foreach($notifications as $notification)
            <tr>
                <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $notification->title }}</td>
                <td>{{ $notification->message }}</td>
                <td>{{ $notification->read_at ? 'Dibaca' : 'Belum dibaca' }}</td>
                <td>
                    @if(!$notification->read_at)
                        <form method="post" action="{{ route('notifications.read', $notification) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-dark">Tandai dibaca</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $notifications->links() }}
@endsection
