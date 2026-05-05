@extends('layouts.app')

@section('title', 'Evaluasi Residu - Risk Register')
@section('breadcrumb', 'Evaluasi Risiko')
@section('page_title', 'Evaluasi Risiko Residu')
@section('page_description', 'Masukkan skor probabilitas dan dampak setelah mitigasi dijalankan.')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">Formulir Evaluasi Residu</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('evaluasi-risiko.store', $identifikasi->id) }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table mb-0 table-form border-bottom">
                            <tbody>
                                <!-- Data Referensial (Analisis) -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">I. Data Penilaian (Awal)</h6>
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
                                    <td class="label-cell"><label class="mb-0">Skor Awal</label></td>
                                    <td class="input-cell text-start">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-sm me-2" style="background-color: {{ $identifikasi->analisis->skor_risiko >= 20 ? '#dc3545' : ($identifikasi->analisis->skor_risiko >= 13 ? '#fd7e14' : ($identifikasi->analisis->skor_risiko >= 5 ? '#ffc107' : '#198754')) }}">
                                                {{ $identifikasi->analisis->skor_risiko }}
                                            </span>
                                            <span class="text-xs font-weight-bold text-dark">{{ ucfirst(strtolower($identifikasi->analisis->peringkat_risiko)) }}</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Penilaian Residu -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">II. Penilaian Risiko (Residu)</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Probabilitas Residu <span class="text-danger">*</span></label></td>
                                    <td class="input-cell text-start">
                                        <select name="probabilitas_residu_id" id="probabilitas_residu_id" class="form-select @error('probabilitas_residu_id') is-invalid @enderror">
                                            <option value="">Pilih Skala Probabilitas</option>
                                            @foreach($probabilitas as $p)
                                                <option value="{{ $p->id }}" data-score="{{ $p->nilai_probabilitas }}" {{ (old('probabilitas_residu_id', $identifikasi->evaluasi->probabilitas_residu_id ?? '')) == $p->id ? 'selected' : '' }}>
                                                    {{ $p->skala_probabilitas }} ({{ $p->nilai_probabilitas }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('probabilitas_residu_id')<div class="invalid-feedback text-xxs">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Dampak Residu <span class="text-danger">*</span></label></td>
                                    <td class="input-cell text-start">
                                        <select name="dampak_residu_id" id="dampak_residu_id" class="form-select @error('dampak_residu_id') is-invalid @enderror">
                                            <option value="">Pilih Skala Dampak</option>
                                            @foreach($dampak as $d)
                                                <option value="{{ $d->id }}" data-score="{{ $d->nilai_dampak }}" {{ (old('dampak_residu_id', $identifikasi->evaluasi->dampak_residu_id ?? '')) == $d->id ? 'selected' : '' }}>
                                                    {{ $d->skala_dampak }} ({{ $d->nilai_dampak }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dampak_residu_id')<div class="invalid-feedback text-xxs">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell" style="background-color: #e9ecef;"><label class="mb-0">Hasil Evaluasi</label></td>
                                    <td class="input-cell text-start">
                                        <div class="bg-gray-100 p-3 border-radius-md">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="text-xxs font-weight-bold text-uppercase text-secondary mb-1">Skor Residu | Peringkat | Penurunan</div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span id="displayScore" class="badge bg-dark">-</span>
                                                        <span id="displayRank" class="text-xs font-weight-bold">-</span>
                                                        <span class="text-secondary text-xs mx-1">|</span>
                                                        <span id="displayReduction" class="text-success font-weight-bold text-xs">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('evaluasi-risiko.index') }}" class="btn btn-secondary mb-0 border-radius-lg">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold">
                             Simpan Evaluasi
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

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const probSelect = document.getElementById('probabilitas_residu_id');
        const dampakSelect = document.getElementById('dampak_residu_id');
        const initialScore = {{ $identifikasi->analisis->skor_risiko }};

        function calculate() {
            const prob = probSelect.options[probSelect.selectedIndex].dataset.score;
            const dam = dampakSelect.options[dampakSelect.selectedIndex].dataset.score;
            
            if (prob && dam) {
                const score = parseInt(prob) * parseInt(dam);
                document.getElementById('displayScore').innerText = score;
                
                let rank = 'Rendah';
                let color = '#344767';
                if (score >= 20) { rank = 'Sangat Tinggi'; color = '#dc3545'; }
                else if (score >= 13) { rank = 'Tinggi'; color = '#fd7e14'; }
                else if (score >= 5) { rank = 'Sedang'; color = '#ffc107'; }
                
                document.getElementById('displayRank').innerText = rank;
                document.getElementById('displayRank').style.color = color;

                const reduction = initialScore > 0 ? ((initialScore - score) / initialScore * 100).toFixed(0) : 0;
                document.getElementById('displayReduction').innerText = '↓ ' + reduction + '% Penurunan';
            }
        }

        probSelect.addEventListener('change', calculate);
        dampakSelect.addEventListener('change', calculate);
        calculate();
    });
</script>
@endpush
@endsection
