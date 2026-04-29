@extends('layouts.app')

@section('title', 'Manajemen User - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Manajemen User')
@section('page_description', 'Kelola data pengguna, perawat, dan staf sistem dalam satu dashboard.')

@section('content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card card-stats">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Total User / Karyawan</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['total'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-users opacity-10" aria-hidden="true"></i>
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
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Aktif</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['aktif'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-user-check opacity-10" aria-hidden="true"></i>
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
                            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Non-Aktif</p>
                            <h4 class="font-weight-bolder mb-0 text-strong">
                                {{ $stats['non_aktif'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-shape-premium ms-auto">
                            <i class="fa fa-user-slash opacity-10" aria-hidden="true"></i>
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
                <h6 class="mb-0 text-white font-weight-bold">Tambah User Baru</h6>
            </div>
            <div class="card-body p-3">
                <form id="formTambahUser">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Lengkap</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-user text-xs"></i></span>
                            <input type="text" name="nama_lengkap" class="form-control border-radius-md ps-2" placeholder="Nama Lengkap" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Username</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-at text-xs"></i></span>
                            <input type="text" name="username" class="form-control border-radius-md ps-2" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Password</label>
                        <div class="input-group input-group-sm position-relative">
                            <span class="input-group-text"><i class="fa fa-key text-xs"></i></span>
                            <input type="password" name="password" class="form-control ps-2 pe-5 password-field" placeholder="******" required>
                            <span class="toggle-password position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer" style="z-index: 10;">
                                <i class="fa fa-eye text-xs text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Konfirmasi Password</label>
                        <div class="input-group input-group-sm position-relative">
                            <span class="input-group-text"><i class="fa fa-user-lock text-xs"></i></span>
                            <input type="password" name="password_confirmation" class="form-control ps-2 pe-5 password-field" placeholder="******" required>
                            <span class="toggle-password position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer" style="z-index: 10;">
                                <i class="fa fa-eye text-xs text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">NIP (Nomor Induk Pegawai)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-id-card text-xs"></i></span>
                            <input type="text" name="nip" class="form-control border-radius-md ps-2" placeholder="Masukkan NIP">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Pilih Role</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-user-tag text-xs"></i></span>
                            <select name="role_id" class="form-select select-search border-radius-md ps-2" required data-placeholder="Pilih Role...">
                                <option value=""></option>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Penempatan Unit</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-building text-xs"></i></span>
                            <select name="unit_id" class="form-select select-search border-radius-md ps-2" required data-placeholder="Pilih Unit...">
                                <option value=""></option>
                                @foreach(\App\Models\Unit::all() as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Profesi</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-stethoscope text-xs"></i></span>
                            <select name="profesi" class="form-select select-search border-radius-md ps-2" data-placeholder="Pilih Profesi...">
                                <option value=""></option>
                                <option value="Medis">Medis</option>
                                <option value="Non Medis">Non Medis</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Status Akun</label>
                        <div class="status-toggle-group">
                            <div class="status-toggle-item">
                                <input type="radio" name="status_user" id="user_aktif" value="aktif" checked>
                                <label for="user_aktif" class="status-toggle-label">Aktif</label>
                            </div>
                            <div class="status-toggle-item">
                                <input type="radio" name="status_user" id="user_nonaktif" value="non-aktif">
                                <label for="user_nonaktif" class="status-toggle-label">Non-Aktif</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white">
                        <i class="fa fa-save me-2"></i> Simpan User
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
                        <h6 class="mb-0 font-weight-bold text-sm">Daftar Data User</h6>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm w-100 w-lg-70 ms-auto">
                            <span class="input-group-text"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control ps-2" placeholder="Cari user..." id="searchTable">
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role & Unit</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Profesi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIP</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->nama_lengkap }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-wrap" style="max-width: 200px;">{{ $user->role->nama_role ?? 'No Role' }}</p>
                                    <p class="text-xs text-secondary mb-0 text-wrap" style="max-width: 150px;">{{ $user->unit->nama_unit ?? 'No Unit' }}</p>
                                </td>
                                <td>
                                    <span class="text-xs font-weight-bold">{{ $user->profesi ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm {{ $user->status_user == 'aktif' ? 'bg-gradient-primary' : 'bg-gradient-secondary' }}">
                                        {{ ucfirst($user->status_user) }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->nip ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="javascript:;" class="btn-action btn-edit me-2" data-bs-toggle="tooltip" title="Edit user">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action btn-delete" data-bs-toggle="tooltip" title="Hapus user">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada user yang terdaftar dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('formTambahUser').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Fitur AJAX Simpan User akan diimplementasikan pada langkah berikutnya!');
    });
</script>
@endpush
