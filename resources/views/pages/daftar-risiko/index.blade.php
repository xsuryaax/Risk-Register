@extends('layouts.app')

@section('title', 'Daftar Lengkap Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Daftar Lengkap Risiko')
@section('page_description', 'Ringkasan keseluruhan risiko, evaluasi, dan rencana tindak lanjut.')

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

                @if($viewPeriodeId != ($activePeriode->id ?? 0))
                    <span class="badge bg-soft-teal text-teal" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        <i class="fa fa-history me-1"></i> MODE LIBRARY
                    </span>
                @endif
            </div>
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

    <style>
        .text-teal { color: #007774 !important; }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3 border-radius-lg shadow-sm">
                <div class="card-header pb-2 py-2 px-3">
                    <form action="{{ route('daftar-risiko.index') }}" method="GET" id="filterForm">
                        <input type="hidden" name="view_triwulan" class="filter-input" value="{{ $currTri }}">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div>
                                <div class="input-group input-group-sm mb-0" style="width: 220px; height: 32px;">
                                    <span class="input-group-text bg-transparent border-end-0"><i
                                            class="fa fa-search text-xs"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0 pe-2"
                                        placeholder="Cari..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @php
                                    $isNotActivePeriod = $activePeriode && $viewPeriodeId != $activePeriode->id;
                                @endphp
                                @if ($isNotActivePeriod && count($risikos) > 0)
                                    <button type="button" id="btnBulkPull"
                                        class="btn btn-sm text-white shadow-sm border-radius-lg mb-0 text-capitalize py-1 px-3 d-none"
                                        style="background-color: #007774 !important; height: 32px;">
                                        <i class="fa fa-download me-1"></i> Tarik Data (<span id="selectedCount">0</span>)
                                    </button>
                                @endif
                                
                                <select name="periode_id" class="form-select form-select-sm select-filter filter-input" style="width: 140px; height: 32px;">
                                    @foreach($periodes as $p)
                                        <option value="{{ $p->id }}" {{ $viewPeriodeId == $p->id ? 'selected' : '' }}>
                                            Tahun {{ $p->tahun }} {{ $p->status ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="peringkat" class="form-select form-select-sm select-filter select-pewarna filter-input" id="filterPeringkat" style="width: 140px; height: 32px;">
                                    <option value="">Semua Warna</option>
                                    <option value="SANGAT TINGGI" {{ request('peringkat') == 'SANGAT TINGGI' ? 'selected' : '' }}>Sangat Tinggi</option>
                                    <option value="TINGGI" {{ request('peringkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="SEDANG" {{ request('peringkat') == 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                                    <option value="RENDAH" {{ request('peringkat') == 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                                    <option value="SANGAT RENDAH" {{ request('peringkat') == 'SANGAT RENDAH' ? 'selected' : '' }}>Sangat Rendah</option>
                                </select>
                                
                                @if(in_array(auth()->user()->role_id, [1,2]))
                                <select name="unit_id" class="form-select form-select-sm select-filter filter-input" id="filterUnit" style="width: 180px; height: 32px;">
                                    <option value="">Semua Unit</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
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

        .bg-soft-danger {
            background-color: rgba(244, 67, 54, 0.1) !important;
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
            table-layout: fixed;
            width: 100%;
        }

        @media (max-width: 1199.98px) {
            .table-daftar {
                table-layout: auto;
                min-width: 1000px;
            }
        }

        .table-daftar td,
        .table-daftar th {
            padding: 0.3rem 0.2rem !important;
            word-break: break-word;
            vertical-align: middle !important;
        }

        .table-daftar th {
            white-space: normal;
            line-height: 1.2;
            font-size: 0.65rem !important;
        }

        .table-daftar td {
            overflow: visible !important;
        }

        /* Column widths - optimized for 100% width on Desktop */
        .dl-no { width: 30px; }
        .dl-kegiatan { width: 120px; }
        .dl-risiko { width: 130px; }
        .dl-sebab { width: 110px; }
        .dl-num { 
            width: 20px !important; 
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .dl-rank { width: 80px; }
        .dl-pengendalian { width: 130px; }
        .dl-rencana { width: 150px; }
        .dl-pj { width: 90px; }

        /* Custom Checkbox Design - Darker faint checkmark when unchecked, Bold when checked */
        .form-check-input.custom-chk-azra, 
        .risk-checkbox {
            width: 1.35rem !important;
            height: 1.35rem !important;
            border: 2px solid #007774 !important;
            background-color: #fff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23999999' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: 80% !important;
            transition: all 0.2s ease-in-out !important;
            margin: 0 !important;
        }

        .form-check-input.custom-chk-azra:checked,
        .risk-checkbox:checked,
        .form-check-input:checked {
            background-color: #007774 !important;
            border-color: #007774 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3.5' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
            opacity: 1 !important;
        }

        .form-check-input.custom-chk-azra:focus,
        .form-check-input:focus {
            border-color: #007774 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 119, 116, 0.25) !important;
        }

        /* Checkbox Hover Animation */
        .form-check-input.custom-chk-azra:hover {
            border-color: #005a58 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 119, 116, 0.15) !important;
            cursor: pointer;
        }

        .btn-action:hover {
            opacity: 0.8;
            transform: scale(1.1);
            transition: all 0.2s;
        }

        #tableWrapper {
            padding-bottom: 25px;
        }
        
        /* Row Hover Animation - Keep it subtle */
        .table-daftar tbody tr {
            transition: background-color 0.2s ease;
        }

        .table-daftar tbody tr:hover {
            background-color: rgba(0, 119, 116, 0.02) !important;
        }

        /* Unified Header Filter Style */
        .card-header {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
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

    // Global State for selection
    let isSelectAllGlobal = false;

    function updateSelectedCount() {
        const count = $('.risk-checkbox:checked').length;
        const total = $('#tableWrapper').data('total');
        
        if (isSelectAllGlobal) {
            $('#selectedCount').text(total);
            $('#selectAllGlobalContainer').removeClass('d-none');
            $('#selectAllText').html(`Terpilih semua <strong>${total}</strong> risiko.`);
            $('#btnSelectAllGlobal').addClass('d-none');
            $('#btnClearSelection').removeClass('d-none');
            $('#btnBulkPull').removeClass('d-none');
        } else {
            $('#selectedCount').text(count);
            if (count > 0) {
                $('#btnBulkPull').removeClass('d-none');
                
                // Show banner if all visible are checked AND there are more pages
                const visibleCount = $('.risk-checkbox').length;
                const visibleChecked = $('.risk-checkbox:checked').length;
                if (visibleChecked === visibleCount && total > visibleCount) {
                    $('#selectAllGlobalContainer').removeClass('d-none');
                    $('#selectAllText').html(`Terpilih <strong>${visibleChecked}</strong> risiko di halaman ini.`);
                    $('#btnSelectAllGlobal').removeClass('d-none');
                } else {
                    $('#selectAllGlobalContainer').addClass('d-none');
                }
            } else {
                $('#btnBulkPull').addClass('d-none');
                $('#selectAllGlobalContainer').addClass('d-none');
            }
            $('#btnSelectAllGlobal').removeClass('d-none');
            $('#btnClearSelection').addClass('d-none');
        }
    }

    // Bulk Pull Logic
    $(document).on('click', '#btnBulkPull', function() {
        const ids = isSelectAllGlobal ? [] : $('.risk-checkbox:checked').map(function() { return $(this).val(); }).get();
        const totalCount = isSelectAllGlobal ? $('#tableWrapper').data('total') : ids.length;

        if (confirm(`Tarik ${totalCount} risiko ke periode aktif?`)) {
            const btn = $(this);
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Memproses...');

            const formData = $('#filterForm').serializeArray();
            const data = {
                _token: "{{ csrf_token() }}",
                ids: ids,
                select_all: isSelectAllGlobal,
                view_periode_id: "{{ $viewPeriodeId }}"
            };

            // Add filters if select all global
            if (isSelectAllGlobal) {
                formData.forEach(item => {
                    data[item.name] = item.value;
                });
            }

            $.ajax({
                url: "{{ route('identifikasi-risiko.bulk-copy') }}",
                method: "POST",
                data: data,
                success: function(res) {
                    alert(res.message);
                    isSelectAllGlobal = false;
                    loadAjax(location.href);
                },
                error: function(err) {
                    alert('Gagal menarik data: ' + (err.responseJSON?.message || 'Terjadi kesalahan'));
                    btn.prop('disabled', false).html(originalText);
                }
            });
        }
    });

    // Checkbox Events
    $(document).on('change', '#checkAll', function() {
        $('.risk-checkbox:not(:disabled)').prop('checked', this.checked);
        isSelectAllGlobal = false;
        updateSelectedCount();
    });

    $(document).on('change', '.risk-checkbox', function() {
        if (!this.checked) {
            isSelectAllGlobal = false;
            $('#checkAll').prop('checked', false);
        }
        updateSelectedCount();
    });

    // Global Selection Events
    $(document).on('click', '#btnSelectAllGlobal', function() {
        isSelectAllGlobal = true;
        updateSelectedCount();
    });

    $(document).on('click', '#btnClearSelection', function() {
        isSelectAllGlobal = false;
        $('.risk-checkbox, #checkAll').prop('checked', false);
        updateSelectedCount();
    });

    // Single Copy Logic
    $(document).on('click', '.btn-copy-single', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        if (confirm('Tarik risiko ini ke periode aktif?')) {
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('identifikasi-risiko.bulk-copy') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: [id],
                    view_periode_id: "{{ $viewPeriodeId }}"
                },
                success: function(res) {
                    alert(res.message);
                    loadAjax(location.href);
                },
                error: function(err) {
                    alert('Gagal menarik data: ' + (err.responseJSON?.message || 'Terjadi kesalahan'));
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        }
    });

    // Re-initialize after AJAX
    $(document).ajaxComplete(function() {
        updateSelectedCount();
    });
});
</script>
@endpush



