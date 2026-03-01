@extends('layouts.app')
@section('content')
<h3 class="section-title">Detail Pesanan</h3>
<div class="card-pro mb-3">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6"><strong>Invoice:</strong> {{ $order->invoice_no }}</div>
            <div class="col-md-6"><strong>Status:</strong> <span class="chip">{{ strtoupper($order->status) }}</span></div>
            <div class="col-md-4"><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
            <div class="col-md-4"><strong>Diskon:</strong> Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</div>
            <div class="col-md-4"><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            @if($order->promotion)
                <div class="col-md-6"><strong>Promo:</strong> {{ $order->promotion->code }}</div>
            @endif
            @if($order->payment)
                <div class="col-md-6"><strong>Pembayaran:</strong> {{ $order->payment->transaction_status }}</div>
            @endif
        </div>
    </div>
</div>

@php
    $paymentStatus = strtolower((string) ($order->payment->transaction_status ?? ''));
    $isPendingPayment = $order->status === 'pending' && in_array($paymentStatus, ['pending', '', 'settlement_pending'], true);
    $isPaidState = in_array($order->status, ['paid', 'processing', 'shipped', 'done'], true);
    $snapToken = $order->payment->provider_ref ?? null;
    $midtransClientKey = config('services.midtrans.client_key');
    $midtransSnapUrl = config('services.midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';

    $statusInfo = match ($order->status) {
        'pending' => ['icon' => 'bi-hourglass-split', 'title' => 'Menunggu Pembayaran', 'desc' => 'Pesanan belum dibayar. Selesaikan pembayaran agar pesanan segera diproses.'],
        'paid' => ['icon' => 'bi-check-circle', 'title' => 'Pembayaran Berhasil', 'desc' => 'Pembayaran sudah terverifikasi. Pesanan Anda sedang diproses untuk pengiriman.'],
        'processing' => ['icon' => 'bi-box-seam', 'title' => 'Pesanan Diproses', 'desc' => 'Tim kami sedang menyiapkan pesanan untuk dikirim.'],
        'shipped' => ['icon' => 'bi-truck', 'title' => 'Sedang Dikirim', 'desc' => 'Pesanan sudah dikirim dan sedang dalam perjalanan.'],
        'done' => ['icon' => 'bi-patch-check', 'title' => 'Pesanan Selesai', 'desc' => 'Pesanan sudah selesai diterima.'],
        'cancelled' => ['icon' => 'bi-x-circle', 'title' => 'Pesanan Dibatalkan', 'desc' => 'Pesanan dibatalkan atau pembayaran gagal.'],
        default => ['icon' => 'bi-info-circle', 'title' => 'Status Pesanan', 'desc' => 'Pantau status pesanan Anda di halaman ini.'],
    };
@endphp

<div class="card-pro mb-3">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="fw-semibold mb-1"><i class="bi {{ $statusInfo['icon'] }} me-1"></i>{{ $statusInfo['title'] }}</div>
            <small class="muted">{{ $statusInfo['desc'] }}</small>
        </div>
        @if($isPendingPayment)
            <button type="button" id="syncPaymentBtn" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran
            </button>
        @endif
    </div>
</div>

@if($isPendingPayment)
    <div class="card-pro mb-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="fw-semibold mb-1">Pembayaran Menunggu</div>
                <small class="muted">Selesaikan pembayaran untuk memproses pesanan Anda.</small>
            </div>
            @if(!empty($snapToken) && !empty($midtransClientKey))
                <button type="button" id="payNowBtn" class="btn btn-primary btn-pill px-4">
                    <i class="bi bi-credit-card me-1"></i>Lakukan Pembayaran
                </button>
            @else
                <span class="chip" style="background:#fff7ed;border-color:#fdba74;color:#9a3412;">Token pembayaran belum tersedia</span>
            @endif
        </div>
    </div>
@endif

@if($isPaidState)
    <div class="card-pro mb-3">
        <div class="card-body">
            <i class="bi bi-truck me-1"></i>
            Pembayaran sudah diterima. Status pesanan Anda saat ini:
            <strong>{{ strtoupper($order->status) }}</strong>.
            @if($order->shipment && $order->shipment->tracking_no)
                Nomor resi: <strong>{{ $order->shipment->tracking_no }}</strong>.
            @endif
        </div>
    </div>
@endif

<div class="table-pro">
    <table class="table align-middle">
        <thead><tr><th>Gambar</th><th>Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td><img src="{{ $item->product->image_url }}" class="product-img" alt="{{ $item->product->name }}"></td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan</a>
</div>

@if($isPendingPayment && !empty($snapToken) && !empty($midtransClientKey))
    <script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const payBtn = document.getElementById('payNowBtn');
            const syncBtn = document.getElementById('syncPaymentBtn');

            const syncPayment = async () => {
                if (!syncBtn) return;
                syncBtn.setAttribute('disabled', 'disabled');
                syncBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek...';
                try {
                    const response = await fetch(@json(route('orders.sync-payment', $order->invoice_no)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'Accept': 'application/json',
                        },
                    });
                    if (response.ok) {
                        window.location.reload();
                        return;
                    }
                } catch (e) {
                    // ignore and restore button state below
                }
                syncBtn.removeAttribute('disabled');
                syncBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran';
            };

            syncBtn?.addEventListener('click', syncPayment);

            if (payBtn && typeof window.snap !== 'undefined') {
                payBtn.addEventListener('click', () => {
                    window.snap.pay(@json($snapToken), {
                        onSuccess: syncPayment,
                        onPending: syncPayment,
                        onError: syncPayment,
                        onClose: () => {}
                    });
                });
            }

            if (syncBtn) {
                setInterval(syncPayment, 15000);
            }
        });
    </script>
@elseif($isPendingPayment)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const syncBtn = document.getElementById('syncPaymentBtn');
            if (!syncBtn) return;

            const syncPayment = async () => {
                syncBtn.setAttribute('disabled', 'disabled');
                syncBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek...';
                try {
                    const response = await fetch(@json(route('orders.sync-payment', $order->invoice_no)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'Accept': 'application/json',
                        },
                    });
                    if (response.ok) {
                        window.location.reload();
                        return;
                    }
                } catch (e) {
                    // ignore and restore button state below
                }
                syncBtn.removeAttribute('disabled');
                syncBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran';
            };

            syncBtn.addEventListener('click', syncPayment);
            setInterval(syncPayment, 15000);
        });
    </script>
@endif
@endsection
