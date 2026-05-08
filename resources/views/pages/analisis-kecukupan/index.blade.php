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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="input-group input-group-sm mb-0" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 text-xs" placeholder="Cari kode atau risiko..." id="searchTable">
                    </div>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <select class="form-select form-select-sm select-filter select-pewarna" id="filterPeringkat">
                            <option value="">Semua Warna</option>
                            <option value="sangat tinggi">Sangat Tinggi</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="sedang">Sedang</option>
                            <option value="rendah">Rendah</option>
                        </select>
                        <select class="form-select form-select-sm select-filter" id="filterPemilik">
                            <option value="">Semua Pemilik</option>
                        </select>
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
                                    $rank = strtoupper($item->analisis->peringkat_risiko ?? '');
                                    $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffff00' : '#198754'));
                                    $textColor = $rank == 'SEDANG' ? 'text-dark' : 'text-white';
                                @endphp
                                <td class="align-middle text-center px-1" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                                    @if(isset($item->analisis))
                                        <span class="text-xs font-weight-bold {{ $textColor }}">
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
                                    <div class="text-xs text-dark col-pemilik-text">{{ $item->analisis->pemilik_risiko ?? '-' }}</div>
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
    let uniquePemilik = new Set();
    $('#mainTable tbody tr').each(function() {
        let pem = $(this).attr('data-pemilik');
        let textPem = $(this).find('.col-pemilik-text').text().trim();
        if(pem && pem !== '-') {
            uniquePemilik.add(textPem);
        }
    });
    
    uniquePemilik = Array.from(uniquePemilik).sort();
    let tsPemilik = document.getElementById('filterPemilik')?.tomselect;

    uniquePemilik.forEach(function(p) {
        if (tsPemilik) {
            tsPemilik.addOption({value: p.toLowerCase(), text: p});
        } else {
            $('#filterPemilik').append(new Option(p, p.toLowerCase()));
        }
    });
    
    function filterTable() {
        const search = $('#searchTable').val().toLowerCase();
        const fPeringkat = $('#filterPeringkat').val().toLowerCase();
        const fPemilik = $('#filterPemilik').val().toLowerCase();
        
        $('#mainTable tbody tr').each(function() {
            if ($(this).find('.empty-state').length) return;
            
            const text = $(this).text().toLowerCase();
            const rowPeringkat = ($(this).attr('data-peringkat') || '').toLowerCase();
            const rowPemilik = ($(this).attr('data-pemilik') || '').toLowerCase();
            
            const matchSearch = text.indexOf(search) > -1;
            const matchPeringkat = fPeringkat === '' || rowPeringkat === fPeringkat;
            const matchPemilik = fPemilik === '' || rowPemilik === fPemilik;
            
            $(this).toggle(matchSearch && matchPeringkat && matchPemilik);
        });
    }

    $('#searchTable').off('keyup').on('keyup', filterTable);
    $('#filterPeringkat, #filterPemilik').on('change', filterTable);
});
</script>
@endpush





