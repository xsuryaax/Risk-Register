@extends('layouts.app')

@section('title', 'Analisis Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Analisis Risiko')
@section('page_description', 'Langkah Kedua: Evaluasi pengendalian yang ada dan tentukan skor risiko.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-header pb-3 p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="input-group input-group-sm mb-3 mb-md-0" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari kode atau risiko..." id="searchTable">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-bordered-light table-compact-analisis" id="mainTable">
                        <thead class="bg-light">
                            <!-- Header Row 1 -->
                            <tr>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-no">No</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-kegiatan">Kegiatan</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-kode">Kode</th>
                                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100">Pengendalian Yang Ada</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num">P</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num border-right-red">D</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num border-right-red">TR</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-rank border-right-red">PR</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-pemilik">Pemilik</th>
                                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-action">Action</th>
                            </tr>
                            <!-- Header Row 2 -->
                            <tr>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-uraian">Uraian</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-desain">Desain</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-efektif">Efektifitas</th>
                            </tr>
                            <!-- Header Row 3 -->
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-bottom col-desain">Ada/Tdk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="align-middle text-center px-1">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="px-1 text-start">
                                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                
                                <td class="px-1 bg-gray-50 text-start">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->analisis->uraian_pengendalian ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->desain_pengendalian ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->efektifitas_pengendalian ?? '-' }}</span>
                                </td>

                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-dark">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center px-1 border-right-red">
                                    <span class="text-xs font-weight-bold text-dark">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</span>
                                </td>
                                @php
                                    $rank = strtoupper($item->analisis->peringkat_risiko ?? '');
                                    $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffff00' : '#198754'));
                                @endphp
                                <td class="align-middle text-center px-1 border-right-red" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                                    <span class="text-xs font-weight-bold text-dark">
                                        {{ $item->analisis->skor_risiko ?? '-' }}
                                    </span>
                                </td>
                                <td class="align-middle text-center px-1 border-right-red">
                                    @if(isset($item->analisis))
                                        <span class="text-xxs font-weight-bold text-dark">
                                            {{ ucfirst(strtolower($item->analisis->peringkat_risiko)) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs text-dark">{{ $item->analisis->pemilik_risiko ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <a href="{{ route('analisis-risiko.edit', $item->id) }}" class="btn-action btn-edit" title="Evaluasi &amp; Analisis">
                                        <i class="fa {{ isset($item->analisis) ? 'fa-edit' : 'fa-plus' }}"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-6 text-secondary text-xs">Belum ada data risiko.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($data->hasPages())
                <div class="card-footer py-3">
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
    .uppercase { text-transform: uppercase; }
    .border-right-red {
        border-right: 1.5px solid #c00000 !important;
    }
    #mainTable th.border-right-red, 
    #mainTable td.border-right-red {
        border-right: 1.5px solid #c00000 !important;
    }

    /* Compact column widths for Analisis Risiko */
    .table-compact-analisis {
        table-layout: fixed;
        width: 100%;
    }
    .col-no       { width: 22px; }
    .col-kegiatan { width: 130px; }
    .col-kode     { width: 75px; }
    .col-uraian   { width: 80px; }
    .col-desain   { width: 25px; }
    .col-efektif  { width: 38px; }
    .col-num      { width: 32px; }
    .col-rank     { width: 65px; }
    .col-pemilik  { width: 70px; }
    .col-action   { width: 55px; }

    .table-compact-analisis td,
    .table-compact-analisis th {
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
        padding: 0.2rem 0.15rem !important;
    }
</style>
@endsection






