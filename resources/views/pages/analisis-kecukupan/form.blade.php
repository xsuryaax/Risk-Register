@extends('layouts.app')

@section('title', 'Input Analisis Kecukupan - Risk Register')
@section('breadcrumb', 'Analisis Kecukupan')
@section('page_title', 'Analisis Kecukupan Pengendalian')
@section('page_description', 'Berikan rencana tindakan tambahan untuk menanggulangi risiko yang telah dianalisis.')

@section('content')
<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card shadow-lg">
            <div class="card-header pb-0 text-left bg-white">
                <h4 class="font-weight-bolder text-info text-gradient">Analisis Kecukupan</h4>
                <p class="mb-0 text-sm">Isi detail rencana tindakan kecukupan dan penanggung jawab untuk risiko ini.</p>
            </div>
            <div class="card-body">
                <!-- Summary Table (Auto-filled Info) -->
                <div class="table-responsive p-0 mb-4">
                    <table class="table table-bordered align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 py-1">Kode</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 py-1">Pernyataan Risiko</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 py-1 text-center">Peringkat</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 py-1">Pemilik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-2 py-2">
                                    <span class="text-xs font-weight-bold text-primary">{{ $identifikasi->kode_risiko }}</span>
                                </td>
                                <td class="px-2 py-2">
                                    <p class="text-xs mb-0 text-wrap font-weight-bold">{{ $identifikasi->kegiatan }}</p>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge badge-sm" style="background-color: {{ $identifikasi->analisis->skor_risiko >= 20 ? '#dc3545' : ($identifikasi->analisis->skor_risiko >= 13 ? '#fd7e14' : ($identifikasi->analisis->skor_risiko >= 5 ? '#ffc107' : '#198754')) }}">
                                        {{ $identifikasi->analisis->peringkat_risiko }}
                                    </span>
                                </td>
                                <td class="px-2 py-2">
                                    <span class="text-xs text-dark">{{ $identifikasi->analisis->pemilik_risiko }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="horizontal dark my-4">

                <form action="{{ route('analisis-kecukupan.update', $identifikasi->id) }}" method="POST" role="form">
                    @csrf
                    <div class="row">
                        <!-- Kolom 10: Uraian Rencana -->
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold text-dark text-sm">Uraian Rencana Pengendalian <span class="text-danger">*</span></label>
                            <textarea name="uraian_rencana" class="form-control form-control-alternative" rows="4" placeholder="Jabarkan rencana tindakan yang akan dilakukan..." required>{{ old('uraian_rencana', $identifikasi->analisisKecukupan->uraian_rencana ?? '') }}</textarea>
                            @error('uraian_rencana')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Kolom 11: Jadwal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-dark text-sm">Jadwal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="text" name="jadwal" class="form-control" placeholder="Contoh: Setiap bulan, Tentative, dsb." value="{{ old('jadwal', $identifikasi->analisisKecukupan->jadwal ?? '') }}" required>
                            @error('jadwal')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Kolom 13: PJ Tindak Lanjut -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold text-dark text-sm">PJ Tindak Lanjut <span class="text-danger">*</span></label>
                            <input type="text" name="pj_tindak_lanjut" class="form-control" placeholder="Nama staf/unit penanggung jawab" value="{{ old('pj_tindak_lanjut', $identifikasi->analisisKecukupan->pj_tindak_lanjut ?? '') }}" required>
                            @error('pj_tindak_lanjut')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('analisis-kecukupan.index') }}" class="btn btn-light btn-sm mb-0">Kembali</a>
                        <button type="submit" class="btn bg-gradient-info btn-sm mb-0">Simpan Rencana</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #1171ef;
        box-shadow: 0 0 0 2px rgba(17, 113, 239, 0.1);
    }
</style>
@endsection
