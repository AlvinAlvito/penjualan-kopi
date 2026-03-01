@extends('layouts.app')
@section('content')
<h3 class="section-title">Riwayat Pesanan</h3>
<div class="table-pro">
    <table class="table table-hover align-middle">
        <thead><tr><th>Invoice</th><th>Status</th><th>Total</th><th>Tanggal</th><th></th></tr></thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->invoice_no }}</strong></td>
                    <td><span class="chip">{{ strtoupper($order->status) }}</span></td>
                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td><a href="{{ route('orders.show', $order->invoice_no) }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center muted py-4">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
