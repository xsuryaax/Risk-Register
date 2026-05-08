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
                <form action="{{ route('analisis-risiko.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="input-group input-group-sm mb-0" style="width: 250px;">
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
                        </select>
                        <select name="pemilik" class="form-select form-select-sm filter-input">
                            <option value="">Semua Pemilik</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner }}" {{ request('pemilik') == $owner ? 'selected' : '' }}>{{ $owner }}</option>
                            @endforeach
                        </select>
                        @if(request()->anyFilled(['search', 'peringkat', 'pemilik']))
                            <a href="{{ route('analisis-risiko.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                @include('pages.analisis-risiko._table')
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

    /* Compact column table style */
    .table-compact-analisis {
        table-layout: fixed;
        width: 100%;
    }

    .table-compact-analisis td,
    .table-compact-analisis th {
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
        padding: 0.2rem 0.15rem !important;
    }
</style>
@endsection

@push('js')
<script>
$(document).ready(function() {
    let fadeTimer;
    
    function fetchResults(url) {
        $('#tableContainer').css('opacity', '0.5');
        
        $.ajax({
            url: url,
            success: function(data) {
                $('#tableContainer').html(data).css('opacity', '1');
                // Update URL without reload
                window.history.pushState({}, '', url);
            }
        });
    }

    $('form input[name="search"]').on('keyup', function() {
        clearTimeout(fadeTimer);
        fadeTimer = setTimeout(() => {
            let form = $(this).closest('form');
            fetchResults(form.attr('action') + '?' + form.serialize());
        }, 500);
    });

    $('.filter-input').on('change', function() {
        let form = $(this).closest('form');
        fetchResults(form.attr('action') + '?' + form.serialize());
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchResults($(this).attr('href'));
    });
});
</script>
@endpush






