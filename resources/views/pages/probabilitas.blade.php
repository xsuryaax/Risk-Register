@extends('layouts.app')

@section('title', 'Skala Probabilitas - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Master Skala Probabilitas')
@section('page_description', 'Kelola skala frekuensi atau kemungkinan terjadinya risiko (Skala 1-5).')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 border-0 shadow-sm border-radius-lg">
            <div class="card-header bg-primary py-3 text-center">
                <h6 class="mb-0 text-white font-weight-bold">Tambah Skala Probabilitas</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('probabilitas.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Probabilitas</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-info-circle text-xs"></i></span>
                            <input type="text" name="nama_probabilitas" class="form-control form-control-sm border-radius-md ps-2" placeholder="Contoh: Sangat Jarang" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nilai Skor (1-5)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-sort-numeric-up text-xs"></i></span>
                            <input type="number" name="nilai_probabilitas" class="form-control form-control-sm border-radius-md ps-2" min="1" max="5" placeholder="1-5" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Keterangan/Kriteria</label>
                        <textarea name="keterangan" class="form-control form-control-sm border-radius-md" rows="3" placeholder="Deskripsikan kriteria frekuensi..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-sm bg-primary mb-0 w-100 py-2 shadow-sm border-radius-lg text-white">
                        <i class="fa fa-save me-2"></i> Simpan Skala
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
                        <h6 class="mb-0 font-weight-bold text-sm">Daftar Skala Probabilitas</h6>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm w-100 w-lg-70 ms-auto">
                            <span class="input-group-text"><i class="fa fa-search text-xs"></i></span>
                            <input type="text" class="form-control ps-2" placeholder="Cari data..." id="searchTable">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skala</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Nama Probabilitas</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm bg-dark font-weight-bold">{{ $item->nilai_probabilitas }}</span>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm px-3">{{ $item->nama_probabilitas }}</h6>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-wrap" style="max-width: 300px;">{{ $item->keterangan ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center">
                                    <a href="javascript:;" class="btn-action btn-edit me-2"
                                       data-id="{{ $item->id }}"
                                       data-nama="{{ $item->nama_probabilitas }}"
                                       data-nilai="{{ $item->nilai_probabilitas }}"
                                       data-keterangan="{{ $item->keterangan }}"
                                       onclick="editProbabilitas(this)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('probabilitas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                        <p class="text-xs text-muted">Belum ada skala probabilitas yang terdaftar.</p>
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

<!-- Modal Edit Probabilitas -->
<div class="modal fade" id="modalEditProbabilitas" tabindex="-1" role="dialog" aria-labelledby="modalEditProbabilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-radius-lg shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title font-weight-bold text-white" id="modalEditProbabilitasLabel">Update Skala Probabilitas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <form id="formEditProbabilitas" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nama Probabilitas</label>
                        <input type="text" name="nama_probabilitas" id="edit_nama_probabilitas" class="form-control form-control-sm border-radius-md" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Nilai Skala (1-5)</label>
                        <input type="number" name="nilai_probabilitas" id="edit_nilai_probabilitas" class="form-control form-control-sm border-radius-md" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1">Kriteria/Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan_prob" class="form-control form-control-sm border-radius-md" rows="3"></textarea>
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
    function editProbabilitas(btn) {
        const id = $(btn).data('id');
        const nama = $(btn).data('nama');
        const nilai = $(btn).data('nilai');
        const keterangan = $(btn).data('keterangan');
        
        $('#edit_nama_probabilitas').val(nama);
        $('#edit_nilai_probabilitas').val(nilai);
        $('#edit_keterangan_prob').val(keterangan);
        $('#formEditProbabilitas').attr('action', `/master/probabilitas/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditProbabilitas'));
        modal.show();
    }
</script>
@endpush
