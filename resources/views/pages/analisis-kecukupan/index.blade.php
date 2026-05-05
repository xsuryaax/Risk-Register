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
                <div class="row align-items-center mb-3">
                    <div class="col-12">
                        <div class="bg-gray-100 p-3 border-radius-lg">
                            <h6 class="text-xs font-weight-bolder text-primary text-uppercase mb-2">Ket Kode Risiko:</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">P</span> PASIEN</span>
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">S</span> STAF</span>
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">N</span> NAKES LAIN</span>
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">F</span> FASILITAS</span>
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">L</span> LINGKUNGAN</span>
                                <span class="text-xxs font-weight-bold text-dark"><span class="badge bg-secondary me-1">B</span> BISNIS RS</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="input-group input-group-sm mb-3 mb-md-0" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 text-xs" placeholder="Cari kode atau risiko..." id="searchTable">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
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
                            <tr>
                                <td class="align-middle text-center px-1">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="px-2 text-start">
                                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                                </td>

                                <td class="align-middle text-center px-1" style="{{ isset($item->analisis) ? 'background-color: '.($item->analisis->skor_risiko >= 20 ? '#dc3545' : ($item->analisis->skor_risiko >= 13 ? '#fd7e14' : ($item->analisis->skor_risiko >= 5 ? '#ffc107' : '#198754'))).';' : '' }}">
                                    @if(isset($item->analisis))
                                        <span class="text-xs font-weight-bold text-dark">
                                            {{ ucfirst(strtolower($item->analisis->peringkat_risiko)) }}
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
                                    <span class="text-xs text-dark">{{ $item->analisisKecukupan->jadwal ?? '-' }}</span>
                                </td>

                                <td class="align-middle text-center px-1">
                                    <div class="text-xs text-dark">{{ $item->analisis->pemilik_risiko ?? '-' }}</div>
                                </td>
                                <td class="align-middle text-center px-1 bg-gray-50">
                                    <span class="text-xs text-dark">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? '-' }}</span>
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
        border-right: 1.5px solid #ff0000 !important;
    }
    .border-left-red {
        border-left: 1.5px solid #ff0000 !important;
    }
    #mainTable th.border-right-red, 
    #mainTable td.border-right-red {
        border-right: 1.5px solid #ff0000 !important;
    }
</style>
@endsection
