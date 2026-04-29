@extends('layouts.app')

@section('title', 'Identifikasi Risiko - Risk Register')
@section('breadcrumb', 'Menu Utama')
@section('page_title', 'Identifikasi Risiko')
@section('page_description', 'Langkah Pertama: Identifikasi kegiatan, tujuan, dan potensi risiko yang mungkin terjadi.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-header pb-0 p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h6 class="mb-0 font-weight-bold text-sm">Daftar Identifikasi Risiko</h6>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('identifikasi-risiko.create') }}" class="btn btn-sm bg-primary text-white mb-0 border-radius-lg shadow-sm">
                            <i class="fa fa-plus me-2"></i> Tambah Identifikasi
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 ms-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control ps-2" placeholder="Cari kegiatan atau kode..." id="searchTable">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit Kerja</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kegiatan & Kode</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori & Lingkup</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pernyataan Risiko</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->unit->nama_unit ?? '-' }}</p>
                                    <p class="text-xxs text-secondary mb-0">{{ $item->unit->kode_unit ?? '' }}</p>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-sm font-weight-bold">{{ Str::limit($item->kegiatan, 40) }}</h6>
                                        <span class="text-xs text-primary font-weight-bold">{{ $item->kode_risiko }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-info mb-1">{{ $item->kategori->nama_kategori ?? '-' }}</span><br>
                                    <span class="text-xxs text-secondary">{{ $item->ruangLingkup->nama_ruang_lingkup ?? '-' }}</span>
                                </td>
                                <td>
                                    <p class="text-xs mb-0 text-wrap" style="max-width: 250px;">{{ Str::limit($item->pernyataan_risiko, 100) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ route('identifikasi-risiko.edit', $item->id) }}" class="btn-action btn-edit me-2" title="Edit Data">
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
                                <td colspan="6" class="text-center py-6">
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
                <div class="card-footer py-4">
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                    </div>
                </div>
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
