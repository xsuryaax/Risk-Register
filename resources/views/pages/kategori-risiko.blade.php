@extends('layouts.app')

@section('title', 'Kategori Risiko - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Master Kategori Risiko')
@section('page_description', 'Kelola pengelompokan kategori risiko (misal: Klinis, Operasional, Finansial).')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 border-0 shadow-sm border-radius-lg">
            <div class="card-header bg-primary py-3 text-center">
                <h6 class="mb-0 text-white font-weight-bold">Tambah Kategori Baru</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('kategori-risiko.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag text-xs"></i></span>
                            <input type="text" name="nama_kategori" class="form-control form-control-sm border-radius-md ps-2" placeholder="Nama Kategori" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Keterangan</label>
                        <textarea name="keterangan" class="form-control form-control-sm border-radius-md" rows="2" placeholder="Keterangan kategori..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white">
                        <i class="fa fa-save me-2"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-body px-0 pt-3 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kategori</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan</th>
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
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $item->nama_kategori }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-wrap">{{ $item->keterangan ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center">
                                    <a href="javascript:;" class="btn-action btn-edit me-2" 
                                       data-id="{{ $item->id }}" 
                                       data-nama="{{ $item->nama_kategori }}" 
                                       data-keterangan="{{ $item->keterangan }}"
                                       onclick="editKategori(this)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kategori-risiko.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                        <p class="text-xs text-muted">Belum ada kategori risiko yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($data->hasPages())
                <div class="card-footer pb-0 pt-3">
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

<!-- Modal Edit kategori -->
<div class="modal fade" id="modalEditKategori" tabindex="-1" role="dialog" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-radius-lg shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title font-weight-bold text-white" id="modalEditKategoriLabel">Update Kategori Risiko</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <form id="formEditKategori" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control form-control-sm border-radius-md" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control form-control-sm border-radius-md" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-sm btn-link text-secondary mb-0" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm bg-primary mb-0 text-white shadow-sm border-radius-lg">
                        <i class="fa fa-save me-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    function editKategori(btn) {
        const id = $(btn).data('id');
        const nama = $(btn).data('nama');
        const keterangan = $(btn).data('keterangan');
        
        $('#edit_nama_kategori').val(nama);
        $('#edit_keterangan').val(keterangan);
        $('#formEditKategori').attr('action', `/master/kategori-risiko/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
        modal.show();
    }
</script>
@endpush
