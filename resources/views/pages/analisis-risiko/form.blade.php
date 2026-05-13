@extends('layouts.app')

@section('title', 'Form Analisis Risiko - Risk Register')
@section('breadcrumb', 'Analisis Risiko')
@section('page_title', 'Form Analisis Risiko')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">Evaluasi & Analisis Risiko</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('analisis-risiko.store', $identifikasi->id) }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table mb-0 table-form border-bottom">
                            <tbody>
                                <!-- Data Identifikasi (Read Only) -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">I. Data Identifikasi (Referensial)</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Kegiatan & Tujuan</label></td>
                                    <td class="input-cell text-start">
                                        <div class="mb-1"><strong>Kegiatan:</strong> {{ $identifikasi->kegiatan }}</div>
                                        <div><strong>Tujuan:</strong> {{ $identifikasi->tujuan_kegiatan }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Pernyataan & Sebab</label></td>
                                    <td class="input-cell text-start">
                                        <div class="mb-1"><strong>Risiko:</strong> {{ $identifikasi->pernyataan_risiko }}</div>
                                        <div><strong>Sebab:</strong> {{ $identifikasi->sebab }} ({{ $identifikasi->jenis_risiko }})</div>
                                    </td>
                                </tr>

                                <!-- Bagian Pengendalian -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">II. Pengendalian Yang Ada</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Uraian Pengendalian</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="uraian_pengendalian" class="form-control" rows="3" placeholder="Masukkan uraian pengendalian yang saat ini sudah berjalan..." required>{{ $identifikasi->analisis->uraian_pengendalian ?? '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Desain Pengendalian</label></td>
                                    <td class="input-cell text-start">
                                        <select name="desain_pengendalian" class="form-control tom-select" required>
                                            <option value="" selected disabled>Pilih Desain...</option>
                                            <option value="Ada" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->desain_pengendalian == 'Ada') ? 'selected' : '' }}>Ada</option>
                                            <option value="Tidak" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->desain_pengendalian == 'Tidak') ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Efektifitas</label></td>
                                    <td class="input-cell text-start">
                                        <select name="efektifitas_pengendalian" class="form-control tom-select" required>
                                            <option value="" selected disabled>Pilih Efektifitas...</option>
                                            <option value="Efektif" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->efektifitas_pengendalian == 'Efektif') ? 'selected' : '' }}>Efektif</option>
                                            <option value="Kurang Efektif" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->efektifitas_pengendalian == 'Kurang Efektif') ? 'selected' : '' }}>Kurang Efektif</option>
                                            <option value="Tidak Efektif" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->efektifitas_pengendalian == 'Tidak Efektif') ? 'selected' : '' }}>Tidak Efektif</option>
                                        </select>
                                    </td>
                                </tr>

                                <!-- Bagian Scoring -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">III. Penilaian Risiko (Scoring)</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Probabilitas (Skor 1-5)</label></td>
                                    <td class="input-cell text-start">
                                        <select name="probabilitas_id" id="probabilitas" class="form-control tom-select" required>
                                            <option value="" selected disabled>Pilih Probabilitas</option>
                                            @foreach($probabilitas as $p)
                                                <option value="{{ $p->id }}" data-val="{{ $p->nilai_probabilitas }}" {{ ($identifikasi->analisis && $identifikasi->analisis->probabilitas_id == $p->id) ? 'selected' : '' }}>
                                                    {{ $p->nilai_probabilitas }} - {{ $p->nama_probabilitas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Dampak (Skor 1-5)</label></td>
                                    <td class="input-cell text-start">
                                        <select name="dampak_id" id="dampak" class="form-control tom-select" required>
                                            <option value="" selected disabled>Pilih Dampak</option>
                                            @foreach($dampak as $d)
                                                <option value="{{ $d->id }}" data-val="{{ $d->nilai_dampak }}" {{ ($identifikasi->analisis && $identifikasi->analisis->dampak_id == $d->id) ? 'selected' : '' }}>
                                                    {{ $d->nilai_dampak }} - {{ $d->nama_dampak }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell" style="background-color: #e9ecef;"><label class="mb-0">Total Skor (TR)</label></td>
                                    <td class="input-cell text-start">
                                        <input type="text" id="total_skor" class="form-control font-weight-bold" readonly style="width: 100px; background-color: #f8f9fa;">
                                        <small id="peringkat_text" class="d-block mt-1 font-weight-bold"></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Pemilik Risiko</label></td>
                                    <td class="input-cell text-start">
                                        <select name="pemilik_risiko" class="form-control tom-select" required>
                                            <option value="" selected disabled>Pilih Unit Pemilik Risiko</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->nama_unit }}" {{ (isset($identifikasi->analisis) && $identifikasi->analisis->pemilik_risiko == $unit->nama_unit) ? 'selected' : '' }}>
                                                    {{ $unit->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('analisis-risiko.index') }}" class="btn btn-secondary mb-0 border-radius-lg">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold">
                             Simpan Analisis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card, .card-body {
        overflow: visible !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
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
        font-size: 0.875rem;
    }
</style>
@endsection

@push('js')
<!-- Tom Select JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    const probSelect = document.getElementById('probabilitas');
    const dampSelect = document.getElementById('dampak');
    const totalInput = document.getElementById('total_skor');
    const rankText = document.getElementById('peringkat_text');

    function calculate() {
        // More robust option retrieval using checked selector
        const pOption = probSelect.querySelector('option:checked');
        const dOption = dampSelect.querySelector('option:checked');
        
        const pVal = pOption ? (pOption.getAttribute('data-val') || 0) : 0;
        const dVal = dOption ? (dOption.getAttribute('data-val') || 0) : 0;
        const score = parseInt(pVal) * parseInt(dVal);

        totalInput.value = (score > 0) ? score : '-';
        
        // Reset and update badge classes
        rankText.className = 'd-block mt-1 font-weight-bold';
        
        if(score >= 15) {
            rankText.innerText = 'SANGAT TINGGI';
            rankText.classList.add('text-danger');
        } else if(score >= 10) {
            rankText.innerText = 'TINGGI';
            rankText.classList.add('text-danger');
        } else if(score >= 5) {
            rankText.innerText = 'SEDANG';
            rankText.classList.add('text-warning');
        } else if(score >= 3) {
            rankText.innerText = 'RENDAH';
            rankText.classList.add('text-info');
        } else if(score > 0) {
            rankText.innerText = 'SANGAT RENDAH';
            rankText.classList.add('text-success');
        } else {
            rankText.innerText = '';
        }
    }

    // Initialize TomSelect with reliable event listeners
    document.querySelectorAll('.tom-select').forEach((el) => {
        if (el.tomselect) return;
        
        let control = new TomSelect(el, {
            create: false,
            dropdownParent: 'body'
        });

        // Use TomSelect native change listener
        control.on('change', () => {
            calculate();
        });
    });

    // Also listen to Native change just in case
    probSelect.addEventListener('change', calculate);
    dampSelect.addEventListener('change', calculate);
    
    // Initial calculation on load
    setTimeout(calculate, 200);
</script>
@endpush
