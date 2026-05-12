@extends('layouts.app')

@section('title', 'Analisis Kecukupan - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Analisis Kecukupan Pengendalian')
@section('page_description', 'Tahap Ketiga: Analisis kecukupan pengendalian yang ada dan tentukan rencana pengendalian baru.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-header pb-3 p-3">
                <form action="{{ route('analisis-kecukupan.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="input-group input-group-sm mb-0" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0 text-xs" placeholder="Cari kode atau risiko..." value="{{ request('search') }}">
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
                                    $rank = strtoupper($item->evaluasi ? $item->evaluasi->peringkat_residu : ($item->analisis->peringkat_risiko ?? ''));
                                    $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffeb3b' : ($rank == 'RENDAH' ? '#0d6efd' : '#198754')));
                                    $textColor = ($rank == 'SEDANG' || $rank == '') ? 'text-dark' : 'text-white';
                                @endphp
                                <td class="align-middle text-center px-1" style="{{ $rank ? 'background-color: '.$bgColor.';' : '' }}">
                                    @if($rank)
                                        <span class="text-xs font-weight-bold {{ $textColor }}">
                                            {{ ucfirst(strtolower($rank)) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </td>


                                <!-- Rencana Pengendalian (DIISI USER) -->
                                <td class="px-2 text-start bg-gray-50">
                                    <p class="text-xs mb-0 text-wrap text-dark" style="min-width: 250px;">{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $item->analisisKecukupan->jadwal ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center px-1">
                                    <p class="text-xs text-dark text-wrap mb-0 col-pemilik-text" style="min-width: 100px;">{{ $item->analisis->pemilik_risiko ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center px-1">
                                    <a href="{{ route('analisis-kecukupan.edit', $item->id) }}" class="btn-action btn-edit" title="{{ isset($item->analisisKecukupan) ? 'Edit Rencana' : 'Tambah Rencana' }}">
                                        <i class="fa {{ isset($item->analisisKecukupan) ? 'fa-edit' : 'fa-plus' }}"></i>
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
    .border-right-red {
        border-right: 1.5px solid #c00000 !important;
    }
    .border-left-red {
        border-left: 1.5px solid #c00000 !important;
    }
    #mainTable th.border-right-red, 
    #mainTable td.border-right-red {
        border-right: 1.5px solid #c00000 !important;
    }
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





