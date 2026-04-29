@extends('layouts.app')

@section('title', 'Manajemen Unit - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Manajemen Unit')
@section('page_description', 'Kelola struktur organisasi, departemen, dan unit pelayanan rumah sakit.')

@section('content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card card-stats">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Total Unit</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['total'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-building opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card card-stats success">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Unit Aktif</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['aktif'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-check opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card card-stats danger">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Unit Non-Aktif</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['non_aktif'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-times opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 border-0 shadow-sm border-radius-lg">
            <div class="card-header bg-primary py-3 text-center">
                <h6 class="mb-0 text-white font-weight-bold">Tambah Unit Baru</h6>
            </div>
            <div class="card-body p-3">
                <form id="formTambahUnit">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Kode Unit (Otomatis)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag text-xs"></i></span>
                            <input type="text" name="kode_unit" class="form-control form-control-sm border-radius-md ps-2 bg-light font-weight-bold" value="{{ $nextCode }}" readonly>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Unit</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-building text-xs"></i></span>
                            <input type="text" name="nama_unit" class="form-control form-control-sm border-radius-md ps-2" placeholder="Nama Unit" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Deskripsi</label>
                        <textarea name="deskripsi_unit" class="form-control form-control-sm border-radius-md" rows="2" placeholder="Deskripsi Unit..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Status Keaktifan</label>
                        <div class="status-toggle-group">
                            <div class="status-toggle-item">
                                <input type="radio" name="status_unit" id="unit_aktif" value="aktif" checked>
                                <label for="unit_aktif" class="status-toggle-label">Aktif</label>
                            </div>
                            <div class="status-toggle-item">
                                <input type="radio" name="status_unit" id="unit_nonaktif" value="non-aktif">
                                <label for="unit_nonaktif" class="status-toggle-label">Non-Aktif</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white">
                        <i class="fa fa-save me-2"></i> Simpan Unit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-header pb-0 p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h6 class="mb-0 font-weight-bold text-sm">Daftar Unit Kerja</h6>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm w-100 w-lg-70 ms-auto">
                            <span class="input-group-text"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control ps-2" placeholder="Cari unit..." id="searchTable">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $unit->nama_unit }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $unit->kode_unit }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-wrap" style="max-width: 200px;">{{ $unit->deskripsi_unit ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm {{ $unit->status_unit == 'aktif' ? 'bg-gradient-primary' : 'bg-gradient-secondary' }}">
                                        {{ ucfirst($unit->status_unit) }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="javascript:;" class="btn-action btn-edit me-2" data-bs-toggle="tooltip" title="Edit unit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action btn-delete" data-bs-toggle="tooltip" title="Hapus unit">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada unit kerja yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <div class="d-flex justify-content-center">
                        {{ $units->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('formTambahUnit').addEventListener('submit', function(e) {
        e.preventDefault();
        // Disini nanti logic AJAX Simpan
        alert('Fitur AJAX Simpan Unit akan diimplementasikan pada langkah berikutnya!');
    });
</script>
@endpush
