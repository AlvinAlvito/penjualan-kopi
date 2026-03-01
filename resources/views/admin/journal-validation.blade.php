@extends('layouts.app')
@section('content')
<style>
    .static-data {
        color: #123a73;
        font-weight: 700;
    }
</style>

<h3 class="section-title mb-2">Validasi Jurnal (Tabel 1-10)</h3>
<p class="muted mb-3">Halaman ini menampilkan pembuktian input, proses, dan output sistem terhadap hasil penelitian jurnal Anda.</p>

<div class="card-pro mb-3">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Input Penelitian</h5>
        <div class="table-pro">
            <table class="table align-middle">
                <thead><tr><th>Produk</th><th>Deskripsi</th><th>Metode Proses</th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td class="small muted">{{ $product->description }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $product->processing_method)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-pro mb-3">
    <div class="card-body">
        <h5 class="fw-bold mb-2">Tabel 1 - Hasil Text Preprocessing</h5>
        <div class="table-pro">
            <table class="table align-middle">
                <thead><tr><th>Varian</th><th>Hasil Text Preprocessing</th></tr></thead>
                <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td><strong>{{ $doc['product']->name }}</strong></td>
                        <td class="small">{{ $doc['term_string'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@php
    $tables = [
        ['num' => 2, 'title' => 'Hasil TF Numerator (S1)', 'scenario' => $scenarioOne, 'mode' => 'tf_numerator'],
        ['num' => 3, 'title' => 'Hasil Pembobotan TF (S1)', 'scenario' => $scenarioOne, 'mode' => 'tf'],
        ['num' => 5, 'title' => 'Hasil Pembobotan TF-IDF (S1)', 'scenario' => $scenarioOne, 'mode' => 'tf_idf'],
        ['num' => 6, 'title' => 'Hasil Pembobotan TF-IDF (S2)', 'scenario' => $scenarioTwo, 'mode' => 'tf_idf'],
    ];
@endphp

@foreach($tables as $meta)
    <div class="card-pro mb-3">
        <div class="card-body">
            <h5 class="fw-bold mb-2">Tabel {{ $meta['num'] }} - {{ $meta['title'] }}</h5>
            <p class="muted mb-2">Query: <strong>{{ $meta['scenario']['query'] }}</strong></p>
            <div class="table-pro">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Varian</th>
                            @foreach($meta['scenario']['terms'] as $idx => $term)
                                <th>Q{{ $idx + 1 }} ({{ $term }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($meta['scenario']['rows'] as $row)
                        <tr>
                            <td><strong>{{ $row['product_name'] }}</strong></td>
                            @foreach($meta['scenario']['terms'] as $term)
                                <td>
                                    @if($meta['mode'] === 'tf_numerator')
                                        {{ $row['tf_numerator'][$term] }}
                                    @elseif($meta['mode'] === 'tf')
                                        {{ number_format($row['tf'][$term], 4) }}
                                    @else
                                        {{ number_format($row['tf_idf'][$term], 4) }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach

<div class="card-pro mb-3">
    <div class="card-body">
        <h5 class="fw-bold mb-2">Tabel 4 - Hasil Pembobotan IDF</h5>
        <div class="table-pro">
            <table class="table align-middle">
                <thead><tr><th>Term</th><th>DF (S1)</th><th>IDF (S1)</th></tr></thead>
                <tbody>
                @foreach($scenarioOne['terms'] as $term)
                    <tr>
                        <td>{{ $term }}</td>
                        <td>{{ $scenarioOne['df'][$term] }}</td>
                        <td>{{ number_format($scenarioOne['idf'][$term], 4) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-2">Tabel 7 - Hasil Cosine Similarity (S1)</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th>Varian</th><th>Similarity Sistem</th><th class="static-data">Target Jurnal</th></tr></thead>
                        <tbody>
                        @foreach($scenarioOne['rows'] as $row)
                            @php
                                $target = $journalTargets['s1'][$row['product_name']] ?? null;
                                $isMatch = $target !== null && abs($row['similarity'] - $target) <= 0.0001;
                            @endphp
                            <tr>
                                <td>{{ $row['product_name'] }}</td>
                                <td>{{ number_format($row['similarity'], 4) }}</td>
                                <td>
                                    @if($target !== null)
                                        <span class="static-data">{{ number_format($target, 4) }}</span>
                                        <span class="chip ms-1" style="{{ $isMatch ? '' : 'background:#fff7ed;border-color:#fdba74;color:#9a3412;' }}">
                                            {{ $isMatch ? 'Sesuai' : 'Cek' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                <h5 class="fw-bold mb-2">Tabel 8 - Hasil Cosine Similarity (S2)</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th>Varian</th><th>Similarity Sistem</th><th class="static-data">Target Jurnal</th></tr></thead>
                        <tbody>
                        @foreach($scenarioTwo['rows'] as $row)
                            @php
                                $target = $journalTargets['s2'][$row['product_name']] ?? null;
                                $isMatch = $target !== null && abs($row['similarity'] - $target) <= 0.0001;
                            @endphp
                            <tr>
                                <td>{{ $row['product_name'] }}</td>
                                <td>{{ number_format($row['similarity'], 4) }}</td>
                                <td>
                                    @if($target !== null)
                                        <span class="static-data">{{ number_format($target, 4) }}</span>
                                        <span class="chip ms-1" style="{{ $isMatch ? '' : 'background:#fff7ed;border-color:#fdba74;color:#9a3412;' }}">
                                            {{ $isMatch ? 'Sesuai' : 'Cek' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card-pro h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-2">Tabel 9 - Ranking Rekomendasi (S1)</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th class="static-data">Rank</th><th class="static-data">Target Jurnal</th><th class="static-data">Skor Jurnal</th><th>Output Web</th><th>Skor Web</th></tr></thead>
                        <tbody>
                        @foreach($journalRankS1 as $i => $target)
                            @php
                                $web = $webRankS1[$i] ?? null;
                                $match = $web && $web['product_name'] === $target['product_name'] && abs(((float) $web['final_score']) - $target['final_score']) <= 0.0001;
                            @endphp
                            <tr>
                                <td class="static-data">{{ $target['rank'] }}</td>
                                <td class="static-data">{{ $target['product_name'] }}</td>
                                <td class="static-data">{{ number_format($target['final_score'], 4) }}</td>
                                <td>{{ $web['product_name'] ?? '-' }}</td>
                                <td>
                                    {{ isset($web['final_score']) ? number_format((float) $web['final_score'], 4) : '-' }}
                                    <span class="chip ms-1" style="{{ $match ? '' : 'background:#fff7ed;border-color:#fdba74;color:#9a3412;' }}">
                                        {{ $match ? 'Sesuai' : 'Cek' }}
                                    </span>
                                </td>
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
                <h5 class="fw-bold mb-2">Tabel 10 - Ranking Rekomendasi (S2)</h5>
                <div class="table-pro">
                    <table class="table align-middle">
                        <thead><tr><th class="static-data">Rank</th><th class="static-data">Target Jurnal</th><th class="static-data">Skor Jurnal</th><th>Output Web</th><th>Skor Web</th></tr></thead>
                        <tbody>
                        @foreach($journalRankS2 as $i => $target)
                            @php
                                $web = $webRankS2[$i] ?? null;
                                $match = $web && $web['product_name'] === $target['product_name'] && abs(((float) $web['final_score']) - $target['final_score']) <= 0.0001;
                            @endphp
                            <tr>
                                <td class="static-data">{{ $target['rank'] }}</td>
                                <td class="static-data">{{ $target['product_name'] }}</td>
                                <td class="static-data">{{ number_format($target['final_score'], 4) }}</td>
                                <td>{{ $web['product_name'] ?? '-' }}</td>
                                <td>
                                    {{ isset($web['final_score']) ? number_format((float) $web['final_score'], 4) : '-' }}
                                    <span class="chip ms-1" style="{{ $match ? '' : 'background:#fff7ed;border-color:#fdba74;color:#9a3412;' }}">
                                        {{ $match ? 'Sesuai' : 'Cek' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-pro">
    <div class="card-body">
        <h5 class="fw-bold mb-2">Ringkasan Evaluasi Jurnal</h5>
        <div class="row g-2">
            <div class="col-md-4"><div class="stat-card">Precision<br><strong class="static-data">{{ number_format($journalMetric['precision'], 2) }}</strong></div></div>
            <div class="col-md-4"><div class="stat-card">Recall<br><strong class="static-data">{{ number_format($journalMetric['recall'], 2) }}</strong></div></div>
            <div class="col-md-4"><div class="stat-card">F1-Score<br><strong class="static-data">{{ number_format($journalMetric['f1_score'], 2) }}</strong></div></div>
        </div>
    </div>
</div>
@endsection
