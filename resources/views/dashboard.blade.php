@extends('layouts.app')

@section('title', 'Risk Register Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard')

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* == KPI CARDS =========================================================== */
        .kpi-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            margin-bottom: 24px;
            width: 100%;
        }

        @media (max-width: 1400px) {
            .kpi-grid {
                gap: 6px;
            }
        }

        @media (max-width: 1200px) {
            .kpi-grid {
                flex-wrap: wrap;
            }

            .kpi-card {
                flex: 1 1 calc(25% - 8px);
            }
        }

        @media (max-width: 768px) {
            .kpi-card {
                flex: 1 1 calc(33.3% - 8px);
            }
        }

        @media (max-width: 480px) {
            .kpi-card {
                flex: 1 1 calc(50% - 8px);
            }
        }

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

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

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
        .kpi-dark {
            background: #2b2b3d;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kpi-red {
            background: #c00000;
            color: #fff;
            box-shadow: 0 4px 12px rgba(192, 0, 0, 0.2);
        }

        .kpi-orange {
            background: #ff9900;
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 153, 0, 0.2);
        }

        .kpi-yellow {
            background: #ffeb3b;
            color: #1a1a2e;
            box-shadow: 0 4px 12px rgba(255, 235, 59, 0.15);
        }

        .kpi-yellow .kpi-icon,
        .kpi-yellow .kpi-label,
        .kpi-yellow .kpi-value,
        .kpi-yellow .kpi-sub {
            color: #1a1a2e !important;
        }

        .kpi-green {
            background: #198754;
            color: #fff;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
        }

        .kpi-blue {
            background: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .kpi-teal {
            background: #007774;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 119, 116, 0.2);
        }

        .kpi-gray {
            background: #8392ab;
            color: #fff;
            box-shadow: 0 4px 12px rgba(131, 146, 171, 0.2);
        }

        /* == CHART CARDS ========================================================= */
        .dash-card {
            background: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: box-shadow 0.25s;
        }

        .dash-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

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
            box-shadow: 0 2px 8px rgba(0, 119, 116, 0.3);
        }

        /* == TOP RISK TABLE ====================================================== */
        .risk-row {
            transition: background 0.15s;
        }

        .risk-row:hover {
            background: #f8f9fa;
        }

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
        .activity-feed {
            position: relative;
            padding-left: 28px;
        }

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

        .activity-item {
            position: relative;
            margin-bottom: 18px;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .activity-title {
            font-size: 0.72rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .activity-meta {
            font-size: 0.6rem;
            color: #94a3b8;
            font-weight: 600;
            margin-top: 2px;
        }

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

        .hm-cell:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            z-index: 2;
            position: relative;
        }

        .hm-bubble {
            background: rgba(255, 255, 255, 0.95);
            color: #1a1a2e;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.72rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .hm-dash {
            color: rgba(255, 255, 255, 0.25);
            font-size: 1rem;
            font-weight: 700;
        }

        .hm-y-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hm-y-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .hm-x-text {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-2 pb-2">
        {{-- Sub-Period / Larik Selector --}}
        <div class="row mb-4 mt-n2">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-xxs font-weight-bolder text-secondary text-uppercase" style="letter-spacing: 1px;">Pilih Tahun:</span>
                    <div class="dropdown">
                        <button class="btn-larik active d-flex align-items-center gap-2 shadow-sm border" type="button" id="dropdownYear" data-bs-toggle="dropdown" aria-expanded="false" 
                                style="background-color: #007774; color: #fff; padding: 5px 16px; border-radius: 8px; border-color: #007774 !important;">
                            <i class="fas fa-calendar-alt opacity-8" style="font-size: 10px;"></i>
                            <span id="selectedYearText" style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $globalPeriodes->firstWhere('id', request('view_periode', $globalActivePeriode->id))->tahun ?? $globalActivePeriode->tahun }}</span>
                            <i class="fas fa-chevron-down opacity-5" style="font-size: 8px;"></i>
                        </button>
                        <ul class="dropdown-menu shadow-xl border-0 border-radius-lg py-2 px-1 mt-1 animate__animated animate__fadeIn animate__faster" aria-labelledby="dropdownYear" style="min-width: 120px; z-index: 1000; background: #ffffff; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <li class="px-3 py-1 mb-1 border-bottom">
                                <span class="text-xxs font-weight-bolder text-uppercase text-secondary" style="font-size: 0.55rem; letter-spacing: 1px;">Daftar Tahun</span>
                            </li>
                            @foreach($globalPeriodes as $p)
                            <li>
                                <a class="dropdown-item py-2 border-radius-md" href="javascript:;" onclick="updatePeriod({{ $p->id }}, '{{ $p->tahun }}')">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-xs font-weight-bold {{ (request('view_periode', $globalActivePeriode->id) == $p->id) ? 'text-teal' : 'text-dark' }}">{{ $p->tahun }}</span>
                                        @if(request('view_periode', $globalActivePeriode->id) == $p->id)
                                            <i class="fas fa-check-circle text-teal" style="font-size: 9px;"></i>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-xxs font-weight-bolder text-secondary text-uppercase" style="letter-spacing: 1px;">Sub-Periode:</span>
                    <div class="larik-wrapper">
                        @php
                            $currTri = request('view_triwulan', 'all');
                            $lariks = ['all' => 'Tahunan', 's1' => 'S1', 's2' => 'S2', '1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'];
                        @endphp
                        @foreach($lariks as $val => $lbl)
                            <button type="button" 
                               onclick="updateTriwulan('{{ $val }}')"
                               class="btn-larik btn-tri {{ $currTri == $val ? 'active' : '' }}"
                               id="btn-tri-{{ $val }}">
                                {{ $lbl }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <style>
            .text-teal { color: #007774 !important; }
        </style>
        <div class="kpi-grid mb-4" id="kpiGrid">
            <a href="{{ route('daftar-risiko.index') }}" class="kpi-card kpi-dark text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-database"></i></div>
                <div class="kpi-label">Total Risiko</div>
                <div class="kpi-value" id="kpi-totalRisks">{{ $allIdentifikasi->count() }}</div>
                <div class="kpi-sub">TOTAL REGISTERED</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['peringkat' => 'SANGAT TINGGI']) }}"
                class="kpi-card kpi-red text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-radiation-alt"></i></div>
                <div class="kpi-label">Sangat Tinggi</div>
                <div class="kpi-value" id="kpi-st">{{ $levelStats['SANGAT TINGGI'] }}</div>
                <div class="kpi-sub">EXTREME RISK</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['peringkat' => 'TINGGI']) }}"
                class="kpi-card kpi-orange text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-fire-alt"></i></div>
                <div class="kpi-label">Tinggi</div>
                <div class="kpi-value" id="kpi-t">{{ $levelStats['TINGGI'] }}</div>
                <div class="kpi-sub">HIGH RISK</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['peringkat' => 'SEDANG']) }}"
                class="kpi-card kpi-yellow text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="kpi-label">Sedang</div>
                <div class="kpi-value" id="kpi-s">{{ $levelStats['SEDANG'] }}</div>
                <div class="kpi-sub">MEDIUM RISK</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['peringkat' => 'RENDAH']) }}"
                class="kpi-card kpi-blue text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
                <div class="kpi-label">Rendah</div>
                <div class="kpi-value" id="kpi-r">{{ $levelStats['RENDAH'] }}</div>
                <div class="kpi-sub">LOW RISK</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['peringkat' => 'SANGAT RENDAH']) }}"
                class="kpi-card kpi-green text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-leaf"></i></div>
                <div class="kpi-label">Sangat Rendah</div>
                <div class="kpi-value" id="kpi-sr">{{ $levelStats['SANGAT RENDAH'] }}</div>
                <div class="kpi-sub">INSIGNIFICANT</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['status' => 'pending']) }}"
                class="kpi-card kpi-gray text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="kpi-label">Belum Ditangani</div>
                <div class="kpi-value" id="kpi-pendingRisks">{{ $pendingRisks }}</div>
                <div class="kpi-sub">RISK OPEN</div>
            </a>
            <a href="{{ route('analisis-risiko.index', ['status' => 'evaluated']) }}"
                class="kpi-card kpi-teal text-decoration-none">
                <div class="kpi-icon"><i class="fas fa-check-double"></i></div>
                <div class="kpi-label">Selesai</div>
                <div class="kpi-value" id="kpi-completedRisks">{{ $completedRisks }}</div>
                <div class="kpi-sub">EVALUATED</div>
            </a>
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
                        <span class="dash-card-title" id="toggleTitle">Analisis Visual Risiko</span>
                        <div class="chart-toggle-group">
                            @if (in_array(auth()->user()->role_id, [1, 2]))
                                <button class="chart-toggle-btn active" id="btnUnit" onclick="switchView('unit')">Per
                                    Unit</button>
                            @endif
                            <button
                                class="chart-toggle-btn {{ !in_array(auth()->user()->role_id, [1, 2]) ? 'active' : '' }}"
                                id="btnTrend" onclick="switchView('trend')">Tren Bulanan</button>
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
                        <a href="{{ route('daftar-risiko.index') }}"
                            style="font-size:0.65rem; font-weight:700; color:#007774; text-decoration:none;">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                    <div class="table-responsive" style="max-height: 290px; overflow-y: auto;">
                        <table class="table mb-0 align-items-center table-bordered"
                            style="font-size:0.74rem; border:1px solid #e9ecef;">
                            <thead class="sticky-top bg-white" style="z-index: 10;">
                                <tr style="background:#f8f9fa;">
                                    <th class="ps-3 py-2 text-center"
                                        style="font-size:0.6rem; font-weight:800; color:#94a3b8; border:1px solid #e9ecef; width:40px;">
                                        #</th>
                                    <th class="ps-2 py-2"
                                        style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef;">
                                        IDENTIFIKASI RISIKO</th>
                                    <th class="text-center py-2"
                                        style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef; width:65px;">
                                        SKOR</th>
                                    <th class="text-center py-2"
                                        style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef; width:130px;">
                                        PERINGKAT</th>
                                    <th class="text-center py-2"
                                        style="font-size:0.6rem; font-weight:800; letter-spacing:1px; color:#94a3b8; border:1px solid #e9ecef; width:100px;">
                                        STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="topRiskBody">
                                @foreach ($criticalRisks as $rk)
                                    <tr class="risk-row" style="border-bottom:1px solid #f0f2f5;">
                                        <td class="text-center py-3 ps-3 fw-bold"
                                            style="border:1px solid #e9ecef; color:#94a3b8; font-size:0.65rem;">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="py-3 ps-2" style="border:1px solid #e9ecef; min-width: 250px;">
                                            <div class="fw-bold text-dark"
                                                style="font-size:0.74rem; line-height:1.4; white-space: normal;">
                                                {{ $rk->kegiatan }}</div>
                                            <div
                                                style="font-size:0.58rem; color:#007774; font-weight:800; letter-spacing:0.5px; margin-top:4px;">
                                                <i class="fas fa-tag me-1 opacity-5"></i>{{ $rk->kode_risiko ?? '-' }}
                                            </div>
                                        </td>
                                        @php
                                            $score = $rk->evaluasi
                                                ? $rk->evaluasi->skor_residu
                                                : $rk->analisis->skor_risiko ?? 0;
                                            $rank = strtoupper(
                                                $rk->evaluasi
                                                    ? $rk->evaluasi->peringkat_residu
                                                    : $rk->analisis->peringkat_risiko ?? '—',
                                            );
                                            $bg =
                                                $rank == 'SANGAT TINGGI'
                                                    ? '#c00000'
                                                    : ($rank == 'TINGGI'
                                                        ? '#ff9900'
                                                        : ($rank == 'SEDANG'
                                                            ? '#ffeb3b'
                                                            : ($rank == 'RENDAH'
                                                                ? '#0d6efd'
                                                                : '#198754')));
                                            $txtC =
                                                $rank == 'SANGAT TINGGI' ||
                                                $rank == 'TINGGI' ||
                                                $rank == 'RENDAH' ||
                                                $rank == 'SANGAT RENDAH'
                                                    ? '#fff'
                                                    : '#1a1a2e';
                                        @endphp
                                        <td class="text-center py-3 fw-900"
                                            style="border:1px solid #e9ecef; color:#1e293b; font-size:0.8rem; background: #f8f9fa;">
                                            {{ $score }}
                                        </td>
                                        <td class="text-center py-3 fw-bold"
                                            style="border:1px solid #e9ecef; background:{{ $bg }}; color:{{ $txtC }}; font-size:0.62rem; letter-spacing:0.5px;">
                                            {{ $rank }}
                                        </td>
                                        <td class="text-center py-3" style="border:1px solid #e9ecef;">
                                            @if ($rk->evaluasi)
                                                <div style="font-size:0.6rem; font-weight:800; color:#198754;"><i
                                                        class="fas fa-check-circle me-1"></i>SELESAI</div>
                                            @else
                                                <div style="font-size:0.6rem; font-weight:800; color:#ff9900;"><i
                                                        class="fas fa-history me-1"></i>PROSES</div>
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
                                <div class="hm-y-text"
                                    style="font-size:0.5rem; letter-spacing:1px; writing-mode:vertical-lr; transform:rotate(180deg); color:#94a3b8; font-weight:800;">
                                    KEMUNGKINAN</div>
                                <div id="heatmapContainer">
                                    <div class="hm-grid">
                                        @for ($d = 5; $d >= 1; $d--)
                                            <div class="hm-axis-label"
                                                style="font-size:0.7rem; color:#94a3b8; font-weight:800;">
                                                {{ $d }}</div>
                                            @for ($p = 1; $p <= 5; $p++)
                                                @php
                                                    $count = $heatmap[$d][$p] ?? 0;
                                                    $score = $p * $d;
                                                    if ($score >= 15) { $col = '#c00000'; }
                                                    elseif ($score >= 10) { $col = '#ff9900'; }
                                                    elseif ($score >= 5) { $col = '#ffeb3b'; }
                                                    elseif ($score >= 3) { $col = '#0d6efd'; }
                                                    else { $col = '#198754'; }
                                                @endphp
                                                @php
                                                    if ($score >= 15) { $rankText = 'SANGAT TINGGI'; } 
                                                    elseif ($score >= 10) { $rankText = 'TINGGI'; } 
                                                    elseif ($score >= 5) { $rankText = 'SEDANG'; } 
                                                    elseif ($score >= 3) { $rankText = 'RENDAH'; } 
                                                    else { $rankText = 'SANGAT RENDAH'; }
                                                @endphp
                                                <a href="{{ route('analisis-risiko.index', ['peringkat' => $rankText]) }}"
                                                    class="hm-cell text-decoration-none"
                                                    style="background:{{ $col }}; height:36px; width:36px; border-radius:4px;"
                                                    title="P={{ $d }} × D={{ $p }} ({{ $rankText }})">
                                                    @if ($count > 0)
                                                        <div class="hm-bubble"
                                                            style="width:22px; height:22px; font-size:0.75rem; font-weight:900; background:rgba(255,255,255,0.95); color:#1a1a2e;">
                                                            {{ $count }}</div>
                                                    @else
                                                        <span class="hm-dash"
                                                            style="font-size:0.6rem; opacity:0.15;">–</span>
                                                    @endif
                                                </a>
                                            @endfor
                                        @endfor
                                        <div></div>
                                        @for ($p = 1; $p <= 5; $p++)
                                            <div class="hm-axis-label"
                                                style="font-size:0.7rem; color:#94a3b8; font-weight:800; padding-top:6px;">
                                                {{ $p }}</div>
                                        @endfor
                                    </div>
                                    <div class="text-center mt-2"
                                        style="font-size:0.5rem; letter-spacing:1px; color:#94a3b8; font-weight:800; margin-left:20px;">
                                        DAMPAK</div>
                                </div>
                            </div>

                            {{-- Legend --}}
                            <div class="w-100 border-top pt-3" style="margin-left:18px;">
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    <div class="d-flex align-items-center gap-1">
                                        <div style="width:10px; height:10px; background:#c00000; border-radius:2px;"></div>
                                        <span style="font-size:0.55rem; font-weight:700; color:#475569;">Sgt Tinggi</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <div style="width:10px; height:10px; background:#ff9900; border-radius:2px;"></div>
                                        <span style="font-size:0.55rem; font-weight:700; color:#475569;">Tinggi</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <div style="width:10px; height:10px; background:#ffeb3b; border-radius:2px;"></div>
                                        <span style="font-size:0.55rem; font-weight:700; color:#475569;">Sedang</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <div style="width:10px; height:10px; background:#0d6efd; border-radius:2px;"></div>
                                        <span style="font-size:0.55rem; font-weight:700; color:#475569;">Rendah</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <div style="width:10px; height:10px; background:#198754; border-radius:2px;"></div>
                                        <span style="font-size:0.55rem; font-weight:700; color:#475569;">Sgt Rendah</span>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let activeChart;
        let levelPieChart;
        let currentSubView = '{{ in_array(auth()->user()->role_id, [1, 2]) ? "unit" : "trend" }}';
        let currentPeriodeId = {{ request('view_periode', $globalActivePeriode->id) }};
        let currentTriwulan = '{{ request('view_triwulan', 'all') }}';
        
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

            let background;
            if (key === 'unit') {
                background = ctx.createLinearGradient(0, 0, canvas.width, 0);
                background.addColorStop(0, '#005f5d');
                background.addColorStop(1, '#00a89d');
            } else {
                background = 'rgba(0,119,116,0.08)';
            }

            activeChart = new Chart(ctx, {
                type: d.type,
                data: {
                    labels: d.labels,
                    datasets: [{
                        label: d.title,
                        data: d.data,
                        backgroundColor: background,
                        borderColor: key === 'unit' ? 'transparent' : '#007774',
                        borderWidth: key === 'unit' ? 0 : 3,
                        borderRadius: 6,
                        maxBarThickness: 22,
                        barPercentage: 0.9,
                        categoryPercentage: 0.9,
                        fill: key === 'trend',
                        tension: 0.4,
                        pointBackgroundColor: '#007774',
                        pointRadius: key === 'trend' ? 5 : 0,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    indexAxis: key === 'unit' ? 'y' : 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { right: 20 } },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            display: key === 'unit',
                            anchor: 'end',
                            align: 'right',
                            color: '#475569',
                            font: { weight: '800', size: 8.5 },
                            formatter: (value) => value > 0 ? value : '',
                            offset: 8
                        },
                        tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8, displayColors: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            suggestedMax: 5, 
                            grid: { display: false, drawBorder: false }, 
                            ticks: { 
                                font: { size: 8, weight: '700' }, 
                                color: '#64748b',
                                padding: 4,
                                autoSkip: false
                            } 
                        },
                        x: { 
                            beginAtZero: true, 
                            suggestedMax: 5, 
                            grid: { color: '#f1f5f9', display: key === 'unit', drawBorder: false }, 
                            ticks: { 
                                font: { size: 8, weight: '700' }, 
                                color: '#64748b',
                                autoSkip: false
                            } 
                        }
                    }
                }
            });
        }

        function switchView(key) {
            currentSubView = key;
            const keys = ['unit', 'trend'];
            keys.forEach(k => {
                const btn = document.getElementById('btn' + k.charAt(0).toUpperCase() + k.slice(1));
                if (btn) btn.classList.remove('active');
            });
            const activeBtn = document.getElementById('btn' + key.charAt(0).toUpperCase() + key.slice(1));
            if (activeBtn) activeBtn.classList.add('active');
            buildChart(key);
        }

        function updatePeriod(id, year) {
            currentPeriodeId = id;
            $('#selectedYearText').text(year);
            
            // Update UI in dropdown
            $('.dropdown-item span').removeClass('text-teal').addClass('text-dark');
            $('.dropdown-item .fa-check-circle').remove();
            
            const $activeItem = $(`.dropdown-item:contains("${year}")`);
            $activeItem.find('span').removeClass('text-dark').addClass('text-teal');
            $activeItem.find('.justify-content-between').append('<i class="fas fa-check-circle text-teal" style="font-size: 9px;"></i>');
            
            updateDashboard();
        }

        function updateTriwulan(val) {
            currentTriwulan = val;
            $('.btn-tri').removeClass('active');
            $(`#btn-tri-${val}`).addClass('active');
            updateDashboard();
        }

        function updateDashboard() {
            // Apply slight fade to show loading
            $('.kpi-value, #topRiskBody, #heatmapContainer, #mainChart, #levelPieChart').css('opacity', '0.5');

            axios.get(`/dashboard`, { 
                params: { 
                    view_triwulan: currentTriwulan,
                    view_periode: currentPeriodeId 
                }, 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            })
            .then(res => {
                const d = res.data;
                
                // 1. KPIs
                $('#kpi-totalRisks').text(d.totalRisks);
                $('#kpi-st').text(d.levelStats['SANGAT TINGGI']);
                $('#kpi-t').text(d.levelStats['TINGGI']);
                $('#kpi-s').text(d.levelStats['SEDANG']);
                $('#kpi-r').text(d.levelStats['RENDAH']);
                $('#kpi-sr').text(d.levelStats['SANGAT RENDAH']);
                $('#kpi-pendingRisks').text(d.pendingRisks);
                $('#kpi-completedRisks').text(d.completedRisks);

                // 2. Main Chart Data Source Update
                ds.unit.labels = d.unitData.map(u => u.nama_unit);
                ds.unit.data = d.unitData.map(u => u.identifikasi_count);
                ds.trend.labels = d.trendData.map(t => t.month);
                ds.trend.data = d.trendData.map(t => t.total);
                buildChart(currentSubView);

                // 3. Pie Chart Update
                levelPieChart.data.labels = d.categoryStats.map(c => c.name);
                levelPieChart.data.datasets[0].data = d.categoryStats.map(c => c.count);
                levelPieChart.update();

                // 4. Top Risk Table Update
                let tableHtml = '';
                d.criticalRisks.forEach((rk, index) => {
                    let bg = rk.rank == 'SANGAT TINGGI' ? '#c00000' : (rk.rank == 'TINGGI' ? '#ff9900' : (rk.rank == 'SEDANG' ? '#ffeb3b' : (rk.rank == 'RENDAH' ? '#0d6efd' : '#198754')));
                    let txtC = (rk.rank == 'SEDANG' ? '#1a1a2e' : '#fff');
                    let statusHtml = rk.status == 'SELESAI' ? `<div style="font-size:0.6rem; font-weight:800; color:#198754;"><i class="fas fa-check-circle me-1"></i>SELESAI</div>` : `<div style="font-size:0.6rem; font-weight:800; color:#ff9900;"><i class="fas fa-history me-1"></i>PROSES</div>`;
                    
                    tableHtml += `
                        <tr class="risk-row" style="border-bottom:1px solid #f0f2f5;">
                            <td class="text-center py-3 ps-3 fw-bold" style="border:1px solid #e9ecef; color:#94a3b8; font-size:0.65rem;">${index + 1}</td>
                            <td class="py-3 ps-2" style="border:1px solid #e9ecef; min-width: 250px;">
                                <div class="fw-bold text-dark" style="font-size:0.74rem; line-height:1.4; white-space: normal;">${rk.kegiatan}</div>
                                <div style="font-size:0.58rem; color:#007774; font-weight:800; letter-spacing:0.5px; margin-top:4px;"><i class="fas fa-tag me-1 opacity-5"></i>${rk.kode || '-'}</div>
                            </td>
                            <td class="text-center py-3 fw-900" style="border:1px solid #e9ecef; color:#1e293b; font-size:0.8rem; background: #f8f9fa;">${rk.score}</td>
                            <td class="text-center py-3 fw-bold" style="border:1px solid #e9ecef; background:${bg}; color:${txtC}; font-size:0.62rem; letter-spacing:0.5px;">${rk.rank}</td>
                            <td class="text-center py-3" style="border:1px solid #e9ecef;">${statusHtml}</td>
                        </tr>`;
                });
                $('#topRiskBody').html(tableHtml || '<tr><td colspan="5" class="text-center py-4 text-xs text-secondary">Tidak ada data risiko kritikal.</td></tr>');

                // 5. Heatmap Update
                for (let d_val = 1; d_val <= 5; d_val++) {
                    for (let p_val = 1; p_val <= 5; p_val++) {
                        let count = d.heatmap[d_val] ? (d.heatmap[d_val][p_val] || 0) : 0;
                        let cell = $(`.hm-cell[title*="P=${d_val} × D=${p_val}"]`);
                        if (count > 0) {
                            cell.html(`<div class="hm-bubble" style="width:22px; height:22px; font-size:0.75rem; font-weight:900; background:rgba(255,255,255,0.95); color:#1a1a2e;">${count}</div>`);
                        } else {
                            cell.html(`<span class="hm-dash" style="font-size:0.6rem; opacity:0.15;">–</span>`);
                        }
                    }
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                $('.kpi-value, #topRiskBody, #heatmapContainer, #mainChart, #levelPieChart').css('opacity', '1');
            });
        }

        $(function() {
            if (typeof ChartDataLabels !== 'undefined') Chart.register(ChartDataLabels);
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#8392ab';
            Chart.defaults.animation = { duration: 400 };

            levelPieChart = new Chart(document.getElementById('levelPieChart'), {
                type: 'pie',
                data: {
                    labels: @json($categoryStats->pluck('name')),
                    datasets: [{
                        data: @json($categoryStats->pluck('count')),
                        backgroundColor: ['#007774', '#c00000', '#ff9900', '#0d6efd', '#198754', '#ffeb3b', '#6f42c1', '#fd7e14'],
                        borderWidth: 1.5, borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        datalabels: { display: false },
                        legend: { position: 'bottom', labels: { boxWidth: 8, padding: 10, font: { size: 9, weight: 600 } } },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                    return `  ${ctx.label}: ${ctx.raw} risiko (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });

            buildChart(currentSubView);
        });
    </script>
@endpush
