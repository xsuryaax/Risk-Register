@extends('layouts.app')

@section('title', 'Manajemen Periode - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Manajemen Periode')
@section('page_description', 'Kelola rentang waktu pelaporan risiko. Hanya satu periode yang bisa aktif dalam satu waktu.')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 border-0 shadow-sm border-radius-lg">
            <div class="card-header bg-primary py-3 text-center">
                <h6 class="mb-0 text-white font-weight-bold">Tambah Tahun Baru</h6>
            </div>
            <div class="card-body p-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-white text-xs mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show text-white text-xs mb-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('periode.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Tahun Periode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-calendar text-xs"></i></span>
                            <input type="number" name="tahun" class="form-control form-control-sm border-radius-md ps-2" placeholder="Contoh: {{ date('Y') }}" min="2000" max="2100" required>
                        </div>
                        @error('tahun') <span class="text-danger text-xxs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control form-control-sm border-radius-md" rows="3" placeholder="Keterangan singkat..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white font-weight-bold">
                        <i class="fa fa-save me-2"></i> Simpan Periode
                    </button>
                    <p class="text-xxs text-muted mt-2 mb-0">
                        <i class="fa fa-info-circle me-1"></i> Periode baru akan dibuat dengan status non-aktif secara standar.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4 border-radius-lg shadow-sm overflow-hidden">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0 font-weight-bold text-sm">Daftar Tahun Periode</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tahun</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr class="{{ $item->status ? 'bg-soft-primary' : '' }}">
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $item->tahun }}</h6>
                                </td>
                                <td>
                                    <p class="text-xs text-secondary mb-0">{{ $item->keterangan ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($item->status)
                                        <span class="badge badge-sm bg-info shadow-none">
                                            <i class="fa fa-check-circle me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-sm bg-secondary opacity-6 shadow-none">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        @if(!$item->status)
                                            <form action="{{ route('periode.activate', $item->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-success mb-0 py-1 px-2 font-weight-bold" 
                                                        onclick="return confirm('Aktifkan periode tahun {{ $item->tahun }}? Semua pelaporan baru akan masuk ke tahun ini.')">
                                                    Aktifkan
                                                </button>
                                            </form>

                                            <form action="{{ route('periode.destroy', $item->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger text-gradient px-1 mb-0 py-1" onclick="return confirm('Hapus periode ini?')">
                                                    <i class="fa fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xxs font-weight-bold text-info"><i class="fa fa-lock me-1"></i> Periode Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-secondary text-xs">Data Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary {
        background-color: rgba(33, 150, 243, 0.03) !important;
    }
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.65rem;
        line-height: 1.5;
        border-radius: 0.4rem;
    }
</style>
@endsection
