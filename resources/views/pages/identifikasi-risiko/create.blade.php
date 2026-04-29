@extends('layouts.app')

@section('title', 'Tambah Identifikasi Risiko - Risk Register')
@section('breadcrumb', 'Identifikasi Risiko')
@section('page_title', 'Form Identifikasi Risiko')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-radius-lg shadow-sm border-0">
            <div class="card-header bg-primary py-3">
                <h6 class="mb-0 text-white font-weight-bold text-center">Formulir Identifikasi Risiko Baru</h6>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('identifikasi-risiko.store') }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table mb-0 table-form border-bottom">
                            <tbody>
                                <!-- Bagian I -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">I. Konteks & Kegiatan</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Unit Kerja</label></td>
                                    <td class="input-cell text-start">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-building text-xs text-primary"></i></span>
                                            <select name="unit_id" class="tom-select" required>
                                                <option value="" selected disabled>Pilih Unit Kerja</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Kegiatan</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="kegiatan" class="form-control" rows="2" placeholder="Masukkan nama kegiatan..." required style="resize: none;"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Tujuan Kegiatan</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="tujuan_kegiatan" class="form-control" rows="2" placeholder="Masukkan tujuan kegiatan..." required style="resize: none;"></textarea>
                                    </td>
                                </tr>

                                <!-- Bagian II -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">II. Klasifikasi Risiko</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Kategori Risiko</label></td>
                                    <td class="input-cell text-start">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tags text-xs text-primary"></i></span>
                                            <select name="kategori_risiko_id" class="tom-select" required>
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                @foreach($kategori as $kat)
                                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Ruang Lingkup</label></td>
                                    <td class="input-cell text-start">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-expand-arrows-alt text-xs text-primary"></i></span>
                                            <select name="ruang_lingkup_id" class="tom-select" required>
                                                <option value="" selected disabled>Pilih Ruang Lingkup</option>
                                                @foreach($ruangLingkup as $rl)
                                                    <option value="{{ $rl->id }}">{{ $rl->nama_ruang_lingkup }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Bagian III -->
                                <tr>
                                    <th colspan="2" class="bg-gray-100 py-2 px-4 border-bottom shadow-none">
                                        <h6 class="mb-0 text-primary text-uppercase text-xxs font-weight-bolder">III. Identifikasi Risiko</h6>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Pernyataan Risiko</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="pernyataan_risiko" class="form-control" rows="3" placeholder="Masukkan pernyataan risiko secara lengkap..." required style="resize: none;"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Sebab (Cause)</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="sebab" class="form-control" rows="3" placeholder="Masukkan penyebab utama risiko..." required style="resize: none;"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Jenis Risiko (UC/C)</label></td>
                                    <td class="input-cell text-start px-4">
                                        <div class="d-flex gap-4 custom-radio-group">
                                            <div class="custom-radio">
                                                <input type="radio" name="jenis_risiko" value="C" id="jenisC" checked>
                                                <label for="jenisC">
                                                    <i class="fa fa-check-circle check-icon"></i> C (Controllable)
                                                </label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" name="jenis_risiko" value="UC" id="jenisUC">
                                                <label for="jenisUC">
                                                    <i class="fa fa-check-circle check-icon"></i> UC (Uncontrollable)
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell"><label class="mb-0">Dampak (Impact)</label></td>
                                    <td class="input-cell text-start">
                                        <textarea name="dampak" class="form-control" rows="3" placeholder="Masukkan dampak yang timbul..." required style="resize: none;"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex justify-content-end gap-2 border-radius-bottom-lg">
                        <a href="{{ route('identifikasi-risiko.index') }}" class="btn btn-secondary mb-0 border-radius-lg">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-5 border-radius-lg font-weight-bold">
                             Simpan Identifikasi
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

    /* Tom Select inside Input Group Integration */
    .input-group .ts-wrapper.tom-select {
        border: none !important;
        flex: 1 1 auto;
        width: 1% !important;
    }
    .input-group .ts-wrapper .ts-control {
        border: none !important;
        padding: 0.5rem 0.75rem !important;
        background: transparent !important;
        font-size: 0.875rem !important;
        min-height: 40px !important;
        display: flex;
        align-items: center;
    }
    .input-group:focus-within {
        border-color: #007774 !important;
        box-shadow: 0 0 0 2px rgba(0, 119, 116, 0.05) !important;
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

@push('js')
<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.querySelectorAll('.tom-select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                option: function(data, escape) {
                    return '<div class="py-2 px-3 text-sm">' + escape(data.text) + '</div>';
                },
                item: function(data, escape) {
                    return '<div class="text-sm">' + escape(data.text) + '</div>';
                }
            }
        });
    });
</script>
@endpush
