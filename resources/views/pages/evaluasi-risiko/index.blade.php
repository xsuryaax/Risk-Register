@extends('layouts.app')

@section('title', 'Evaluasi Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Evaluasi Risiko')
@section('page_description', 'Tahap Akhir: Hitung risiko residu dan persentase penurunan tingkat risiko.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-body px-0 pt-0 pb-2">
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
                                <th colspan="4" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-info text-white">Evaluasi Risiko (Residu)</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-penurunan">Penurunan (%)</th>
                                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-action">Action</th>
                            </tr>
                            <!-- Header Row 2 -->
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">P</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">D</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-tr">TR</th>
                                
                                <th class="text-center text-uppercase text-white text-xxs font-weight-bolder bg-info col-num">P</th>
                                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-num">D</th>
                                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-tr">TR</th>
                                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-rank">PR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="text-start text-wrap">
                                    <p class="text-xs font-weight-bold mb-0 text-dark">{{ $item->kegiatan }}</p>
                                </td>

                                <!-- Risiko Awal -->
                                <td class="align-middle text-center bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</span>
                                </td>
                                @php
                                    $rank = strtoupper($item->analisis->peringkat_risiko ?? '');
                                    $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffff00' : '#198754'));
                                @endphp
                                <td class="align-middle text-center" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                                    <span class="text-xs font-weight-bold text-dark">{{ $item->analisis->skor_risiko ?? '-' }}</span>
                                </td>

                                <td class="align-middle text-center">
                                    <span class="text-xs text-dark">{{ $item->analisis->pemilik_risiko ?? '-' }}</span>
                                </td>

                                <!-- Risiko Residu -->
                                <td class="align-middle text-center bg-info-soft">
                                    <span class="text-xs text-dark">{{ $item->evaluasi->probabilitas->nilai_probabilitas ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center bg-info-soft">
                                    <span class="text-xs text-dark">{{ $item->evaluasi->dampak->nilai_dampak ?? '-' }}</span>
                                </td>
                                @php
                                    $resRank = strtoupper($item->evaluasi->peringkat_residu ?? '');
                                    $resBgColor = $resRank == 'SANGAT TINGGI' ? '#c00000' : ($resRank == 'TINGGI' ? '#ff9900' : ($resRank == 'SEDANG' ? '#ffff00' : '#198754'));
                                @endphp
                                <td class="align-middle text-center" style="{{ isset($item->evaluasi) ? 'background-color: '.$resBgColor.';' : '' }}">
                                    <span class="text-xs font-weight-bold text-dark">{{ $item->evaluasi->skor_residu ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-xxs font-weight-bold text-dark">{{ isset($item->evaluasi) ? ucfirst(strtolower($item->evaluasi->peringkat_residu)) : '-' }}</span>
                                </td>

                                <td class="align-middle text-center">
                                    @if(isset($item->evaluasi))
                                        <span class="text-xs font-weight-bold text-success">{{ number_format($item->evaluasi->penurunan_persen, 0) }}%</span>
                                    @else
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </td>

                                <td class="align-middle text-center">
                                    <a href="{{ route('evaluasi-risiko.edit', $item->id) }}" class="btn-action btn-edit" title="Evaluasi Residu">
                                        <i class="fa {{ isset($item->evaluasi) ? 'fa-edit' : 'fa-plus' }}"></i>
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
    .col-pernyataan { width: 160px; }
    .col-num { width: 28px; }
    .col-tr { width: 35px; }
    .col-rank { width: 55px; }
    .col-pemilik { width: 90px; }
    .col-penurunan { width: 80px; }
    .col-action { width: 55px; }

    .table-evaluasi td, .table-evaluasi th {
        padding: 0.3rem 0.2rem !important;
        word-break: break-word;
    }

    .table-evaluasi th {
        white-space: normal;
        line-height: 1.2;
    }

    .table-evaluasi td {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection



