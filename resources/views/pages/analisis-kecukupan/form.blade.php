@extends('layouts.app')

@section('title', 'Input Analisis Kecukupan - Risk Register')
@section('breadcrumb', 'Analisis Kecukupan')
@section('page_title', 'Analisis Kecukupan Pengendalian')
@section('page_description', 'Berikan rencana tindakan tambahan untuk menanggulangi risiko yang telah dianalisis.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">Formulir Rencana Tindak Lanjut</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('analisis-kecukupan.update', $identifikasi->id) }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table mb-0 table-form border-bottom">
                            <tbody>
                                <!-- Data Referensial (Analisis) -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">I. Data Analisis (Referensial)</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Pernyataan Risiko</label></td>
                                    <td class="input-cell text-start">
                                        <div class="mb-1 text-xs"><strong>Kode:</strong> <span class="text-primary font-weight-bold">{{ $identifikasi->kode_risiko }}</span></div>
                                        <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $identifikasi->kegiatan }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Peringkat Risiko</label></td>
                                    <td class="input-cell text-start">
                                        @php
                                            $score = $identifikasi->analisis->skor_risiko;
                                            $bgColor = $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')));
                                            $textColor = ($score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                                            
                                            if ($score >= 15) $rankTitle = 'Sangat Tinggi';
                                            elseif ($score >= 10) $rankTitle = 'Tinggi';
                                            elseif ($score >= 5) $rankTitle = 'Sedang';
                                            elseif ($score >= 3) $rankTitle = 'Rendah';
                                            else $rankTitle = 'Sangat Rendah';
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-sm me-2 {{ $textColor }}" style="background-color: {{ $bgColor }}">
                                                {{ $score }}
                                            </span>
                                            <span class="text-xs font-weight-bold text-dark">{{ $rankTitle }}</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Rencana Tindak Lanjut -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">II. Rencana Tindak Lanjut</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Uraian Rencana <span class="text-danger">*</span></label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="uraian_rencana" class="form-control" rows="4" placeholder="Jabarkan rencana tindakan yang akan dilakukan..." required style="resize: none;">{{ old('uraian_rencana', $identifikasi->analisisKecukupan->uraian_rencana ?? '') }}</textarea>
                                        @error('uraian_rencana')<p class="text-danger text-xxs mt-1">{{ $message }}</p>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Jadwal Pelaksanaan <span class="text-danger">*</span></label></td>
                                    <td class="input-cell text-start">
                                        <input type="text" name="jadwal" class="form-control" placeholder="Contoh: Setiap bulan, Tentative, dsb." value="{{ old('jadwal', $identifikasi->analisisKecukupan->jadwal ?? '') }}" required>
                                        @error('jadwal')<p class="text-danger text-xxs mt-1">{{ $message }}</p>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">PJ Tindak Lanjut <span class="text-danger">*</span></label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="pj_tindak_lanjut" class="form-control" rows="1" placeholder="Sebutkan unit atau personil penanggung jawab..." required style="resize: none;">{{ old('pj_tindak_lanjut', $identifikasi->analisisKecukupan->pj_tindak_lanjut ?? '') }}</textarea>
                                        @error('pj_tindak_lanjut')<p class="text-danger text-xxs mt-1">{{ $message }}</p>@enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('analisis-kecukupan.index') }}" class="btn btn-secondary mb-0 border-radius-lg">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold">
                             Simpan Rencana
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
        padding: 1rem 1.5rem !important;
        background-color: #ffffff;
    }
</style>
@endsection

@push('js')
<script>
    document.querySelectorAll('.tom-select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            dropdownParent: 'body',
        });
    });
</script>
@endpush



