@extends('layouts.app')

@section('title', 'Evaluasi Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Evaluasi Risiko')
@section('page_description', 'Tahap Akhir: Hitung risiko residu dan persentase penurunan tingkat risiko.')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-3 bg-white border px-3 py-1 shadow-none" style="border-radius: 50px !important; border-color: #e9ecef !important; background-color: #fbfbfb !important;">
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #c00000; display: inline-block;"></span>
                        <span class="text-xxs font-weight-bold text-dark" style="font-size: 0.62rem !important; letter-spacing: 0.2px;">SANGAT TINGGI</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #ff9900; display: inline-block;"></span>
                        <span class="text-xxs font-weight-bold text-dark" style="font-size: 0.62rem !important; letter-spacing: 0.2px;">TINGGI</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #ffeb3b; display: inline-block; border: 1px solid #dee2e6;"></span>
                        <span class="text-xxs font-weight-bold text-dark" style="font-size: 0.62rem !important; letter-spacing: 0.2px;">SEDANG</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #0d6efd; display: inline-block;"></span>
                        <span class="text-xxs font-weight-bold text-dark" style="font-size: 0.62rem !important; letter-spacing: 0.2px;">RENDAH</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #198754; display: inline-block;"></span>
                        <span class="text-xxs font-weight-bold text-dark" style="font-size: 0.62rem !important; letter-spacing: 0.2px;">SANGAT RENDAH</span>
                    </div>
                </div>

                @if(($activePeriode && $activePeriode->id != request('periode_id', $activePeriode->id)))
                    <span class="badge bg-soft-teal text-teal" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        <i class="fa fa-history me-1"></i> MODE LIBRARY
                    </span>
                @endif
            </div>
            <div class="larik-wrapper">
                @php
                    $lariks = ['all' => 'Tahunan', 's1' => 'S1', 's2' => 'S2', '1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'];
                    $currTri = $viewTriwulan ?? 'all';
                @endphp
                @foreach($lariks as $val => $lbl)
                    <button type="button" 
                            onclick="$('input[name=view_triwulan]').val('{{ $val }}').trigger('change'); $('.btn-larik').removeClass('active'); $(this).addClass('active');"
                            class="btn-larik {{ $currTri == $val ? 'active' : '' }}">
                        {{ $lbl }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .text-teal { color: #007774 !important; }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3 border-radius-lg shadow-sm">
                <div class="card-header py-2 px-3">
                    <form action="{{ route('evaluasi-risiko.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <input type="hidden" name="view_triwulan" class="filter-input" value="{{ $currTri }}">
                        <div class="input-group input-group-sm mb-0" style="width: 220px;">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari kode atau risiko..." value="{{ request('search') }}">
                        </div>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <select name="peringkat" class="form-select form-select-sm select-filter select-pewarna filter-input" id="filterPeringkat">
                            <option value="">Semua Warna</option>
                            <option value="SANGAT TINGGI" {{ request('peringkat') == 'SANGAT TINGGI' ? 'selected' : '' }}>Sangat Tinggi</option>
                            <option value="TINGGI" {{ request('peringkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi</option>
                            <option value="SEDANG" {{ request('peringkat') == 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                            <option value="RENDAH" {{ request('peringkat') == 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                            <option value="SANGAT RENDAH" {{ request('peringkat') == 'SANGAT RENDAH' ? 'selected' : '' }}>Sangat Rendah</option>
                        </select>
                        <select name="unit_id" class="form-select form-select-sm select-filter filter-input" style="min-width: 150px;">
                            <option value="">Semua Unit</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-bordered-light table-evaluasi" id="mainTable">
                        <thead class="bg-light">
                            <!-- Header Row 1 -->
                            <tr>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-no">No</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-kode">Kode</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-pernyataan">Pernyataan Risiko</th>
                                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100">Daftar Risiko (Awal)</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-pemilik">Pemilik</th>
                                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-info text-white">Evaluasi Risiko (Residu)</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-rank">PR</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-penurunan">Penurunan (%)</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-action">Aksi</th>
                            </tr>
                            <!-- Header Row 2 -->
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">P</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">D</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-tr">TR</th>
                                
                                <th class="text-center text-uppercase text-white text-xxs font-weight-bolder bg-info col-num">P</th>
                                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-num">D</th>
                                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-tr">TR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr data-peringkat="{{ strtolower($item->analisis->peringkat_risiko ?? '') }}" data-pemilik="{{ strtolower($item->analisis->pemilik_risiko ?? '') }}">
                                <td class="align-middle text-center">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="text-start text-wrap">
                                    <p class="text-xs font-weight-bold mb-0 text-dark">{{ $item->kegiatan }}</p>
                                    @if($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya')
                                        <div class="mt-1">
                                            <span class="badge badge-sm bg-soft-danger text-danger text-xxs p-1 font-weight-bolder" style="text-transform: none;">
                                                <i class="fa fa-exclamation-circle me-1"></i> {{ $item->evaluasi->frekuensi_kejadian }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Risiko Awal -->
                                <td class="align-middle text-center bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</span>
                                </td>
                                @php
                                    $score = $item->analisis->skor_risiko;
                                    $rank = $score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')));
                                    $bgColor = $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')));
                                    $textColor = ($score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                                @endphp
                                <td class="align-middle text-center" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                                    <span class="text-xs font-weight-bold {{ isset($item->analisis) ? $textColor : 'text-dark' }}">{{ $item->analisis->skor_risiko ?? '-' }}</span>
                                </td>

                                <td class="align-middle text-center px-2">
                                    @php
                                        $rawPemilik = $item->analisis->pemilik_risiko ?? '-';
                                        $pemiliks = array_filter(explode(',', $rawPemilik));
                                        $firstPemilik = $pemiliks[0] ?? '-';
                                        $extraCount = count($pemiliks) > 1 ? count($pemiliks) - 1 : 0;
                                    @endphp
                                    <div class="custom-tooltip-wrapper">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="text-xs text-dark font-weight-bold cursor-pointer text-truncate" style="max-width: 100px;">
                                                {{ $firstPemilik }}
                                            </span>
                                            @if($extraCount > 0)
                                                <span class="badge bg-soft-info text-primary p-1 ms-1" style="font-size: 0.65rem; min-width: 18px;">+{{ $extraCount }}</span>
                                            @endif
                                        </div>

                                        @if(count($pemiliks) > 1)
                                        <div class="custom-tooltip-content">
                                            <div class="p-2">
                                                <div class="mb-2 font-weight-bold border-bottom pb-1 text-info" style="font-size: 10px; letter-spacing: 0.5px;">DAFTAR PEMILIK :</div>
                                                <ul class="list-unstyled mb-0 text-start">
                                                    @foreach($pemiliks as $p)
                                                        <li class="py-1 d-flex align-items-start gap-2" style="font-size: 11px; line-height: 1.2;">
                                                            <i class="fa fa-circle text-info mt-1" style="font-size: 6px;"></i>
                                                            <span class="text-white">{{ trim($p) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                @php
                                    $frekuensi = $item->frekuensi_pelaporan ?? 'triwulan';
                                    $viewTri = $currTri ?? 'all';
                                    $showValue = false;

                                    if ($viewTri == 'all') {
                                        $showValue = true;
                                    } else {
                                        $targetArr = ($viewTri == 's1' ? [1, 2] : ($viewTri == 's2' ? [3, 4] : [$viewTri]));
                                        if ($frekuensi == 'tahunan') {
                                            $showValue = true;
                                        } elseif ($frekuensi == 'semester') {
                                            $itemSem = $item->triwulan <= 2 ? [1, 2] : [3, 4];
                                            if (array_intersect($targetArr, $itemSem)) {
                                                $showValue = true;
                                            }
                                        } elseif ($frekuensi == 'triwulan') {
                                            if (in_array($item->triwulan, $targetArr)) {
                                                $showValue = true;
                                            }
                                        }
                                    }

                                    $isMatch = $showValue;
                                @endphp

                                <!-- Risiko Residu -->
                                <td class="align-middle text-center bg-info-soft">
                                    <span class="text-xs text-dark">{{ $isMatch ? ($item->evaluasi?->probabilitas?->nilai_probabilitas ?? '-') : '-' }}</span>
                                </td>
                                <td class="align-middle text-center bg-info-soft">
                                    <span class="text-xs text-dark">{{ $isMatch ? ($item->evaluasi?->dampak?->nilai_dampak ?? '-') : '-' }}</span>
                                </td>
                                @php
                                    $resScore = $isMatch ? ($item->evaluasi->skor_residu ?? null) : null;
                                    $resRank = $resScore !== null ? ($resScore >= 15 ? 'Sangat Tinggi' : ($resScore >= 10 ? 'Tinggi' : ($resScore >= 5 ? 'Sedang' : ($resScore >= 3 ? 'Rendah' : 'Sangat Rendah')))) : '-';
                                    $resBgColor = $resScore !== null ? ($resScore >= 15 ? '#c00000' : ($resScore >= 10 ? '#ff9900' : ($resScore >= 5 ? '#ffeb3b' : ($resScore >= 3 ? '#0d6efd' : '#198754')))) : '';
                                    $resTextColor = ($resScore !== null && $resScore >= 5 && $resScore < 10) ? 'text-dark' : 'text-white';
                                @endphp
                                <td class="align-middle text-center" style="{{ $resScore !== null ? 'background-color: '.$resBgColor.';' : '' }}">
                                    <span class="text-xs font-weight-bold {{ $resScore !== null ? $resTextColor : 'text-dark' }}">{{ $resScore ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-xxs font-weight-bold text-dark">{{ $resScore !== null ? $resRank : '-' }}</span>
                                </td>

                                <td class="align-middle text-center">
                                    @if($resScore !== null)
                                        <span class="text-xs font-weight-bold text-success">{{ number_format($item->evaluasi->penurunan_persen, 0) }}%</span>
                                    @else
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </td>

                                <td class="align-middle text-center">
                                    <a href="{{ route('evaluasi-risiko.edit', $item->id) }}?view_triwulan={{ $currTri }}" class="btn-action btn-edit" title="{{ $isMatch && isset($item->evaluasi) ? 'Edit Evaluasi' : 'Tambah Evaluasi' }}">
                                        <i class="fa {{ $isMatch && isset($item->evaluasi) ? 'fa-edit' : 'fa-plus' }}"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="text-center py-6 text-secondary text-xs">Belum ada data analisis risiko.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($data->hasPages())
                <div class="card-footer py-3 border-top">
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gray-50 { background-color: #fbfbfb !important; }
    .bg-info-soft { background-color: rgba(17, 205, 239, 0.05) !important; }
    .table-evaluasi {
        table-layout: fixed;
        width: 100%;
    }
    .col-no { width: 25px; }
    .col-kode { width: 70px; }
    .col-pernyataan { width: 180px; }
    .col-num { width: 22px; }
    .col-tr { width: 30px; }
    .col-rank { width: 85px; }
    .col-pemilik { width: 130px; }
    .col-penurunan { width: 80px; }
    .col-action { width: 55px; }

    .table-evaluasi td, .table-evaluasi th {
        padding: 0.4rem 0.3rem !important;
        word-break: break-word;
    }

    .table-evaluasi th {
        white-space: normal;
        line-height: 1.2;
    }

    .table-evaluasi td {
        overflow: visible !important;
    }
    .bg-soft-info { background-color: rgba(33, 150, 243, 0.1) !important; }
    .bg-soft-danger { background-color: rgba(244, 67, 54, 0.1) !important; }

    /* Custom Tooltip Style */
    .custom-tooltip-wrapper {
        position: relative;
        display: inline-block;
    }
    .custom-tooltip-content {
        visibility: hidden;
        min-width: 180px;
        max-width: 250px;
        background-color: #1a1a2e;
        color: #fff;
        text-align: left;
        border-radius: 8px;
        position: absolute;
        z-index: 1050;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        opacity: 0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        pointer-events: none;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(4px);
    }
    .custom-tooltip-content::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -6px;
        border-width: 6px;
        border-style: solid;
        border-color: #1a1a2e transparent transparent transparent;
    }
    .custom-tooltip-wrapper:hover .custom-tooltip-content {
        visibility: visible;
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    .table-responsive { overflow: visible !important; }
    .card { overflow: visible !important; }
</style>
@endsection

@push('js')
<script>
$(document).ready(function() {
    let fadeTimer;

    $('form input[name="search"]').on('keyup', function() {
        clearTimeout(fadeTimer);
        fadeTimer = setTimeout(() => {
            let form = $(this).closest('form');
            loadAjax(form.attr('action') + '?' + form.serialize());
        }, 500);
    });

    $('.filter-input').on('change', function() {
        let form = $(this).closest('form');
        loadAjax(form.attr('action') + '?' + form.serialize());
    });
});
</script>
@endpush



