@extends('layouts.app')

@section('title', 'Analisis Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Analisis Risiko')
@section('page_description', 'Langkah Kedua: Evaluasi pengendalian yang ada dan tentukan skor risiko.')

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
                <a href="{{ route('pdf.analisis-risiko.all', request()->query()) }}" id="btnExportPdf"
                    class="btn btn-sm bg-white text-dark shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3 border">
                    <i class="fa fa-file-pdf me-2 text-info"></i> Cetak PDF
                </a>
                <div class="larik-wrapper">
                    @php
                        $lariks = ['all' => 'Tahunan', 's1' => 'S1', 's2' => 'S2', '1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'];
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
                    <form action="{{ route('analisis-risiko.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-0">
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
                @include('pages.analisis-risiko._table')
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gray-50 { background-color: #fbfbfb !important; }
    .uppercase { text-transform: uppercase; }
    
    /* Compact column table style */
    .table-compact-analisis {
        width: 100%;
    }

    .table-compact-analisis td,
    .table-compact-analisis th {
        padding: 0.2rem 0.15rem !important;
    }

    /* Fix Overflow to allow dropdowns/tooltips to overflow the table */
    .card, .card-body, .table-responsive {
        overflow: visible !important;
    }
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
        const form = $('#filterForm').length ? $('#filterForm') : $('form').first();
        const params = form.serialize();
        const pdfUrl = "{{ route('pdf.analisis-risiko.all') }}?" + params;
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

    // Toggle Edit Mode
    $(document).on('click', '.btn-toggle-edit', function() {
        const row = $(this).closest('tr');
        
        // Backup original state for cancel button
        const scoreCol = row.find('.col-score');
        const labelScore = row.find('.label-score');
        const labelRank = row.find('.label-rank');
        
        row.data('orig-score-text', labelScore.text());
        row.data('orig-score-color', scoreCol.css('background-color'));
        row.data('orig-score-class', labelScore.attr('class'));
        row.data('orig-rank-text', labelRank.text());

        row.find('.view-mode').addClass('d-none');
        row.find('.edit-mode').removeClass('d-none');
        
        // Init TomSelect on demand for the unit dropdown
        const select = row.find('.ts-multi');
        if (select.length && !select[0].tomselect) {
            new TomSelect(select[0], {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: 'Pilih Unit...',
                dropdownParent: 'body',
                onInitialize: function() {
                    this.control.classList.add('form-control-sm');
                }
            });
        }
    });

    // Cancel Edit Mode
    $(document).on('click', '.btn-inline-cancel', function() {
        const row = $(this).closest('tr');
        
        // Restore original state before hiding edit mode
        const scoreCol = row.find('.col-score');
        const labelScore = row.find('.label-score');
        const labelRank = row.find('.label-rank');

        labelScore.text(row.data('orig-score-text'));
        scoreCol.css('background-color', row.data('orig-score-color'));
        labelScore.attr('class', row.data('orig-score-class'));
        labelRank.text(row.data('orig-rank-text'));

        row.find('.edit-mode').addClass('d-none');
        row.find('.view-mode').removeClass('d-none');
    });

    // Inline Save Logic
    $(document).on('click', '.btn-inline-save', function() {
        const btn = $(this);
        const row = btn.closest('tr');
        const id = row.data('id');
        const originalContent = btn.html();
        
        // Multi-select handling
        const pemilikVal = row.find('.edit-pemilik').val();
        const pemilikStr = Array.isArray(pemilikVal) ? pemilikVal.join(',') : pemilikVal;

        // Data gathering
        const data = {
            _token: '{{ csrf_token() }}',
            uraian_pengendalian: row.find('.edit-uraian').val(),
            desain_pengendalian: row.find('.edit-desain').val(),
            efektifitas_pengendalian: row.find('.edit-efektif').val(),
            probabilitas_id: row.find('.edit-prob').val(),
            dampak_id: row.find('.edit-dampak').val(),
            pemilik_risiko: pemilikStr,
            view_triwulan_active: $('input[name=view_triwulan]').val()
        };

        // UI Feedback
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin text-xs"></i>');

        $.ajax({
            url: `/analisis-risiko/${id}`,
            method: 'POST',
            data: data,
            success: function(res) {
                if (res.success) {
                    // Update View Mode Labels
                    row.find('.label-uraian').text(data.uraian_pengendalian || '-');
                    row.find('.label-desain').text(data.desain_pengendalian || '-');
                    row.find('.label-efektif').text(data.efektifitas_pengendalian || '-');
                    row.find('.label-prob').text(row.find('.edit-prob option:selected').text() || '-');
                    row.find('.label-dampak').text(row.find('.edit-dampak option:selected').text() || '-');
                    
                    // Update Pemilik View (+1 logic)
                    const pArray = data.pemilik_risiko ? data.pemilik_risiko.split(',').filter(x => x.trim()) : [];
                    const firstP = pArray[0] || '-';
                    const eCount = pArray.length > 1 ? pArray.length - 1 : 0;
                    
                    row.find('.label-pemilik-text').text(firstP);
                    
                    // Update the list inside the custom tooltip
                    const listContainer = row.find('.label-pemilik-list');
                    listContainer.empty();
                    pArray.forEach(p => {
                        listContainer.append(`<li class="py-1" style="font-size: 11px; white-space: nowrap;"><i class="fa fa-caret-right me-1 text-info"></i> ${p}</li>`);
                    });

                    // Manage badge and tooltip visibility
                    const extraBadge = row.find('.label-pemilik-extra');
                    const tooltipContent = row.find('.custom-tooltip-content');
                    
                    if (eCount > 0) {
                        tooltipContent.removeClass('d-none');
                        if (extraBadge.length) {
                            extraBadge.removeClass('d-none').text('+' + eCount);
                        } else {
                            row.find('.label-pemilik-container').append(`<span class="badge bg-soft-info text-primary p-1 ms-1 label-pemilik-extra" style="font-size: 0.65rem;">+${eCount}</span>`);
                        }
                    } else {
                        extraBadge.addClass('d-none');
                        tooltipContent.addClass('d-none');
                    }

                    // Update Score and Rank
                    const scoreCol = row.find('.col-score');
                    const rankCol = row.find('.col-rank');
                    scoreCol.css('background-color', res.color);
                    scoreCol.find('.label-score').text(res.score || '-').removeClass('text-dark text-white').addClass(res.text_color);
                    rankCol.find('.label-rank').text(res.rank || '-');
                    
                    // Toggle back to view mode
                    row.find('.edit-mode').addClass('d-none');
                    row.find('.view-mode').removeClass('d-none');
                    
                    // Reset button state
                    btn.prop('disabled', false).html(originalContent);

                    // Update the edit button icon/title
                    row.find('.btn-toggle-edit i').removeClass('fa-plus').addClass('fa-edit');
                    row.find('.btn-toggle-edit').attr('title', 'Edit Analisis');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalContent);
                let msg = 'Gagal menyimpan perubahan';
                if (xhr.status === 422) {
                    msg = 'Data belum lengkap (P & D harus terisi)';
                }
                alert(msg);
            }
        });
    });

    // Real-time calculation for Inline Edit
    $(document).on('change', '.edit-prob, .edit-dampak', function() {
        const row = $(this).closest('tr');
        const pVal = parseInt(row.find('.edit-prob option:selected').text()) || 0;
        const dVal = parseInt(row.find('.edit-dampak option:selected').text()) || 0;
        const score = pVal * dVal;
        
        const scoreCol = row.find('.col-score');
        const rankCol = row.find('.col-rank');
        const labelScore = scoreCol.find('.label-score');
        const labelRank = rankCol.find('.label-rank');
        
        labelScore.text(score > 0 ? score : '-');
        
        let bgColor = '';
        let textColor = 'text-white';
        let rank = '-';
        
        if (score >= 15) {
            bgColor = '#c00000';
            rank = 'Sangat Tinggi';
        } else if (score >= 10) {
            bgColor = '#ff9900';
            rank = 'Tinggi';
        } else if (score >= 5) {
            bgColor = '#ffeb3b';
            rank = 'Sedang';
            textColor = 'text-dark';
        } else if (score >= 3) {
            bgColor = '#0d6efd';
            rank = 'Rendah';
        } else if (score > 0) {
            bgColor = '#198754';
            rank = 'Sangat Rendah';
        }
        
        scoreCol.css('background-color', bgColor || 'transparent');
        labelScore.removeClass('text-dark text-white').addClass(textColor);
        labelRank.text(rank);
    });
});
</script>
@endpush

<style>
    /* Custom Stable Tooltip System */
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
    .label-pemilik-list {
        padding-left: 0;
        list-style: none;
    }

    /* Spreadsheet style tweaks */
    .edit-mode .form-control-sm, 
    .edit-mode .form-select-sm {
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        padding: 0.3rem 0.5rem;
        font-size: 0.75rem !important;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }

    .edit-mode .form-control-sm:focus, 
    .edit-mode .form-select-sm:focus {
        border-color: #007774;
        box-shadow: 0 0 0 2px rgba(0, 119, 116, 0.1);
        outline: none;
        background-color: #fff;
    }

    .edit-mode .form-select-sm {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-position: right 0.5rem center !important;
        background-size: 10px 10px !important;
        padding-right: 1.5rem !important;
    }

    /* Specific compacting for P & D */
    .edit-prob, .edit-dampak {
        padding-left: 0.3rem !important;
        padding-right: 1rem !important;
        text-align: center;
        font-weight: 700;
        color: #1a1a2e;
        cursor: pointer;
    }

    /* Row Hover */
    .row-analisis:hover {
        background-color: #fcfcfc;
    }

    textarea.form-control-sm {
        line-height: 1.3;
        min-height: 50px;
    }

    .bg-gray-50 { background-color: #f8f9fa !important; }
    
    /* Layout preservation */
    .col-score {
        transition: all 0.3s ease;
    }

    .table-compact-analisis td {
        vertical-align: middle !important;
        border-bottom: 1px solid #f0f2f5;
    }


    .bg-soft-info {
        background-color: rgba(33, 150, 243, 0.1) !important;
    }
</style>






