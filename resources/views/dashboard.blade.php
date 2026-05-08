@extends('layouts.app')

@section('title', 'Risk Intelligence Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Risk Intelligence Dashboard')

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
* { font-family: 'Inter', sans-serif; }

/* == KPI CARDS =========================================================== */
.kpi-grid { 
    display: flex; 
    flex-wrap: nowrap; 
    gap: 12px; 
    margin-bottom: 24px; 
    width: 100%;
}
@media (max-width: 1400px) { .kpi-grid { gap: 6px; } }
@media (max-width: 1200px) { .kpi-grid { flex-wrap: wrap; } .kpi-card { flex: 1 1 calc(25% - 8px); } }
@media (max-width: 768px) { .kpi-card { flex: 1 1 calc(33.3% - 8px); } }
@media (max-width: 480px) { .kpi-card { flex: 1 1 calc(50% - 8px); } }

.kpi-card {
    flex: 1;
    border-radius: 14px;
    padding: 14px 12px;
    position: relative;
    overflow: hidden;
    border: none;
    cursor: default;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 0;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
.kpi-card .kpi-icon {
    font-size: 1.2rem;
    margin-bottom: 6px;
    opacity: 0.9;
}
.kpi-card .kpi-label {
    font-size: 0.55rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    opacity: 0.8;
    margin-bottom: 2px;
}
.kpi-card .kpi-value {
    font-size: 1.6rem;
    font-weight: 900;
    line-height: 1;
}
.kpi-card .kpi-sub {
    font-size: 0.45rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    opacity: 0.7;
    margin-top: 2px;
}

/* ── Card Colors ──────────────────────────────────────────────────────────── */
.kpi-dark  { background: #2b2b3d; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.kpi-red   { background: #c00000; color: #fff; box-shadow: 0 4px 12px rgba(192,0,0,0.2); }
.kpi-orange{ background: #ff9900; color: #fff; box-shadow: 0 4px 12px rgba(255,153,0,0.2); }
.kpi-yellow{ background: #ffeb3b; color: #1a1a2e; box-shadow: 0 4px 12px rgba(255,235,59,0.15); }
.kpi-yellow .kpi-icon, .kpi-yellow .kpi-label, .kpi-yellow .kpi-value, .kpi-yellow .kpi-sub { color: #1a1a2e !important; }
.kpi-green { background: #198754; color: #fff; box-shadow: 0 4px 12px rgba(25,135,84,0.2); }
.kpi-blue  { background: #0d6efd; color: #fff; box-shadow: 0 4px 12px rgba(13,110,253,0.2); }
.kpi-teal  { background: #007774; color: #fff; box-shadow: 0 4px 12px rgba(0,119,116,0.2); }

/* == CHART CARDS ========================================================= */
.dash-card {
    background: #ffffff;
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: box-shadow 0.25s;
}
.dash-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.1); }

.dash-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px 8px;
    border-bottom: 1px solid #f0f2f5;
}
.dash-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #1e293b;
    position: relative;
    padding-left: 12px;
}
.dash-card-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 16px;
    background: #2a9d8f;
    border-radius: 2px;
}
.chart-toggle-group {
    display: flex;
    gap: 4px;
    background: #f8f9fa;
    padding: 3px;
    border-radius: 8px;
}
.chart-toggle-btn {
    padding: 4px 12px;
    border: none;
    background: transparent;
    color: #8392ab;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.chart-toggle-btn.active {
    background: #007774;
    color: #fff;
    box-shadow: 0 2px 8px rgba(0,119,116,0.3);
}

/* == TOP RISK TABLE ====================================================== */
.risk-row { transition: background 0.15s; }
.risk-row:hover { background: #f8f9fa; }
.level-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 0.58rem;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

/* == TIMELINE ============================================================ */
.activity-feed { position: relative; padding-left: 28px; }
.activity-feed::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 4px;
    bottom: 4px;
    width: 2px;
    background: #f0f2f5;
    border-radius: 2px;
}
.activity-item { position: relative; margin-bottom: 18px; }
.activity-item:last-child { margin-bottom: 0; }
.activity-dot {
    position: absolute;
    left: -24px;
    top: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.45rem;
    color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.activity-title { font-size: 0.72rem; font-weight: 700; color: #1a1a2e; }
.activity-meta  { font-size: 0.6rem; color: #94a3b8; font-weight: 600; margin-top: 2px; }

/* == HEATMAP ============================================================= */
.hm-grid {
    display: grid;
    grid-template-columns: 20px repeat(5, 36px);
    gap: 2px;
    padding: 0;
    width: fit-content;
}
.hm-axis-label {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.68rem;
    color: #94a3b8;
}
.hm-cell {
    aspect-ratio: 1;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.hm-cell:hover { transform: scale(1.1); box-shadow: 0 8px 20px rgba(0,0,0,0.2); z-index: 2; position: relative; }
.hm-bubble {
    background: rgba(255,255,255,0.95);
    color: #1a1a2e;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.72rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.hm-dash { color: rgba(255,255,255,0.25); font-size: 1rem; font-weight: 700; }
.hm-y-wrap { display: flex; flex-direction: column; align-items: center; justify-content: center; }
.hm-y-text  { writing-mode: vertical-rl; transform: rotate(180deg); font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; color: #94a3b8; text-transform: uppercase; }
.hm-x-text  { font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; color: #94a3b8; text-transform: uppercase; }
.legend-dot { width: 14px; height: 14px; border-radius: 4px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 pb-5">

    {{-- ═══════════════════════════════════════════ KPI CARDS ══════════════════════════════════════════ --}}
    <div class="kpi-grid mb-4">
        <div class="kpi-card kpi-dark">
            <div class="kpi-icon"><i class="fas fa-database"></i></div>
            <div class="kpi-label">Total Risiko</div>
            <div class="kpi-value">{{ $totalAnalyzed }}</div>
            <div class="kpi-sub">TOTAL ANALYZED</div>
        </div>
        <a href="{{ route('analisis-risiko.index', ['peringkat' => 'SANGAT TINGGI']) }}" class="kpi-card kpi-red text-decoration-none">
            <div class="kpi-icon"><i class="fas fa-radiation-alt"></i></div>
            <div class="kpi-label">Sangat Tinggi</div>
            <div class="kpi-value">{{ $levelStats['SANGAT TINGGI'] }}</div>
            <div class="kpi-sub">EXTREME RISK</div>
        </a>
        <a href="{{ route('analisis-risiko.index', ['peringkat' => 'TINGGI']) }}" class="kpi-card kpi-orange text-decoration-none">
            <div class="kpi-icon"><i class="fas fa-fire-alt"></i></div>
            <div class="kpi-label">Tinggi</div>
            <div class="kpi-value">{{ $levelStats['TINGGI'] }}</div>
            <div class="kpi-sub">HIGH RISK</div>
        </a>
        <a href="{{ route('analisis-risiko.index', ['peringkat' => 'SEDANG']) }}" class="kpi-card kpi-yellow text-decoration-none">
            <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="kpi-label">Sedang</div>
            <div class="kpi-value">{{ $levelStats['SEDANG'] }}</div>
            <div class="kpi-sub">MEDIUM RISK</div>
        </a>
        <a href="{{ route('analisis-risiko.index', ['peringkat' => 'RENDAH']) }}" class="kpi-card kpi-green text-decoration-none">
            <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
            <div class="kpi-label">Rendah</div>
            <div class="kpi-value">{{ $levelStats['RENDAH'] }}</div>
            <div class="kpi-sub">LOW RISK</div>
        </a>
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="kpi-label">Belum Ditangani</div>
            <div class="kpi-value">{{ $pendingRisks }}</div>
            <div class="kpi-sub">RISK OPEN</div>
        </div>
        <div class="kpi-card kpi-teal">
            <div class="kpi-icon"><i class="fas fa-check-double"></i></div>
            <div class="kpi-label">Selesai</div>
            <div class="kpi-value">{{ $completedRisks }}</div>
            <div class="kpi-sub">EVALUATED</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ ROW 2 ══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-3">
        {{-- Pie --}}
        <div class="col-lg-4">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title">Distribusi Kategori Risiko</span>
                </div>
                <div class="px-3 pt-3 pb-3" style="height:300px;">
                    <canvas id="levelPieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Toggle Bar / Line --}}
        <div class="col-lg-8">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title" id="toggleTitle">Risiko per Unit / Divisi</span>
                    <div class="chart-toggle-group">
                        <button class="chart-toggle-btn active" id="btnUnit" onclick="switchView('unit')">Per Unit</button>
                        <button class="chart-toggle-btn" id="btnTrend" onclick="switchView('trend')">Tren Bulanan</button>
                    </div>
                </div>
                <div class="px-3 pb-3" style="height:300px;">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ ROW 3 ══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-3">
        {{-- Top Risk Table --}}
        <div class="col-lg-7">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title">Top Risk Profile</span>
                    <a href="{{ route('daftar-risiko.index') }}" style="font-size:0.65rem; font-weight:700; color:#007774; text-decoration:none;">
                        Lihat Semua &rarr;
                    </a>
                </div>
                <div class="table-responsive" style="max-height: 290px; overflow-y: auto;">
                    <table class="table mb-0 align-items-center table-bordered" style="font-size:0.74rem; border:1px solid #e9ecef;">
                        <thead class="sticky-top bg-white" style="z-index: 10;">
                            <tr style="background:#f8f9fa;">
                                <th class="ps-3 py-2 text-center" style="font-size:0.6rem; font-weight:800; color:#94a3b8; border:1px solid #e9ecef; width:40px;">#</th>
                                <th class="ps-2 py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef;">IDENTIFIKASI RISIKO</th>
                                <th class="text-center py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef; width:130px;">PERINGKAT</th>
                                <th class="text-center py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef; width:100px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criticalRisks as $rk)
                            <tr class="risk-row" style="border-bottom:1px solid #f0f2f5;">
                                <td class="text-center py-3 ps-3 fw-bold" style="border:1px solid #e9ecef; color:#94a3b8; font-size:0.65rem;">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 ps-2" style="border:1px solid #e9ecef; min-width: 250px;">
                                    <div class="fw-bold text-dark" style="font-size:0.74rem; line-height:1.4; white-space: normal;">{{ $rk->kegiatan }}</div>
                                    <div style="font-size:0.58rem; color:#007774; font-weight:800; letter-spacing:0.5px; margin-top:4px;">
                                        <i class="fas fa-tag me-1 opacity-5"></i>{{ $rk->kode_risiko ?? '-' }}
                                    </div>
                                </td>
                                @php
                                    $rank  = strtoupper($rk->analisis->peringkat_risiko ?? '—');
                                    $bg    = $rank=='SANGAT TINGGI'?'#c00000':($rank=='TINGGI'?'#ff9900':($rank=='SEDANG'?'#ffeb3b':'#198754'));
                                    $txtC  = ($rank=='SANGAT TINGGI'||$rank=='TINGGI'||$rank=='RENDAH')?'#fff':'#1a1a2e';
                                @endphp
                                <td class="text-center py-3 fw-bold" style="border:1px solid #e9ecef; background:{{ $bg }}; color:{{ $txtC }}; font-size:0.62rem; letter-spacing:0.5px;">
                                    {{ $rank }}
                                </td>
                                <td class="text-center py-3" style="border:1px solid #e9ecef;">
                                    @if($rk->evaluasi)
                                        <div style="font-size:0.6rem; font-weight:800; color:#198754;"><i class="fas fa-check-circle me-1"></i>SELESAI</div>
                                    @else
                                        <div style="font-size:0.6rem; font-weight:800; color:#ff9900;"><i class="fas fa-history me-1"></i>PROSES</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-title">Risk Matrix Heatmap</span>
                </div>
                <div class="p-4">
                    <div class="d-flex flex-column align-items-center" style="margin-left:-18px;">
                        {{-- Matrix --}}
                        <div class="d-flex align-items-center gap-1 mb-4">
                            <div class="hm-y-text" style="font-size:0.5rem; letter-spacing:1px; writing-mode:vertical-lr; transform:rotate(180deg); color:#94a3b8; font-weight:800;">KEMUNGKINAN</div>
                            <div>
                                <div class="hm-grid">
                                    @for($d=5; $d>=1; $d--)
                                        <div class="hm-axis-label" style="font-size:0.7rem; color:#94a3b8; font-weight:800;">{{ $d }}</div>
                                        @for($p=1; $p<=5; $p++)
                                            @php 
                                                $count = $heatmap[$d][$p] ?? 0;
                                                $score = $p*$d;
                                                $col   = $score>=20?'#c00000':($score>=13?'#ff9900':($score>=5?'#ffeb3b':'#198754'));
                                            @endphp
                                            @php
                                                $rankText = $score>=20?'SANGAT TINGGI':($score>=13?'TINGGI':($score>=5?'SEDANG':'RENDAH'));
                                            @endphp
                                            <a href="{{ route('analisis-risiko.index', ['peringkat' => $rankText]) }}" class="hm-cell text-decoration-none" style="background:{{ $col }}; height:36px; width:36px; border-radius:4px;" title="P={{ $d }} × D={{ $p }} ({{ $rankText }})">
                                                @if($count > 0)
                                                    <div class="hm-bubble" style="width:22px; height:22px; font-size:0.75rem; font-weight:900; background:rgba(255,255,255,0.95); color:#1a1a2e;">{{ $count }}</div>
                                                @else
                                                    <span class="hm-dash" style="font-size:0.6rem; opacity:0.15;">–</span>
                                                @endif
                                            </a>
                                        @endfor
                                    @endfor
                                    <div></div>
                                    @for($p=1; $p<=5; $p++) <div class="hm-axis-label" style="font-size:0.7rem; color:#94a3b8; font-weight:800; padding-top:6px;">{{ $p }}</div> @endfor
                                </div>
                                <div class="text-center mt-2" style="font-size:0.5rem; letter-spacing:1px; color:#94a3b8; font-weight:800; margin-left:20px;">DAMPAK</div>
                            </div>
                        </div>

                        {{-- Legend & Footer --}}
                        <div class="w-100 border-top pt-3" style="margin-left:18px;">
                            <div class="d-flex flex-wrap gap-3 justify-content-center mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px; height:10px; background:#c00000; border-radius:2px;"></div>
                                    <span style="font-size:0.6rem; font-weight:700; color:#475569;">Ekstrem</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px; height:10px; background:#ff9900; border-radius:2px;"></div>
                                    <span style="font-size:0.6rem; font-weight:700; color:#475569;">Tinggi</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px; height:10px; background:#ffeb3b; border-radius:2px;"></div>
                                    <span style="font-size:0.6rem; font-weight:700; color:#475569;">Sedang</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px; height:10px; background:#198754; border-radius:2px;"></div>
                                    <span style="font-size:0.6rem; font-weight:700; color:#475569;">Rendah</span>
                                </div>
                            </div>
                            <div class="text-center" style="font-size:0.7rem; font-weight:800; color:#1e293b; background:#f1f5f9; padding:6px 16px; border-radius:30px; width:fit-content; margin:0 auto;">
                                <i class="fas fa-bullseye me-1 opacity-5"></i>
                                Total: {{ array_sum(array_map('array_sum', $heatmap)) }} Risiko
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
/* ── Global Toggleable Chart Logic ── */
let activeChart;
const ds = {
    unit: {
        title: 'Risiko per Unit / Divisi',
        type: 'bar',
        labels: @json($unitData->pluck('nama_unit')),
        data: @json($unitData->pluck('identifikasi_count')),
    },
    trend: {
        title: 'Tren Risiko Bulanan',
        type: 'line',
        labels: @json($trendData->pluck('month')),
        data: @json($trendData->pluck('total')),
    }
};

function buildChart(key) {
    const canvas = document.getElementById('mainChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    if (activeChart) activeChart.destroy();
    const d = ds[key];
    document.getElementById('toggleTitle').textContent = d.title;
    
    activeChart = new Chart(ctx, {
        type: d.type,
        data: {
            labels: d.labels,
            datasets: [{
                label: d.title,
                data: d.data,
                backgroundColor: key === 'unit'
                    ? d.data.map((_, i) => ['#007774','#c00000','#ff9900','#198754','#0d6efd','#ffc107'][i % 6])
                    : 'rgba(0,119,116,0.12)',
                borderColor: key === 'unit' ? 'transparent' : '#007774',
                borderWidth: key === 'unit' ? 0 : 3,
                borderRadius: key === 'unit' ? 4 : 0,
                fill: key === 'trend',
                tension: 0.45,
                pointBackgroundColor: '#007774',
                pointRadius: key === 'trend' ? 5 : 0,
                pointHoverRadius: 8,
            }]
        },
        options: {
            indexAxis: key === 'unit' ? 'y' : 'x',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                datalabels: {
                    anchor: 'end',
                    align: key === 'unit' ? 'right' : 'top',
                    color: '#8392ab',
                    font: { weight: 'bold', size: 10 },
                    formatter: (value) => value > 0 ? value : '',
                    offset: 4
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: '#f0f2f5', 
                        display: key === 'unit' ? false : true,
                        borderDash: [4] 
                    },
                    ticks: { font: { size: 10, weight: '600' }, stepSize: 1 }
                },
                x: {
                    beginAtZero: true,
                    grid: { 
                        color: '#f0f2f5',
                        display: key === 'unit' ? true : false,
                        borderDash: [4]
                    },
                    ticks: { font: { size: 10, weight: '600' }, stepSize: 1 }
                }
            }
        }
    });
}

function switchView(key) {
    const keys = ['unit','trend'];
    keys.forEach(k => {
        const btn = document.getElementById('btn' + k.charAt(0).toUpperCase() + k.slice(1));
        if (btn) btn.classList.remove('active');
    });
    
    const activeBtn = document.getElementById('btn' + key.charAt(0).toUpperCase() + key.slice(1));
    if (activeBtn) activeBtn.classList.add('active');
    
    buildChart(key);
}

$(function() {
    // Register the datalabels plugin globally if it exists
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }
    
    // Default Font Global Setup
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#8392ab';

    /* ── Pie Chart ─────────────────────────────────────────────────────────── */
    new Chart(document.getElementById('levelPieChart'), {
        type: 'pie',
        data: {
            labels: @json($categoryStats->pluck('name')),
            datasets: [{
                data: @json($categoryStats->pluck('count')),
                backgroundColor: [
                    '#007774', '#c00000', '#ff9900', '#0d6efd', '#198754', '#ffeb3b', '#6f42c1', '#fd7e14'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverBorderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 10 },
                    formatter: (value) => value > 0 ? value : '',
                },
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 8, padding: 8, font: { size: 9, weight: 700 } }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                            const pct   = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                            return `  ${ctx.label}: ${ctx.raw} risiko (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // Initial build
    buildChart('unit');
});
</script>
@endpush
