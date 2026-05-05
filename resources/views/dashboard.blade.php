@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid px-2 pb-3">
    <!-- Baris 1: Sinergi Visual & Data -->
    <div class="row g-2 mb-2">
        <!-- Risiko Prioritas -->
        <div class="col-lg-5">
            <div class="card id-card h-100 shadow-none border">
                <div class="id-header border-bottom">
                    <span class="id-title"><i class="fas fa-list-ol me-2 text-primary"></i>Risiko Prioritas</span>
                    <span class="id-badge">Top 10 Aktif</span>
                </div>
                <div class="card-body p-0">
                    <div class="id-table-scroll" style="height: 250px; overflow-x: hidden;">
                        <table class="table id-table-v6 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%;">No</th>
                                    <th class="ps-2" style="width: 50%;">Kegiatan</th>
                                    <th class="text-center" style="width: 25%;">Tingkat</th>
                                    <th class="text-center" style="width: 15%;">Tren</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criticalRisks as $rk)
                                <tr>
                                    <td class="text-center py-2 text-xxs font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="ps-2 py-2">
                                        <div class="id-risk-text-v6">{{ $rk->kegiatan }}</div>
                                        <div class="id-risk-code-v6">{{ $rk->kode_risiko }}</div>
                                    </td>
                                    @php
                                        $rank = $rk->analisis->peringkat_risiko;
                                        $bg = $rank=='SANGAT TINGGI'?'#ef4444':($rank=='TINGGI'?'#f59e0b':($rank=='SEDANG'?'#eab308':'#10b981'));
                                    @endphp
                                    <td class="text-center py-2 text-white font-weight-bolder text-xxs text-uppercase" style="background-color: {{ $bg }};">
                                        {{ $rank }}
                                    </td>
                                    <td class="text-center py-2"><div class="trend-v6"><i class="fas fa-arrow-up text-danger animate-pulse text-xxs"></i></div></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card id-card h-100 shadow-none border">
                <div class="id-header border-bottom"><span class="id-title">Pengendalian</span></div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                    <div class="position-relative" style="width: 120px; height: 120px;">
                        <canvas id="controlChart"></canvas>
                        <div class="donut-label-fix">
                            <div class="text-dark font-weight-black" style="font-size: 1.2rem; line-height:1;">{{ $controlPerformance->sum('total') }}</div>
                            <div class="text-muted text-xxs font-weight-bold">UNIT</div>
                        </div>
                    </div>
                    <div class="w-100 mt-3 pt-2 border-top">
                        @foreach($controlPerformance as $perf)
                            @php $c = strtolower($perf->efektifitas_pengendalian) == 'efektif' ? '#10b981' : (strtolower($perf->efektifitas_pengendalian) == 'kurang efektif' ? '#f59e0b' : '#ef4444'); @endphp
                            <div class="d-flex justify-content-between text-xxs mb-1">
                                <span class="font-weight-bold" style="color: {{ $c }}">● {{ $perf->efektifitas_pengendalian }}</span>
                                <span class="font-weight-bolder text-dark">{{ $perf->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card id-card h-100 shadow-none border">
                <div class="id-header border-bottom"><span class="id-title">Penanganan</span></div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                    <div class="position-relative" style="width: 120px; height: 120px;">
                        <canvas id="issuesChart"></canvas>
                        <div class="donut-label-fix">
                            <div class="text-dark font-weight-black" style="font-size: 1.2rem; line-height:1;">{{ $evaluatedIssues + $openIssues }}</div>
                            <div class="text-muted text-xxs font-weight-bold">RISIKO</div>
                        </div>
                    </div>
                    <div class="w-100 mt-3 pt-2 border-top d-flex gap-3 justify-content-center">
                        <div class="text-center px-3 border-end">
                            <div class="text-xxs text-muted font-weight-bold">SELESAI</div>
                            <div class="text-sm font-weight-black" style="color: #007774;">{{ $evaluatedIssues }}</div>
                        </div>
                        <div class="text-center px-3">
                            <div class="text-xxs text-muted font-weight-bold">BELUM</div>
                            <div class="text-sm font-weight-black" style="color: #f59e0b;">{{ $openIssues }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Heatmap DIKECILKAN Mengikuti Profile -->
    <div class="row g-2">
        <div class="col-lg-7">
            <div class="card id-card h-100 shadow-none border">
                <div class="id-header border-bottom">
                    <span class="id-title">Profil Risiko per Kategori</span>
                </div>
                <div class="card-body p-3">
                    <div style="height: 280px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card id-card h-100 shadow-none border">
                <div class="id-header border-bottom">
                    <span class="id-title">Matriks Paparan Risiko</span>
                </div>
                <div class="card-body p-0 d-flex flex-column bg-white align-items-center justify-content-center">
                    <!-- Heatmap Grid diperkecil drastis agar tidak melebihi tinggi Profile -->
                    <div class="heatmap-compact-box mt-2 mb-2">
                        <div class="hm-y-label">KEMUNGKINAN</div>
                        <div class="hm-grid-compact-v8">
                            @for($d=5; $d>=1; $d--)
                                <div class="hm-pt-y">{{ $d }}</div>
                                @for($p=1; $p<=5; $p++)
                                    @php 
                                        $count = $heatmap[$p][$d] ?? 0;
                                        $score = $p*$d;
                                        $col = $score>=20?'#ef4444':($score>=13?'#f59e0b':($score>=5?'#eab308':'#10b981'));
                                    @endphp
                                    <div class="hm-cell-v8" style="background-color: {{ $col }};">
                                        @if($count > 0) <div class="hm-bubble-v8">{{ $count }}</div> @else <span class="hm-dash-v8">-</span> @endif
                                    </div>
                                @endfor
                            @endfor
                            <div class="empty"></div>
                            @for($p=1; $p<=5; $p++) <div class="hm-pt-x">{{ $p }}</div> @endfor
                        </div>
                        <div class="hm-x-label text-center mt-1">DAMPAK</div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 py-2 border-top w-100 bg-light-soft mt-auto">
                        <div class="l-item-full"><span class="l-dot-v8 bg-danger"></span> Sangat Tinggi</div>
                        <div class="l-item-full"><span class="l-dot-v8" style="background: #f59e0b"></span> Tinggi</div>
                        <div class="l-item-full"><span class="l-dot-v8" style="background: #eab308"></span> Sedang</div>
                        <div class="l-item-full"><span class="l-dot-v8" style="background: #10b981"></span> Rendah</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-soft { background: #fafbfc; }
    .id-card { border-radius: 8px; background: white; border: 1px solid #ebedf0 !important; }
    .id-header { padding: 8px 15px; background: #fafbfc; }
    .id-title { font-weight: 800; font-size: 0.7rem; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
    .id-badge { font-size: 0.55rem; color: #007774; background: #f0fdf4; padding: 1px 8px; border-radius: 4px; font-weight: 800; }
    .donut-label-fix { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; pointer-events: none; }
    
    .id-table-v6 th { font-size: 0.6rem; color: #94a3b8; text-transform: uppercase; padding: 10px 12px; border-bottom: 1px solid #f1f5f9; background: #fafbfc; font-weight: 800; }
    .id-table-v6 td { vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .id-risk-text-v6 { font-size: 0.68rem; font-weight: 800; color: #1e293b; white-space: normal; line-height: 1.1; word-break: break-word; }
    .id-risk-code-v6 { font-size: 0.55rem; color: #94a3b8; margin-top: 1px; }
    .trend-v6 { display: flex; align-items: center; justify-content: center; transform: scale(1.1); }
    .animate-pulse { animation: pulse 2s infinite; }
    
    .heatmap-compact-box { display: flex; flex-direction: column; align-items: center; width: 100%; position: relative; }
    .hm-y-label { position: absolute; left: 0px; top: 90px; transform: rotate(180deg); writing-mode: vertical-rl; font-size: 0.52rem; font-weight: 900; color: #94a3b8; letter-spacing: 1px; }
    .hm-grid-compact-v8 { display: grid; grid-template-columns: 20px repeat(5, 1fr); gap: 4px; width: 100%; max-width: 240px; }
    .hm-cell-v8 { aspect-ratio: 1 / 1; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
    .hm-bubble-v8 { background: white; color: #1e293b; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.6rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .hm-dash-v8 { color: rgba(255,255,255,0.25); font-weight: 800; font-size: 0.7rem; }
    .hm-pt-y { font-weight: 800; color: #cbd5e1; align-self: center; text-align: right; padding-right: 6px; font-size: 0.7rem; }
    .hm-pt-x { text-align: center; font-weight: 800; color: #cbd5e1; font-size: 0.7rem; padding-top: 4px; }
    .hm-x-label { font-size: 0.52rem; font-weight: 900; color: #94a3b8; letter-spacing: 1px; }

    .l-item-full { display: flex; align-items: center; gap: 4px; font-size: 0.6rem; font-weight: 800; color: #475569; }
    .l-dot-v8 { width: 8px; height: 8px; border-radius: 2px; }
</style>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.size = 9;
    Chart.defaults.color = '#94a3b8';

    const donutOpts = { responsive: true, maintainAspectRatio: false, cutout: '85%', plugins: { legend: { display: false } } };

    const controlLabels = @json($controlPerformance->pluck('efektifitas_pengendalian'));
    const controlColors = controlLabels.map(l => {
        const s = l.toLowerCase();
        if(s === 'efektif') return '#10b981';
        if(s === 'kurang efektif') return '#f59e0b';
        return '#ef4444';
    });

    new Chart(document.getElementById('controlChart').getContext('2d'), {
        type: 'doughnut',
        data: { labels: controlLabels, datasets: [{ data: @json($controlPerformance->pluck('total')), backgroundColor: controlColors, borderWidth: 0 }] },
        options: donutOpts
    });

    new Chart(document.getElementById('issuesChart').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Selesai', 'Belum'], datasets: [{ data: [{{ $evaluatedIssues }}, {{ $openIssues }}], backgroundColor: ['#007774', '#f59e0b'], borderWidth: 0 }] },
        options: donutOpts
    });

    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($categoryData->pluck('name')),
            datasets: [
                { label: 'Sangat Tinggi', data: @json($categoryData->pluck('extreme')), backgroundColor: '#ef4444' },
                { label: 'Tinggi', data: @json($categoryData->pluck('high')), backgroundColor: '#f59e0b' },
                { label: 'Sedang', data: @json($categoryData->pluck('medium')), backgroundColor: '#eab308' },
                { label: 'Rendah', data: @json($categoryData->pluck('low')), backgroundColor: '#10b981' }
            ]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
            scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, grid: { display: false }, ticks: { font: { weight: 'bold' }, color: '#334155' } } },
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } } }
        }
    });
</script>
@endpush
@endsection
