@extends('layouts.app')
@section('content')
<h3 class="section-title">Analitik Rekomendasi</h3>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Metrik Evaluasi</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th>Query</th><th>Precision</th><th>Recall</th><th>F1</th></tr></thead>
                        <tbody>
                            @foreach($evaluation as $row)
                                <tr>
                                    <td>{{ $row['query'] }}</td>
                                    <td>{{ $row['precision'] }}</td>
                                    <td>{{ $row['recall'] }}</td>
                                    <td><strong>{{ $row['f1_score'] }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Top Query</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th>Query</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @forelse($topQueries as $q)
                                <tr><td>{{ $q->query_text }}</td><td>{{ $q->total }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center muted">Belum ada data query.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-pro">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Log Rekomendasi Terbaru</h5>
        <div class="table-pro">
            <table class="table align-middle">
                <thead><tr><th>Waktu</th><th>User</th><th>Query</th><th>Hasil</th></tr></thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $log->user_id ?? '-' }}</td>
                            <td>{{ $log->query_text }}</td>
                            <td><small class="muted">{{ json_encode($log->result_json) }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center muted py-4">Belum ada log rekomendasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
