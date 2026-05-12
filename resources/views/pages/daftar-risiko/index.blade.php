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
                    <form action="{{ route('daftar-risiko.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="input-group input-group-sm mb-0" style="width: 250px;">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0 text-xs" placeholder="Cari kegiatan atau risiko..." value="{{ request('search') }}">
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
                            <select name="unit_id" class="form-select form-select-sm select-filter filter-input" id="filterUnit">
                                <option value="">Semua Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                @include('pages.daftar-risiko._table')
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



