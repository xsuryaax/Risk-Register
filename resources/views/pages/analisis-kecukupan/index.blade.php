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
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('pdf.analisis-kecukupan.all', request()->query()) }}" id="btnExportPdf"
                    class="btn btn-sm bg-white text-dark shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3 border">
                    <i class="fa fa-file-pdf me-2 text-info"></i> Cetak PDF
                </a>
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
                @include('pages.analisis-kecukupan._table')
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

    let xhr;
    function loadAjax(url) {
        if (xhr) xhr.abort();
        
        // Update PDF link with current form state
        const params = url.split('?')[1] || '';
        const pdfUrl = "{{ route('pdf.analisis-kecukupan.all') }}?" + params;
        $('#btnExportPdf').attr('href', pdfUrl);

        $('#tableContainer').css('opacity', '0.8');
        
        xhr = $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                $('#tableContainer').html(data);
                $('#tableContainer').css('opacity', '1');
                window.history.pushState(null, '', url);
            },
            error: function(err) {
                if (err.statusText !== 'abort') {
                    $('#tableContainer').css('opacity', '1');
                    console.error(err);
                }
            }
        });
    }

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





