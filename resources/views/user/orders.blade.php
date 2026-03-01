@extends('layouts.app')
@section('content')
<h3>Riwayat Pesanan</h3>
<table class="table table-bordered">
    <thead><tr><th>Invoice</th><th>Status</th><th>Total</th><th>Tanggal</th><th></th></tr></thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ $order->status }}</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('orders.show', $order->invoice_no) }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection
