@extends('layouts.app')
@section('content')
<h3>Analitik Rekomendasi</h3>
<table class="table table-bordered mb-4">
    <thead><tr><th>Query</th><th>TP</th><th>FP</th><th>FN</th><th>Precision</th><th>Recall</th><th>F1</th></tr></thead>
    <tbody>
    @foreach($evaluation as $row)
        <tr>
            <td>{{ $row['query'] }}</td>
            <td>{{ $row['tp'] }}</td>
            <td>{{ $row['fp'] }}</td>
            <td>{{ $row['fn'] }}</td>
            <td>{{ $row['precision'] }}</td>
            <td>{{ $row['recall'] }}</td>
            <td>{{ $row['f1_score'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h5>Top Query</h5>
<table class="table table-sm table-bordered mb-4">
    <thead><tr><th>Query</th><th>Jumlah</th></tr></thead>
    <tbody>@foreach($topQueries as $q)<tr><td>{{ $q->query_text }}</td><td>{{ $q->total }}</td></tr>@endforeach</tbody>
</table>

<h5>Log Terbaru</h5>
<table class="table table-sm table-bordered">
    <thead><tr><th>Waktu</th><th>User ID</th><th>Query</th><th>Hasil</th></tr></thead>
    <tbody>@foreach($recentLogs as $log)<tr><td>{{ $log->created_at }}</td><td>{{ $log->user_id }}</td><td>{{ $log->query_text }}</td><td><small>{{ json_encode($log->result_json) }}</small></td></tr>@endforeach</tbody>
</table>
@endsection
