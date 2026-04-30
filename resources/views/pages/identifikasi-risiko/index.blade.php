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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="input-group input-group-sm mb-3 mb-md-0" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa fa-search text-xs"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari kegiatan atau kode..." id="searchTable">
                    </div>
                    <a href="{{ route('identifikasi-risiko.create') }}" class="btn btn-sm bg-primary text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 px-3">
                        <i class="fa fa-plus me-2"></i> Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-bordered-light" id="mainTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kegiatan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Tujuan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kode</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kategori</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Ruang Lingkup</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Pernyataan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Sebab</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 text-center">UC/C</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Dampak</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="align-middle text-center px-1">
                                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->tujuan_kegiatan }}</p>
                                </td>
                                <td class="px-1">
                                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                                </td>
                                <td class="px-1">
                                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                                </td>
                                <td class="px-1">
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
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada identifikasi risiko yang terdaftar.</p>
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
@endsection

@push('js')
<script>
    // Logic for live search already in global layout
</script>
@endpush
