@extends('layouts.app')

@section('title', (isset($item) ? 'Edit' : 'Tambah') . ' Kriteria Dampak - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', (isset($item) ? 'Edit' : 'Tambah') . ' Kriteria Dampak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">{{ isset($item) ? 'Perbarui' : 'Tambah' }} Matriks Kriteria Dampak</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ isset($item) ? route('dampak.update', $item->id) : route('dampak.store') }}" method="POST">
                    @csrf
                    @if(isset($item))
                        @method('PUT')
                    @endif

                    <div class="table-responsive">
                        <table class="table mb-0 table-form">
                            <tbody>
                                <!-- Bagian I: Parameter Utama -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">I. Parameter Utama</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Level Dampak</label></td>
                                    <td class="input-cell text-start">
                                        <input type="text" name="nama_dampak" class="form-control" value="{{ $item->nama_dampak ?? '' }}" placeholder="Contoh: Sangat Rendah, Rendah, Sedang, Tinggi, Sangat Tinggi" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Skor Dampak (1-5)</label></td>
                                    <td class="input-cell text-start">
                                        <div style="max-width: 150px;">
                                            <input type="number" name="nilai_dampak" class="form-control" min="1" max="5" value="{{ $item->nilai_dampak ?? '' }}" placeholder="1-5" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Warna Visual</label></td>
                                    <td class="input-cell text-start">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" name="warna" class="form-control p-1" value="{{ $item->warna ?? '#5e72e4' }}" style="width: 50px; height: 40px !important;">
                                            <small class="text-xxs text-muted">Warna representatif pada matriks heatmap.</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Area Dampak</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="area_dampak" class="form-control" rows="4" placeholder="Deskripsi umum area dampak pada level ini (pisahkan per baris)..." style="resize: none;">{{ $item->area_dampak ?? '' }}</textarea>
                                        <div class="alert bg-gray-100 border-0 mt-2 mb-0 p-2 shadow-none">
                                            <p class="text-xxs text-dark mb-0">
                                                <i class="fa fa-lightbulb me-1 text-primary"></i> Contoh: "Tidak berdampak pada pencapaian tujuan instansi. Agak mengganggu pelayanan. Kerugian kurang material."
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Bagian II: Kriteria Detail Per Aspek -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">II. Kriteria Detail Per Aspek</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Cidera Pasien</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="cidera_pasien" class="form-control" rows="2" placeholder="Contoh: Tidak cidera / Dapat diatasi dengan pertolongan pertama..." style="resize: none;">{{ $item->cidera_pasien ?? '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Pelayanan / Operasional</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="pelayanan_operasional" class="form-control" rows="2" placeholder="Contoh: Terhenti lebih dari 1 jam / 8 jam / 1 hari..." style="resize: none;">{{ $item->pelayanan_operasional ?? '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Biaya / Keuangan</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="biaya_keuangan" class="form-control" rows="2" placeholder="Contoh: Kerugian kecil / Kerugian lebih dari 0,1% anggaran..." style="resize: none;">{{ $item->biaya_keuangan ?? '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Publikasi</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="publikasi" class="form-control" rows="2" placeholder="Contoh: Rumor / Media lokal, waktu singkat / Media nasional..." style="resize: none;">{{ $item->publikasi ?? '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Reputasi</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="reputasi" class="form-control" rows="2" placeholder="Contoh: Rumor / Dampak kecil terhadap moril karyawan..." style="resize: none;">{{ $item->reputasi ?? '' }}</textarea>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('dampak.index') }}" class="btn btn-secondary mb-0 border-radius-lg px-4">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold shadow-sm">
                            <i class="fa fa-save me-2"></i> {{ isset($item) ? 'Perbarui Kriteria' : 'Simpan Kriteria' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .table-form td, .table-form th {
        border-bottom: 1px solid #ebedf2 !important;
        white-space: normal !important;
        vertical-align: middle;
    }
    .label-cell {
        width: 30%;
        background-color: #f8f9fa;
        padding: 1.25rem 1.5rem !important;
        color: #344767;
        font-weight: 700;
        font-size: 0.8rem;
        border-right: 1px solid #ebedf2 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .input-cell {
        padding: 1.25rem 1.5rem !important;
        background-color: #ffffff;
    }

    .form-control {
        border: 1px solid #d2d6da !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #007774 !important;
        box-shadow: 0 0 0 2px rgba(0,119,116,0.05) !important;
    }
</style>
@endsection
