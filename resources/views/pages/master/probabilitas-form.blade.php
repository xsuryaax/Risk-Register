@extends('layouts.app')

@section('title', (isset($item) ? 'Edit' : 'Tambah') . ' Skala Probabilitas - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', (isset($item) ? 'Edit' : 'Tambah') . ' Skala Probabilitas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">{{ isset($item) ? 'Perbarui' : 'Tambah' }} Skala Probabilitas</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ isset($item) ? route('probabilitas.update', $item->id) : route('probabilitas.store') }}" method="POST">
                    @csrf
                    @if(isset($item))
                        @method('PUT')
                    @endif

                    <div class="table-responsive">
                        <table class="table mb-0 table-form">
                            <tbody>
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">Informasi Dasar</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Nama Probabilitas</label></td>
                                    <td class="input-cell text-start">
                                        <input type="text" name="nama_probabilitas" class="form-control" value="{{ $item->nama_probabilitas ?? '' }}" placeholder="Contoh: Sering Terjadi" required>
                                        <small class="text-xxs text-muted mt-1 d-block">Label tingkatan probabilitas.</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Nilai Skala (1-5)</label></td>
                                    <td class="input-cell text-start">
                                        <div style="max-width: 150px;">
                                            <input type="number" name="nilai_probabilitas" class="form-control" min="1" max="5" value="{{ $item->nilai_probabilitas ?? '' }}" placeholder="1-5" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Warna Visual</label></td>
                                    <td class="input-cell text-start">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" name="warna" class="form-control p-1" value="{{ $item->warna ?? '#5e72e4' }}" style="width: 50px; height: 40px !important;">
                                            <small class="text-xxs text-muted">Akan muncul di baris tabel dan peta risiko.</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">Kriteria & Frekuensi</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Deskripsi Kriteria</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="keterangan" class="form-control" rows="4" placeholder="Jelaskan kriteria frekuensi (persentase dan periode waktu)..." required style="resize: none;">{{ $item->keterangan ?? '' }}</textarea>
                                        <div class="alert bg-gray-100 border-0 mt-2 mb-0 p-2 shadow-none">
                                            <p class="text-xxs text-dark mb-0">
                                                <i class="fa fa-lightbulb me-1 text-primary"></i> Contoh: "Peristiwa sangat mungkin terjadi. Terjadi beberapa kali per tahun (51-80%)."
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('probabilitas.index') }}" class="btn btn-secondary mb-0 border-radius-lg px-4">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold shadow-sm">
                            <i class="fa fa-save me-2"></i> {{ isset($item) ? 'Simpan Perubahan' : 'Simpan Data' }}
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
    }
    .form-control:focus {
        border-color: #007774 !important;
        box-shadow: 0 0 0 2px rgba(0,119,116,0.05) !important;
    }

    /* Custom Radio with Checkmark Theme */
    .custom-radio-group .custom-radio {
        position: relative;
    }
    .custom-radio input[type="radio"] {
        display: none;
    }
    .custom-radio label {
        cursor: pointer;
        font-weight: 700;
        font-size: 0.875rem;
        color: #67748e;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        padding: 5px 10px;
        border-radius: 8px;
    }
    .custom-radio .check-icon {
        font-size: 1.1rem;
        color: #d2d6da;
        transition: all 0.2s ease;
    }
    .custom-radio input[type="radio"]:checked + label {
        color: #007774;
    }
    .custom-radio input[type="radio"]:checked + label .check-icon {
        color: #007774;
    }
    .custom-radio label:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
