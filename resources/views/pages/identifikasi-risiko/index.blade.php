@extends('layouts.app')

@section('title', 'Identifikasi Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Identifikasi Risiko')
@section('page_description', 'Langkah Pertama: Identifikasi kegiatan, tujuan, dan potensi risiko yang mungkin terjadi.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-header pb-3 p-3">
                <form action="{{ route('identifikasi-risiko.index') }}" method="GET">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="input-group input-group-sm mb-0" style="width: 220px;">
                                <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari..." value="{{ request('search') }}">
                            </div>
                            
                            <select name="periode_id" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ $viewPeriodeId == $p->id ? 'selected' : '' }}>
                                        Tahun {{ $p->tahun }} {{ $p->status ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>

                            @if(in_array(auth()->user()->role_id, [1, 2]))
                            <select name="unit_id" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="d-flex gap-2 mt-3 mt-md-0">
                            @if($activePeriode && $viewPeriodeId == $activePeriode->id)
                                <a href="{{ route('identifikasi-risiko.create') }}" class="btn btn-sm text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3" style="background-color: #007774 !important;">
                                    <i class="fa fa-plus me-2"></i> Tambah Data
                                </a>
                            @elseif($activePeriode)
                                <button type="button" id="btnBulkCopy" class="btn btn-sm text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3 d-none" style="background-color: #007774 !important;">
                                    <i class="fa fa-copy me-2"></i> Tarik Terpilih (<span id="selectedCount">0</span>)
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-bordered-light" id="mainTable">
                        <thead class="bg-light">
                            <tr>
                                @if($activePeriode && $viewPeriodeId != $activePeriode->id)
                                <th class="text-center px-1" style="width: 40px;">
                                    <div class="form-check d-flex justify-content-center p-0 m-0">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                    </div>
                                </th>
                                @endif
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kode</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kegiatan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Tujuan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kategori</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Ruang Lingkup</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Pernyataan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Sebab</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 text-center">UC/C</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Dampak</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            @php
                                $isPulled = in_array($item->kegiatan, $pulledActivities ?? []);
                            @endphp
                            <tr class="">
                                @if($activePeriode && $viewPeriodeId != $activePeriode->id)
                                <td class="align-middle text-center px-1">
                                    <div class="form-check d-flex justify-content-center p-0 m-0">
                                        <input class="form-check-input risk-checkbox" type="checkbox" value="{{ $item->id }}" 
                                               {{ $isPulled ? 'disabled' : '' }} 
                                               title="{{ $isPulled ? 'Risiko ini sudah ditarik ke periode aktif' : 'Pilih untuk ditarik' }}">
                                    </div>
                                </td>
                                @endif
                                <td class="align-middle text-center px-1">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                                </td>
                                <td class="px-1 text-wrap">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->tujuan_kegiatan }}</p>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->ruangLingkup->nama_ruang_lingkup ?? '-' }}</p>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->pernyataan_risiko }}</p>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->sebab }}</p>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <span class="text-xs font-weight-bold text-dark">{{ $item->jenis_risiko }}</span>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->dampak }}</p>
                                </td>
                                <td class="align-middle text-center px-1">
                                    <div class="d-flex justify-content-center align-items-center">
                                        @if($activePeriode && $viewPeriodeId == $activePeriode->id)
                                            <a href="{{ route('identifikasi-risiko.edit', $item->id) }}" class="btn-action btn-edit me-1" title="Edit Data">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('identifikasi-risiko.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus identifikasi risiko ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus Data">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($activePeriode)
                                            @if(!$isPulled)
                                                <button type="button" class="btn-action btn-edit btn-copy-risk" data-id="{{ $item->id }}" title="Tarik ke Tahun {{ $activePeriode->tahun }}">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            @else
                                                <span class="text-teal" title="Sudah ditarik"><i class="fa fa-check-double text-xs"></i></span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada identifikasi risiko untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($data->hasPages())
                <div class="card-footer py-3">
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
    .bg-soft-info { background-color: rgba(33, 150, 243, 0.1) !important; }
    .bg-soft-danger { background-color: rgba(244, 67, 54, 0.1) !important; }
    .bg-soft-teal { background-color: rgba(0, 119, 116, 0.08) !important; }
    .bg-light-soft { background-color: #f8f9fa !important; }

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
        background-color: #007774 !important; /* Hijau Azra Solid Fill */
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
    #mainTable td, #mainTable th {
        vertical-align: middle !important;
    }
</style>
@endsection

@push('js')
<script>
    $(document).ready(function() {
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
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // --- Bulk Selection ---
        $('#checkAll').on('change', function() {
            $('.risk-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkBtn();
        });

        $(document).on('change', '.risk-checkbox', function() {
            updateBulkBtn();
            if(!$(this).prop('checked')) $('#checkAll').prop('checked', false);
        });

        function updateBulkBtn() {
            const count = $('.risk-checkbox:checked').length;
            $('#selectedCount').text(count);
            if(count > 0) {
                $('#btnBulkCopy').removeClass('d-none');
            } else {
                $('#btnBulkCopy').addClass('d-none');
            }
        }

        // --- Bulk Pull Execution ---
        $('#btnBulkCopy').on('click', function() {
            const ids = [];
            $('.risk-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            Swal.fire({
                title: 'Tarik Massal',
                text: `Tarik ${ids.length} risiko terpilih ke periode aktif?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tarik!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = $(this);
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Memproses...');

                    $.ajax({
                        url: "{{ route('identifikasi-risiko.bulk-copy') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil', response.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                btn.prop('disabled', false).html('<i class="fa fa-copy me-2"></i> Tarik Terpilih');
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('<i class="fa fa-copy me-2"></i> Tarik Terpilih');
                            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush


