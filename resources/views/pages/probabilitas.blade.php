@extends('layouts.app')

@section('title', 'Skala Probabilitas - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Master Skala Probabilitas')
@section('page_description', 'Kelola skala frekuensi atau kemungkinan terjadinya risiko (Skala 1-5).')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="alert bg-gray-100 border-0 mb-0 shadow-none py-2 px-3 flex-grow-1 me-3">
                <p class="text-xs text-dark mb-0">
                    <i class="fa fa-info-circle me-1 text-primary"></i>
                    <strong>Panduan:</strong> Skala probabilitas digunakan untuk menentukan seberapa sering risiko mungkin terjadi. Kriteria mencakup kemungkinan (persentase) & frekuensi.
                </p>
            </div>
            <a href="{{ route('probabilitas.create') }}" class="btn btn-sm bg-primary text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 text-nowrap">
                <i class="fa fa-plus me-2"></i> Tambah Data
            </a>
        </div>
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-body px-0 pt-3 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 80px;">Skala</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Level Probabilitas</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Kriteria & Frekuensi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr style="border-left: 5px solid {{ $item->warna }};">
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm font-weight-bold" style="background-color: {{ $item->warna }}; color: #fff;">{{ $item->nilai_probabilitas }}</span>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm px-3 font-weight-bold" style="color: {{ $item->warna }};">{{ $item->nama_probabilitas }}</h6>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-wrap px-3 py-2">{{ $item->keterangan ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ route('probabilitas.edit', $item->id) }}" class="btn-action btn-edit me-2" title="Edit Data">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('probabilitas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data probabilitas ini?')">
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
                                <td colspan="4" class="text-center py-6">
                                    <div class="empty-state py-4">
                                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Script for search or other functions if needed
</script>
@endpush
