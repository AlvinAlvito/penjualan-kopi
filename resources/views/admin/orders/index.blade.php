@extends('layouts.app')
@section('content')
<h3>Manajemen Pesanan</h3>
<table class="table table-bordered">
    <thead><tr><th>Invoice</th><th>User</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr></thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ number_format($order->total,0,',','.') }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->payment->transaction_status ?? '-' }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection
