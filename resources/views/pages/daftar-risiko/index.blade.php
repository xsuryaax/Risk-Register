@extends('layouts.app')

@section('title', 'Daftar Lengkap Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Daftar Lengkap Risiko')
@section('page_description', 'Ringkasan keseluruhan risiko, evaluasi, dan rencana tindak lanjut.')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-radius-lg shadow-sm">
                <div class="card-header pb-3 p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="input-group input-group-sm mb-3 mb-md-0" style="width: 250px;">
                            <span class="input-group-text bg-transparent border-end-0"><i
                                    class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0"
                                placeholder="Cari kegiatan atau risiko..." id="searchTable">
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                    <div class="p-0">
                        <table class="table align-items-center mb-0 table-bordered-light table-daftar" id="mainTable">
                            <thead class="bg-light sticky-top" style="z-index: 2;">
                                <tr>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-no">
                                        No</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-kegiatan">
                                        Kegiatan</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-risiko">
                                        Pernyataan<br>Risiko</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-sebab">
                                        Sebab</th>
                                    <th colspan="4"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100">
                                        Analisis Risiko</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pengendalian">
                                        Pengendalian<br>Yang Ada</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-rencana">
                                        Rencana<br>Tindak Lanjut</th>
                                    <th rowspan="2"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pj">
                                        PJ</th>
                                </tr>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                                        P</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                                        D</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                                        TR</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-rank">
                                        Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($risikos as $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="align-middle text-start">
                                            <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">
                                                {{ $item->kegiatan }}</p>
                                            <span class="text-xxs text-primary">{{ $item->kode_risiko }}</span>
                                        </td>
                                        <td class="align-middle text-start">
                                            <p class="text-xs mb-0 text-wrap text-dark">{{ $item->pernyataan_risiko }}</p>
                                        </td>
                                        <td class="align-middle text-start">
                                            <p class="text-xs mb-0 text-wrap text-dark">{{ $item->sebab }}</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <span
                                                class="text-xs font-weight-bold text-dark">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-xs font-weight-bold text-dark">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</span>
                                        </td>
                                        @php
                                            $rank = strtoupper($item->analisis->peringkat_risiko ?? '');
                                            $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffff00' : '#198754'));
                                        @endphp
                                        <td class="align-middle text-center" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                                            <span class="text-xs font-weight-bold text-dark">{{ $item->analisis->skor_risiko ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-xxs font-weight-bold {{ isset($item->analisis) ? 'text-dark' : 'text-secondary' }}">{{ isset($item->analisis) ? ucfirst(strtolower($item->analisis->peringkat_risiko)) : '-' }}</span>
                                        </td>

                                        <td class="align-middle text-start text-wrap">
                                            <span
                                                class="text-xs text-dark">{{ $item->analisis->uraian_pengendalian ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-start text-wrap">
                                            <span
                                                class="text-xs text-dark">{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-center text-wrap">
                                            <span
                                                class="text-xs text-dark">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? ($item->analisis->pemilik_risiko ?? '-') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-6 text-secondary text-xs">Belum ada daftar
                                            lengkap risiko.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($risikos->hasPages())
                        <div class="card-footer py-3">
                            <div class="d-flex justify-content-center">
                                {{ $risikos->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gray-50 {
            background-color: #fbfbfb !important;
        }

        .bg-gray-100 {
            background-color: #f8f9fa !important;
        }

        .border-right-red {
            border-right: 1.5px solid #c00000 !important;
        }

        #mainTable th.border-right-red,
        #mainTable td.border-right-red {
            border-right: 1.5px solid #c00000 !important;
        }

        /* Daftar Lengkap compact table */
        .table-daftar {
            table-layout: auto;
            width: 100%;
        }

        .table-daftar td,
        .table-daftar th {
            padding: 0.2rem 0.2rem !important;
            word-break: break-word;
        }

        .table-daftar th {
            white-space: normal;
            line-height: 1.2;
        }

        .table-daftar td {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Column widths */
        .dl-no {
            width: 22px;
        }

        .dl-kegiatan {
            width: 130px;
        }

        .dl-risiko {
            width: 120px;
        }

        .dl-sebab {
            width: 100px;
        }

        .dl-num {
            width: 26px;
        }

        .dl-rank {
            width: 55px;
        }

        .dl-pengendalian {
            width: 110px;
        }

        .dl-rencana {
            width: 160px;
        }

        .dl-pj {
            width: 75px;
        }
    </style>

    <script>
        document.getElementById('searchTable').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('#mainTable tbody tr');

            rows.forEach(row => {
                if (row.cells.length <= 1) return;
                let textContent = row.textContent.toLowerCase();
                row.style.display = textContent.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endsection



