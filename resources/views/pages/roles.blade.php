@extends('layouts.app')

@section('title', 'Manajemen Role - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Manajemen Role')
@section('page_description', 'Atur tingkatan wewenang dan hak akses user secara sistematis.')

@section('content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card card-stats">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Total Role</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['total'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-user-tag opacity-10" aria-hidden="true"></i>
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
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Role Aktif</p>
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
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Role Non-Aktif</p>
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
                <h6 class="mb-0 text-white font-weight-bold">Tambah Role Baru</h6>
            </div>
            <div class="card-body p-3">
                <form id="formTambahRole">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user-tag text-xs"></i></span>
                            <input type="text" name="nama_role" class="form-control form-control-sm border-radius-md ps-2" placeholder="Contoh: Administrator" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Deskripsi</label>
                        <textarea name="deskripsi_role" class="form-control form-control-sm border-radius-md" rows="2" placeholder="Tujuan/Wewenang Role..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Status Keaktifan</label>
                        <div class="status-toggle-group">
                            <div class="status-toggle-item">
                                <input type="radio" name="status_role" id="role_aktif" value="aktif" checked>
                                <label for="role_aktif" class="status-toggle-label">Aktif</label>
                            </div>
                            <div class="status-toggle-item">
                                <input type="radio" name="status_role" id="role_nonaktif" value="non-aktif">
                                <label for="role_nonaktif" class="status-toggle-label">Non-Aktif</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white">
                        <i class="fa fa-save me-2"></i> Simpan Role
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
                        <h6 class="mb-0 font-weight-bold text-sm">Daftar Role User</h6>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm w-100 w-lg-70 ms-auto">
                            <span class="input-group-text"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control ps-2" placeholder="Cari role..." id="searchTable">
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $role->nama_role }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $role->deskripsi_role ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="javascript:;" class="btn-action btn-edit me-2" data-bs-toggle="tooltip" title="Edit role">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action btn-delete" data-bs-toggle="tooltip" title="Hapus role">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada role yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <div class="d-flex justify-content-center">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('formTambahRole').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Fitur AJAX Simpan Role akan diimplementasikan pada langkah berikutnya!');
    });
</script>
@endpush
