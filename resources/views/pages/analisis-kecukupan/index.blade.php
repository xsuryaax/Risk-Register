@extends('layouts.app')

@section('title', 'Analisis Kecukupan - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Analisis Kecukupan Pengendalian')
@section('page_description', 'Tahap Ketiga: Analisis kecukupan pengendalian yang ada dan tentukan rencana pengendalian baru.')

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
                    $lariks = ['all' => 'All', 's1' => 'S1', 's2' => 'S2', '1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'];
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
                    <form action="{{ route('analisis-kecukupan.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
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
                    <table class="table align-items-center mb-0 table-bordered-light" id="mainTable">
                        <thead class="bg-light">
                            <!-- Header Row 1 -->
                            <tr>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">No</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 80px;">Kode</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Pernyataan Risiko</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 50px;">PR</th>
                                <th colspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 border-bottom bg-gray-100">Analisis Kecukupan</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 100px;">Pemilik</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100" style="width: 100px;">PJ Lanjut</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Action</th>
                            </tr>
                            <!-- Header Row 2 -->
                            <tr>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top" style="min-width: 250px;">Uraian Rencana</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top" style="width: 120px;">Jadwal</th>
                            </tr>
                            <!-- Header Row 3 -->
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-bottom">Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr data-peringkat="{{ strtolower($item->analisis->peringkat_risiko ?? '') }}" data-pemilik="{{ strtolower($item->analisis->pemilik_risiko ?? '') }}">
                                <td class="align-middle text-center px-1">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="px-2 text-start">
                                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                                </td>

                                @php
                                    $score = $item->analisis->skor_risiko ?? null;
                                    $rankLabel = $score !== null ? ($score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')))) : '-';
                                    $bgColor = $score !== null ? ($score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')))) : '';
                                    $textColor = ($score !== null && $score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                                @endphp
                                <td class="align-middle text-center px-1" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                                    @if($score !== null)
                                        <span class="text-xs font-weight-bold {{ $textColor }}">
                                            {{ $rankLabel }}
                                        </span>
                                    @else
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </td>


                                @php
                                    $targetVal = ($currTri == 's1' ? [1, 2] : ($currTri == 's2' ? [3, 4] : [$currTri]));
                                    $isMatch = $currTri == 'all' || in_array($item->triwulan, $targetVal);
                                @endphp

                                <!-- Rencana Pengendalian (DIISI USER) -->
                                <td class="px-2 text-start bg-gray-50">
                                    <p class="text-xs mb-0 text-wrap text-dark" style="min-width: 250px;">{{ $isMatch ? ($item->analisisKecukupan->uraian_rencana ?? '-') : '-' }}</p>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $isMatch ? ($item->analisisKecukupan->jadwal ?? '-') : '-' }}</p>
                                </td>

                                <td class="align-middle text-center px-1">
                                    @php
                                        $rawPemilik = $item->analisis->pemilik_risiko ?? '-';
                                        $pemiliks = array_filter(explode(',', $rawPemilik));
                                        $firstPemilik = $pemiliks[0] ?? '-';
                                        $extraCount = count($pemiliks) > 1 ? count($pemiliks) - 1 : 0;
                                    @endphp
                                    <div class="custom-tooltip-wrapper">
                                        <span class="text-xs text-dark cursor-pointer">
                                            {{ $firstPemilik }}
                                            @if($extraCount > 0)
                                                <span class="badge bg-soft-info text-primary p-1 ms-1" style="font-size: 0.65rem;">+{{ $extraCount }}</span>
                                            @endif
                                        </span>

                                        @if(count($pemiliks) > 1)
                                        <div class="custom-tooltip-content">
                                            <div class="px-2 py-1">
                                                <div class="mb-1 font-weight-bold border-bottom pb-1 text-white opacity-8" style="font-size: 10px;">DAFTAR PEMILIK :</div>
                                                <ul class="list-unstyled mb-0 text-start">
                                                    @foreach($pemiliks as $p)
                                                        <li class="py-1" style="font-size: 11px; white-space: nowrap;">
                                                            <i class="fa fa-caret-right me-1 text-info"></i> {{ $p }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $isMatch ? ($item->analisisKecukupan->pj_tindak_lanjut ?? '-') : '-' }}</p>
                                </td>

                                <td class="align-middle text-center px-1">
                                    <a href="{{ route('analisis-kecukupan.edit', $item->id) }}?view_triwulan={{ $currTri }}" class="btn-action btn-edit" title="{{ isset($item->analisisKecukupan) && $item->triwulan == $currTri ? 'Edit Rencana' : 'Tambah Rencana' }}">
                                        <i class="fa {{ isset($item->analisisKecukupan) && $item->triwulan == $currTri ? 'fa-edit' : 'fa-plus' }}"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-secondary text-xs">Belum ada data analisis risiko.</td>
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
    .bg-soft-info { background-color: rgba(33, 150, 243, 0.1) !important; }

    /* Custom Tooltip Style */
    .custom-tooltip-wrapper {
        position: relative;
        display: inline-block;
    }
    .custom-tooltip-content {
        visibility: hidden;
        min-width: 160px;
        background-color: #1a1a2e;
        color: #fff;
        text-align: left;
        border-radius: 8px;
        position: absolute;
        z-index: 10000;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        pointer-events: none;
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
        transform: translateX(-50%) translateY(-5px);
    }
    
    .table-responsive { overflow: visible !important; }
    .card { overflow: visible !important; }

    .border-right-red { border-right: 1.5px solid #c00000 !important; }
    .border-left-red { border-left: 1.5px solid #c00000 !important; }
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





