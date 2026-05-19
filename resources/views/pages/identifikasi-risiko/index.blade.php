@extends('layouts.app')

@section('title', 'Identifikasi Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Identifikasi Risiko')
@section('page_description', 'Langkah Pertama: Identifikasi kegiatan, tujuan, dan potensi risiko yang mungkin terjadi.')

@section('content')
    <div class="d-flex justify-content-end gap-2" style="margin-top: -10px;">
        <a href="{{ route('pdf.identifikasi-risiko.all', request()->query()) }}" id="btnExportPdf"
            class="btn btn-sm bg-white text-dark shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3 border">
            <i class="fa fa-file-pdf me-2 text-info"></i> Cetak PDF
        </a>
        @if ($activePeriode)
            <a href="{{ route('identifikasi-risiko.create') }}" id="btnTambahData"
                class="btn btn-sm text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3"
                style="background-color: #007774 !important;">
                <i class="fa fa-plus me-2"></i> Tambah Data
            </a>
        @endif
    </div>

    <div class="row mb-3">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center">
                {{-- Library Mode badge removed as this page is now strictly for the active period --}}
            </div>

        </div>
    </div>

    <style>
        .text-teal { color: #007774 !important; }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3 border-radius-lg shadow-sm">
                <div class="card-header pb-0 py-2 px-3">
                    <form action="{{ route('identifikasi-risiko.index') }}" method="GET" id="filterForm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div>
                                <div class="input-group input-group-sm mb-0" style="width: 220px;">
                                    <span class="input-group-text bg-transparent border-end-0"><i
                                            class="fa fa-search text-xs"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                                        placeholder="Cari..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                {{-- Year Filter Removed: Managing Active Period only --}}
                                <input type="hidden" name="periode_id" value="{{ $activePeriode->id ?? '' }}">

                                {{-- Triwulan Filter removed as this page manages annual identification --}}
                                
                                @if (in_array(auth()->user()->role_id, [1, 2]))
                                    <select name="unit_id" id="filterUnit" class="form-select form-select-sm" style="width: 180px; height: 32px;">
                                        <option value="">Semua Unit</option>
                                        @foreach ($units as $u)
                                            <option value="{{ $u->id }}"
                                                {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <!-- Select All Global Banner -->
                        <div id="selectAllGlobalContainer" class="alert alert-info py-2 px-3 mb-2 mt-2 border-radius-lg d-none" style="background-color: rgba(0, 119, 116, 0.05); border: 1px solid rgba(0, 119, 116, 0.1);">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-xs text-dark" id="selectAllText"></span>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btnSelectAllGlobal" class="btn btn-xs bg-white text-teal mb-0 border-radius-sm shadow-sm font-weight-bold">
                                        Pilih Semua Risiko di Semua Halaman
                                    </button>
                                    <button type="button" id="btnClearSelection" class="btn btn-xs bg-white text-danger mb-0 border-radius-sm shadow-sm font-weight-bold d-none">
                                        Bersihkan Seleksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                    @include('pages.identifikasi-risiko._table')
                </div>
            </div>
        </div>
    </div>
    <style>
        .bg-soft-info {
            background-color: rgba(33, 150, 243, 0.1) !important;
        }

        .bg-soft-danger {
            background-color: rgba(244, 67, 54, 0.1) !important;
        }

        .bg-soft-teal {
            background-color: rgba(0, 119, 116, 0.08) !important;
        }

        .bg-light-soft {
            background-color: #f8f9fa !important;
        }

        /* Rapi Checkbox Style - Solid Hijau Azra */
        .form-check-input {
            width: 1.15rem !important;
            height: 1.15rem !important;
            border: 2px solid #007774 !important;
            border-radius: 4px !important;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 !important;
            background-color: #fff;
            box-shadow: none !important;
        }

        .form-check-input:disabled {
            background-color: #e9ecef !important;
            border-color: #d2d6da !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        .form-check-input:checked {
            background-color: #007774 !important;
            /* Hijau Azra Solid Fill */
            border-color: #007774 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
        }

        .form-check-input:hover {
            background-color: rgba(0, 119, 116, 0.05);
        }

        .form-check-input:focus {
            border-color: #007774 !important;
            box-shadow: 0 0 0 3px rgba(0, 119, 116, 0.1) !important;
        }

        .form-check {
            min-height: auto !important;
            padding-left: 0 !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #mainTable td,
        #mainTable th {
            vertical-align: middle !important;
        }
    </style>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            const filterForm = $('#filterForm');

            let xhr;
            function reloadTable(url) {
                if (xhr) xhr.abort();

                const fetchUrl = url || filterForm.attr('action') + '?' + filterForm.serialize();
                
                // Update PDF link immediately
                const params = fetchUrl.split('?')[1] || '';
                const pdfUrl = "{{ route('pdf.identifikasi-risiko.all') }}?" + params;
                $('#btnExportPdf').attr('href', pdfUrl);

                // Show loading state (subtle)
                $('#tableContainer').css('opacity', '0.8');

                xhr = $.ajax({
                    url: fetchUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#tableContainer').html(response);
                        $('#tableContainer').css('opacity', '1');

                        // Update URL
                        window.history.pushState(null, '', fetchUrl);
                    },
                    error: function(err) {
                        if (err.statusText !== 'abort') {
                            $('#tableContainer').css('opacity', '1');
                            console.error(err);
                        }
                    }
                });
            }

            // --- AJAX Pagination ---
            $(document).on('click', '#tableContainer .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url && url !== '#') {
                    reloadTable(url);
                }
            });

            // --- Search Bar AJAX (with debounce) ---
            let searchTimer;
            $('input[name="search"]').on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    reloadTable();
                }, 500);
            });

            // --- Tom Select for Unit Filter ---
            if (document.getElementById('filterUnit')) {
                new TomSelect('#filterUnit', {
                    create: false,
                    allowEmptyOption: true,
                    onChange: function() {
                        reloadTable();
                    }
                });
            }

            // --- Triwulan Filter ---
            $('select[name="triwulan"]').on('change', function() {
                reloadTable();
            });
            // --- Individual Copy ---
            $(document).on('click', '.btn-copy-risk', function() {
                const btn = $(this);
                const id = btn.data('id');
                const originalText = btn.html();

                if (!confirm('Tarik risiko ini ke periode sekarang?')) return;

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('identifikasi-risiko.copy') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        risk_id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            btn.prop('disabled', false).html(originalText);
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(originalText);
                        const msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan sistem.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });

            let selectAllGlobal = false;

            // --- Bulk Selection ---
            $(document).on('change', '#checkAll', function() {
                const isChecked = $(this).prop('checked');
                const checkedCheckboxes = $('.risk-checkbox:not(:disabled)').prop('checked', isChecked);
                const actualCheckedCount = isChecked ? checkedCheckboxes.length : 0;

                const tableWrapper = $('#tableWrapper');
                if (tableWrapper.length) {
                    const total = tableWrapper.data('total');
                    const count = tableWrapper.data('count');

                    if (isChecked && total > count) {
                        $('#selectAllText').html('Terpilih <strong>' + actualCheckedCount +
                            '</strong> risiko di halaman ini.');
                        $('#selectAllGlobalContainer').hide().removeClass('d-none').fadeIn();
                    } else {
                        $('#selectAllGlobalContainer').fadeOut(function() {
                            $(this).addClass('d-none');
                        });
                        selectAllGlobal = false;
                    }
                }
                updateBulkBtn();
            });

            $(document).on('click', '#btnSelectAllGlobal', function() {
                selectAllGlobal = true;
                const total = $('#tableWrapper').data('total');
                $('#selectAllText').html('<i class="fa fa-check-circle me-1"></i> Seluruh <strong>' +
                    total + '</strong> risiko terpilih.');
                $(this).addClass('d-none');
                $('#btnClearSelection').removeClass('d-none');
                updateBulkBtn();
            });

            $(document).on('click', '#btnClearSelection', function() {
                selectAllGlobal = false;
                $('#checkAll').prop('checked', false).trigger('change');
            });

            $(document).on('change', '.risk-checkbox', function() {
                updateBulkBtn();
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                    selectAllGlobal = false;
                    $('#selectAllGlobalContainer').fadeOut(function() {
                        $(this).addClass('d-none');
                    });
                }
            });

            function updateBulkBtn() {
                const count = selectAllGlobal ? $('#tableWrapper').data('total') : $('.risk-checkbox:checked')
                    .length;
                $('#selectedCount').text(count);
                if (count > 0) {
                    $('#btnBulkCopy').removeClass('d-none');
                } else {
                    $('#btnBulkCopy').addClass('d-none');
                }
            }

            // --- Bulk Pull Execution ---
            $('#btnBulkCopy').on('click', function() {
                const ids = [];
                if (!selectAllGlobal) {
                    $('.risk-checkbox:checked').each(function() {
                        ids.push($(this).val());
                    });
                }

                const displayCount = selectAllGlobal ? $('#tableWrapper').data('total') : ids.length;

                Swal.fire({
                    title: 'Tarik Massal',
                    text: `Tarik ${displayCount} risiko terpilih ke periode aktif?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tarik!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = $(this);
                        btn.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin me-2"></i> Memproses...');

                        const postData = {
                            _token: "{{ csrf_token() }}",
                            select_all: selectAllGlobal,
                            view_periode_id: $('select[name="periode_id"]').val(),
                            triwulan: $('select[name="triwulan"]').val(),
                            unit_id: $('select[name="unit_id"]').val(),
                            search: $('input[name="search"]').val()
                        };

                        if (!selectAllGlobal) {
                            postData.ids = ids;
                        }

                        $.ajax({
                            url: "{{ route('identifikasi-risiko.bulk-copy') }}",
                            method: "POST",
                            data: postData,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil', response.message, 'success')
                                        .then(() => {
                                            window.location.reload();
                                        });
                                } else {
                                    btn.prop('disabled', false).html(
                                        '<i class="fa fa-copy me-2"></i> Tarik Terpilih'
                                    );
                                    Swal.fire('Gagal', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                btn.prop('disabled', false).html(
                                    '<i class="fa fa-copy me-2"></i> Tarik Terpilih'
                                );
                                Swal.fire('Error', 'Terjadi kesalahan sistem.',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
