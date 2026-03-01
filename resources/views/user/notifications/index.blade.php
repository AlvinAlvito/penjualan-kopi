@extends('layouts.app')
@section('content')
<h3 class="section-title">Notifikasi Saya</h3>
<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Waktu</th><th>Judul</th><th>Pesan</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($notifications as $notification)
                <tr>
                    <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                    <td><strong>{{ $notification->title }}</strong></td>
                    <td class="muted">{{ $notification->message }}</td>
                    <td>
                        @if($notification->read_at)
                            <span class="chip">DIBACA</span>
                        @else
                            <span class="chip" style="background:#fef9c3;border-color:#fde68a;color:#854d0e">BARU</span>
                        @endif
                    </td>
                    <td>
                        @if(!$notification->read_at)
                            <form method="post" action="{{ route('notifications.read', $notification) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-dark"><i class="bi bi-check2-circle me-1"></i>Tandai Dibaca</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center muted py-4">Belum ada notifikasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
