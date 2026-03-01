@extends('layouts.app')
@section('content')
<h3 class="section-title">Manajemen Pesanan</h3>
<div class="table-pro">
    <table class="table align-middle table-hover">
        <thead><tr><th>Invoice</th><th>User</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr></thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->invoice_no }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                    <td><span class="chip">{{ strtoupper($order->status) }}</span></td>
                    <td>{{ $order->payment->transaction_status ?? '-' }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center muted py-4">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
