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
.kpi-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; margin-bottom: 20px; }
@media (max-width: 1199px) { .kpi-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 575px)  { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.kpi-card {
    border-radius: 16px;
    padding: 20px 16px;
    position: relative;
    overflow: hidden;
    border: none;
    cursor: default;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important; }
.kpi-card .kpi-icon {
    font-size: 1.6rem;
    margin-bottom: 12px;
    opacity: 0.9;
}
.kpi-card .kpi-label {
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    opacity: 0.8;
    margin-bottom: 4px;
}
.kpi-card .kpi-value {
    font-size: 2.2rem;
    font-weight: 900;
    line-height: 1;
}
.kpi-card .kpi-sub {
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    opacity: 0.7;
    margin-top: 6px;
}
.kpi-card::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    right: -20px;
    bottom: -20px;
}

/* ── Dark card ──────────────────────────────────────────────────────────── */
.kpi-dark  { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; box-shadow: 0 8px 24px rgba(26,26,46,0.3); }
.kpi-red   { background: linear-gradient(135deg, #c00000 0%, #e53935 100%); color: #fff; box-shadow: 0 8px 24px rgba(192,0,0,0.35); }
.kpi-orange{ background: linear-gradient(135deg, #e65100 0%, #ff9900 100%); color: #fff; box-shadow: 0 8px 24px rgba(255,153,0,0.35); }
.kpi-teal  { background: linear-gradient(135deg, #007774 0%, #00bfa5 100%); color: #fff; box-shadow: 0 8px 24px rgba(0,119,116,0.35); }
.kpi-blue  { background: linear-gradient(135deg, #1565c0 0%, #42a5f5 100%); color: #fff; box-shadow: 0 8px 24px rgba(21,101,192,0.35); }
.kpi-green { background: linear-gradient(135deg, #1b5e20 0%, #43a047 100%); color: #fff; box-shadow: 0 8px 24px rgba(27,94,32,0.35); }

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
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.4px;
    color: #1a1a2e;
    text-transform: uppercase;
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
    grid-template-columns: 28px repeat(5, 1fr);
    gap: 6px;
    padding: 10px 0;
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
    <div class="kpi-grid mb-2">
        <div class="kpi-card kpi-dark">
            <div class="kpi-icon"><i class="fas fa-layer-group"></i></div>
            <div class="kpi-label">Total Risiko</div>
            <div class="kpi-value" data-target="{{ $totalRisks }}">0</div>
            <div class="kpi-sub">SEMUA KATEGORI</div>
        </div>
        <div class="kpi-card kpi-red">
            <div class="kpi-icon"><i class="fas fa-radiation-alt"></i></div>
            <div class="kpi-label">Sangat Tinggi</div>
            <div class="kpi-value" data-target="{{ $levelStats['SANGAT TINGGI'] }}">0</div>
            <div class="kpi-sub">HIGH EXTREME</div>
        </div>
        <div class="kpi-card kpi-orange">
            <div class="kpi-icon"><i class="fas fa-fire-alt"></i></div>
            <div class="kpi-label">Tinggi</div>
            <div class="kpi-value" data-target="{{ $levelStats['TINGGI'] }}">0</div>
            <div class="kpi-sub">HIGH</div>
        </div>
        <div class="kpi-card kpi-teal">
            <div class="kpi-icon"><i class="fas fa-minus-circle"></i></div>
            <div class="kpi-label">Sedang</div>
            <div class="kpi-value" data-target="{{ $levelStats['SEDANG'] }}">0</div>
            <div class="kpi-sub">MEDIUM</div>
        </div>
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="kpi-label">Belum Ditangani</div>
            <div class="kpi-value" data-target="{{ $pendingRisks }}">0</div>
            <div class="kpi-sub">RISK OPEN</div>
        </div>
        <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i class="fas fa-check-double"></i></div>
            <div class="kpi-label">Selesai</div>
            <div class="kpi-value" data-target="{{ $completedRisks }}">0</div>
            <div class="kpi-sub">EVALUATED</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ ROW 2 ══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-3">
        {{-- Pie --}}
        <div class="col-lg-4">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title">Distribusi Level Risiko</span>
                </div>
                <div class="px-3 pb-3" style="height:300px;">
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
                <div class="table-responsive">
                    <table class="table mb-0" style="font-size:0.74rem;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th class="ps-4 py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:none;">NAMA RISIKO</th>
                                <th class="text-center py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:none; width:120px;">PERINGKAT</th>
                                <th class="text-center py-2" style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:none; width:90px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criticalRisks as $rk)
                            <tr class="risk-row" style="border-bottom:1px solid #f0f2f5;">
                                <td class="ps-4 py-2" style="border:none;">
                                    <div class="fw-bold" style="font-size:0.73rem; color:#1a1a2e; line-height:1.3;">{{ \Illuminate\Support\Str::limit($rk->kegiatan, 55) }}</div>
                                    <div style="font-size:0.58rem; color:#94a3b8; font-weight:700; letter-spacing:0.5px; margin-top:2px;">{{ $rk->kode_risiko }}</div>
                                </td>
                                @php
                                    $rank  = strtoupper($rk->analisis->peringkat_risiko ?? '—');
                                    $bg    = $rank=='SANGAT TINGGI'?'#c00000':($rank=='TINGGI'?'#ff9900':($rank=='SEDANG'?'#ffeb3b':'#198754'));
                                    $txtC  = ($rank=='SANGAT TINGGI'||$rank=='RENDAH')?'#fff':'#1a1a2e';
                                @endphp
                                <td class="text-center py-2" style="border:none;">
                                    <span class="level-badge" style="background:{{ $bg }}; color:{{ $txtC }};">{{ $rank }}</span>
                                </td>
                                <td class="text-center py-2" style="border:none;">
                                    @if($rk->evaluasi)
                                        <span style="font-size:0.62rem; font-weight:800; color:#198754; letter-spacing:0.5px;"><i class="fas fa-check-circle me-1"></i>SELESAI</span>
                                    @else
                                        <span style="font-size:0.62rem; font-weight:800; color:#ff9900; letter-spacing:0.5px;"><i class="fas fa-dot-circle me-1"></i>PROSES</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="col-lg-5">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title">Aktivitas Terbaru</span>
                    <span style="font-size:0.6rem; font-weight:700; background:#f0fdf4; color:#007774; padding:3px 10px; border-radius:20px;">LIVE</span>
                </div>
                <div class="p-4">
                    <div class="activity-feed">
                        @foreach($activities as $act)
                        <div class="activity-item">
                            @if($act['type'] == 'ID')
                                <div class="activity-dot" style="background:#3b82f6;"><i class="fas fa-plus"></i></div>
                            @elseif($act['type'] == 'AN')
                                <div class="activity-dot" style="background:#ff9900;"><i class="fas fa-chart-bar"></i></div>
                            @else
                                <div class="activity-dot" style="background:#198754;"><i class="fas fa-check"></i></div>
                            @endif
                            <div class="activity-title">{{ $act['msg'] }}</div>
                            <div class="activity-meta">
                                <span style="background:#f0f2f5; border-radius:4px; padding:1px 6px;">{{ $act['risk'] }}</span>
                                &nbsp;· {{ $act['date']->diffForHumans() }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ ROW 4 HEATMAP ═════════════════════════════════════ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-title">Risk Matrix — Likelihood × Impact</span>
                </div>
                <div class="row p-4 align-items-center g-3">
                    {{-- Y axis label --}}
                    <div class="col-auto d-flex align-items-center justify-content-center" style="min-width:32px;">
                        <div class="hm-y-text">KEMUNGKINAN</div>
                    </div>
                    {{-- Grid --}}
                    <div class="col">
                        <div class="hm-grid">
                            @for($d=5; $d>=1; $d--)
                                <div class="hm-axis-label" style="font-size:0.78rem;">{{ $d }}</div>
                                @for($p=1; $p<=5; $p++)
                                    @php 
                                        $count = $heatmap[$p][$d] ?? 0;
                                        $score = $p*$d;
                                        $col   = $score>=20?'#c00000':($score>=13?'#ff9900':($score>=5?'#ffeb3b':'#198754'));
                                        $shadow= $score>=20?'0 4px 18px rgba(192,0,0,0.5)':($score>=13?'0 4px 18px rgba(255,153,0,0.4)':($score>=5?'0 4px 18px rgba(255,235,59,0.4)':'0 4px 18px rgba(25,135,84,0.4)'));
                                    @endphp
                                    <div class="hm-cell" style="background:{{ $col }}; box-shadow:{{ $shadow }};" title="P={{ $d }} × D={{ $p }} | Skor={{ $score }}">
                                        @if($count > 0)
                                            <div class="hm-bubble">{{ $count }}</div>
                                        @else
                                            <span class="hm-dash">–</span>
                                        @endif
                                    </div>
                                @endfor
                            @endfor
                            <div></div>
                            @for($p=1; $p<=5; $p++) <div class="hm-axis-label" style="font-size:0.78rem;">{{ $p }}</div> @endfor
                        </div>
                        <div class="text-center mt-1"><span class="hm-x-text">DAMPAK</span></div>
                    </div>
                    {{-- Legend --}}
                    <div class="col-lg-3 border-start ps-4">
                        <div style="font-size:0.62rem; font-weight:800; letter-spacing:1px; color:#94a3b8; text-transform:uppercase; margin-bottom:14px;">Keterangan Level</div>
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <div class="legend-dot" style="background:#c00000; box-shadow:0 3px 10px rgba(192,0,0,0.4);"></div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:800; color:#1a1a2e;">Sangat Tinggi</div>
                                <div style="font-size:0.6rem; color:#94a3b8; font-weight:600;">Skor ≥ 20 | Tindakan Segera</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <div class="legend-dot" style="background:#ff9900; box-shadow:0 3px 10px rgba(255,153,0,0.4);"></div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:800; color:#1a1a2e;">Tinggi</div>
                                <div style="font-size:0.6rem; color:#94a3b8; font-weight:600;">Skor 13–19 | Prioritas Tinggi</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <div class="legend-dot" style="background:#ffeb3b; box-shadow:0 3px 10px rgba(255,235,59,0.4);"></div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:800; color:#1a1a2e;">Sedang</div>
                                <div style="font-size:0.6rem; color:#94a3b8; font-weight:600;">Skor 5–12 | Pantau Rutin</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="legend-dot" style="background:#198754; box-shadow:0 3px 10px rgba(25,135,84,0.4);"></div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:800; color:#1a1a2e;">Rendah</div>
                                <div style="font-size:0.6rem; color:#94a3b8; font-weight:600;">Skor 1–4 | Terkendali</div>
                            </div>
                        </div>
                        <div style="margin-top:20px; padding:12px; background:#f8f9fa; border-radius:10px;">
                            <div style="font-size:0.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.8px;">Total di Matrix</div>
                            <div style="font-size:1.4rem; font-weight:900; color:#1a1a2e; margin-top:2px;">{{ array_sum(array_map('array_sum', $heatmap)) }} Risiko</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* ── Animated counter ─────────────────────────────────────────────────── */
document.querySelectorAll('.kpi-value[data-target]').forEach(el => {
    const target = +el.dataset.target;
    let curr = 0;
    const step = Math.max(1, Math.ceil(target / 40));
    const timer = setInterval(() => {
        curr = Math.min(curr + step, target);
        el.textContent = curr.toLocaleString();
        if (curr >= target) clearInterval(timer);
    }, 30);
});

/* ── Pie Chart ─────────────────────────────────────────────────────────── */
new Chart(document.getElementById('levelPieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Sangat Tinggi', 'Tinggi', 'Sedang', 'Rendah'],
        datasets: [{
            data: [
                {{ $levelStats['SANGAT TINGGI'] }},
                {{ $levelStats['TINGGI'] }},
                {{ $levelStats['SEDANG'] }},
                {{ $levelStats['RENDAH'] }}
            ],
            backgroundColor: ['#c00000', '#ff9900', '#ffeb3b', '#198754'],
            borderWidth: 4,
            borderColor: '#ffffff',
            hoverBorderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, padding: 16, font: { size: 11, weight: 700 } }
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

/* ── Toggleable Chart ──────────────────────────────────────────────────── */
let activeChart;
const ctx = document.getElementById('mainChart').getContext('2d');

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
                    ? d.data.map((_, i) => ['#007774','#c00000','#ff9900','#198754','#5e72e4','#f53d0f'][i % 6])
                    : 'rgba(0,119,116,0.12)',
                borderColor: key === 'unit' ? 'transparent' : '#007774',
                borderWidth: key === 'unit' ? 0 : 3,
                borderRadius: key === 'unit' ? 6 : 0,
                fill: key === 'trend',
                tension: 0.45,
                pointBackgroundColor: '#007774',
                pointRadius: key === 'trend' ? 5 : 0,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f2f5', borderDash: [4] },
                    ticks: { font: { size: 10, weight: '600' }, stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '600' } }
                }
            }
        }
    });
}

function switchView(key) {
    ['unit','trend'].forEach(k => {
        document.getElementById('btn' + k.charAt(0).toUpperCase() + k.slice(1)).classList.remove('active');
    });
    document.getElementById('btn' + key.charAt(0).toUpperCase() + key.slice(1)).classList.add('active');
    buildChart(key);
}

buildChart('unit');
</script>
@endpush
