@extends('layouts.app')

@section('title', 'Skala Dampak - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Master Skala Dampak')
@section('page_description', 'Kelola skala konsekuensi atau tingkat keparahan risiko (Skala 1-5).')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="alert bg-gray-100 border-0 mb-0 shadow-none py-2 px-3 flex-grow-1 me-3">
                <p class="text-xs text-dark mb-0">
                    <i class="fa fa-info-circle me-1 text-primary"></i>
                    <strong>Panduan:</strong> Matriks ini membantu menentukan besaran konsekuensi jika risiko terjadi. Dampak dinilai dari aspek:
                    <span class="badge border border-primary text-primary bg-transparent mx-1 px-1 py-1">Cidera Pasien</span>
                    <span class="badge border border-primary text-primary bg-transparent mx-1 px-1 py-1">Pelayanan</span>
                    <span class="badge border border-primary text-primary bg-transparent mx-1 px-1 py-1">Keuangan</span>
                    <span class="badge border border-primary text-primary bg-transparent mx-1 px-1 py-1">Publikasi</span>
                    <span class="badge border border-primary text-primary bg-transparent mx-1 px-1 py-1">Reputasi</span>.
                </p>
            </div>
            <a href="{{ route('dampak.create') }}" class="btn btn-sm bg-primary text-white shadow-sm border-radius-lg mb-0 text-capitalize py-2 text-nowrap">
                <i class="fa fa-plus me-2"></i> Tambah Kriteria
            </a>
        </div>
        <div class="card mb-4 border-radius-lg shadow-sm">
            <div class="card-body px-0 pt-3 pb-2" id="tableContainer">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="mainTable">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 80px;">Skala</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3" style="width: 140px;">Level Dampak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Area Dampak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cidera Pasien</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelayanan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keuangan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Publikasi</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reputasi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr style="border-left: 5px solid {{ $item->warna }};">
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm font-weight-bold" style="background-color: {{ $item->warna }}; color: #fff;">{{ $item->nilai_dampak }}</span>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm px-3 font-weight-bold" style="color: {{ $item->warna }};">{{ $item->nama_dampak }}</h6>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2" style="min-width: 150px;">{{ $item->area_dampak ?? '-' }}</p>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2">{{ $item->cidera_pasien ?? '-' }}</p>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2">{{ $item->pelayanan_operasional ?? '-' }}</p>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2">{{ $item->biaya_keuangan ?? '-' }}</p>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2">{{ $item->publikasi ?? '-' }}</p>
                                </td>
                                <td class="white-space-normal">
                                    <p class="text-xs mb-0 text-wrap py-2">{{ $item->reputasi ?? '-' }}</p>
                                </td>

                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ route('dampak.edit', $item->id) }}" class="btn-action btn-edit me-2" title="Edit Data">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dampak.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dampak ini?')">
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
                                <td colspan="9" class="text-center py-6">
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
