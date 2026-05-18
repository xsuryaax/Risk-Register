<div class="table-responsive p-0">
    <table class="table align-items-center mb-0 table-bordered-light table-compact-analisis" id="mainTable">
        <colgroup>
            <col style="width: 25px;"> <!-- No -->
            <col style="width: 70px;"> <!-- Kode -->
            <col style="width: 15%;"> <!-- Kegiatan -->
            <col style="width: 25%;"> <!-- Uraian -->
            <col style="width: 100px;"> <!-- Desain -->
            <col style="width: 110px;"> <!-- Efektifitas -->
            <col style="width: 65px;"> <!-- P -->
            <col style="width: 65px;"> <!-- D -->
            <col style="width: 50px;"> <!-- TR -->
            <col style="width: 80px;"> <!-- PR -->
            <col style="width: 120px;"> <!-- Pemilik -->
            <col style="width: 50px;"> <!-- Action -->
        </colgroup>
        <thead class="bg-light">
            <tr>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-no">No</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-kode">Kode</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-kegiatan">Kegiatan</th>
                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100">Pengendalian Yang Ada</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num">P</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num border-right-red">D</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-num border-right-red">TR</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-rank border-right-red">PR</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-pemilik">Pemilik</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-action">Aksi</th>
            </tr>
            <tr>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-uraian">Uraian</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-desain">Desain</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top col-efektif">Efektifitas</th>
            </tr>
            <tr>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-bottom col-desain">Ada/Tdk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            @php
                $rawAnalisis = $item->analisis;
                $frekuensi = $item->frekuensi_pelaporan ?? 'triwulan';
                $viewTri = $viewTriwulan ?? 'all';
                $showValue = false;

                if ($viewTri == 'all') {
                    $showValue = true;
                } else {
                    $targetArr = ($viewTri == 's1' ? [1, 2] : ($viewTri == 's2' ? [3, 4] : [$viewTri]));
                    if ($frekuensi == 'tahunan') {
                        $showValue = true;
                    } elseif ($frekuensi == 'semester') {
                        $itemSem = $item->triwulan <= 2 ? [1, 2] : [3, 4];
                        if (array_intersect($targetArr, $itemSem)) {
                            $showValue = true;
                        }
                    } elseif ($frekuensi == 'triwulan') {
                        if (in_array($item->triwulan, $targetArr)) {
                            $showValue = true;
                        }
                    }
                }

                $analisis = $rawAnalisis;
                $pId = $analisis->probabilitas_id ?? null;
                $dId = $analisis->dampak_id ?? null;
                $score = $analisis->skor_risiko ?? null;

                $rank = null;
                $bgColor = 'transparent';
                $textColor = 'text-dark';

                if ($score !== null) {
                    $rank = $score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')));
                    $bgColor = $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')));
                    $textColor = ($score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                }
            @endphp
            <tr data-id="{{ $item->id }}" class="row-analisis">
                <td class="align-middle text-center px-1">
                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                </td>
                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                </td>
                <td class="px-1 text-start">
                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark" style="min-width: 150px;">{{ $item->kegiatan }}</p>
                </td>
                
                {{-- Uraian Pengendalian --}}
                <td class="px-1 bg-gray-50 overflow-hidden">
                    <div class="view-mode">
                        <p class="text-xs mb-0 text-wrap text-dark label-uraian">{{ $analisis->uraian_pengendalian ?? '-' }}</p>
                    </div>
                    <div class="edit-mode d-none">
                        <textarea class="form-control form-control-sm text-xs edit-uraian" rows="2" style="min-width: 180px;">{{ $analisis->uraian_pengendalian ?? '' }}</textarea>
                    </div>
                </td>
                
                {{-- Desain --}}
                <td class="align-middle text-center px-1 bg-gray-50">
                    <div class="view-mode">
                        <span class="text-xs text-dark label-desain">{{ $analisis->desain_pengendalian ?? '-' }}</span>
                    </div>
                    <div class="edit-mode d-none">
                        <select class="form-select form-select-sm text-xs edit-desain">
                            <option value="">-</option>
                            <option value="Ada" {{ ($analisis->desain_pengendalian ?? '') == 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Tidak" {{ ($analisis->desain_pengendalian ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                </td>
                
                {{-- Efektifitas --}}
                <td class="align-middle text-center px-1 bg-gray-50">
                    <div class="view-mode">
                        <span class="text-xs text-dark label-efektif">{{ $analisis->efektifitas_pengendalian ?? '-' }}</span>
                    </div>
                    <div class="edit-mode d-none">
                        <select class="form-select form-select-sm text-xs edit-efektif">
                            <option value="">-</option>
                            <option value="Efektif" {{ ($analisis->efektifitas_pengendalian ?? '') == 'Efektif' ? 'selected' : '' }}>Efektif</option>
                            <option value="Kurang Efektif" {{ ($analisis->efektifitas_pengendalian ?? '') == 'Kurang Efektif' ? 'selected' : '' }}>Kurang</option>
                            <option value="Tidak Efektif" {{ ($analisis->efektifitas_pengendalian ?? '') == 'Tidak Efektif' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                </td>

                {{-- Probabilitas (P) --}}
                <td class="align-middle text-center px-0">
                    <div class="view-mode">
                        <span class="text-xs font-weight-bold text-dark label-prob">{{ $analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                    </div>
                    <div class="edit-mode d-none">
                        <select class="form-select form-select-sm text-xs edit-prob px-1" style="min-width: 45px;">
                            <option value="">-</option>
                            @foreach($probs as $p)
                                <option value="{{ $p->id }}" {{ $pId == $p->id ? 'selected' : '' }}>{{ $p->nilai_probabilitas }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>

                {{-- Dampak (D) --}}
                <td class="align-middle text-center px-0 border-right-red">
                    <div class="view-mode">
                        <span class="text-xs font-weight-bold text-dark label-dampak">{{ $analisis->dampak->nilai_dampak ?? '-' }}</span>
                    </div>
                    <div class="edit-mode d-none">
                        <select class="form-select form-select-sm text-xs edit-dampak px-1" style="min-width: 45px;">
                            <option value="">-</option>
                            @foreach($damps as $d)
                                <option value="{{ $d->id }}" {{ $dId == $d->id ? 'selected' : '' }}>{{ $d->nilai_dampak }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>

                {{-- Skor Risiko (TR) --}}
                <td class="align-middle text-center px-1 border-right-red col-score" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                    <span class="text-xs font-weight-bold {{ $score !== null ? $textColor : 'text-dark' }} label-score">
                        {{ $score ?? '-' }}
                    </span>
                </td>

                {{-- Peringkat Risiko (PR) --}}
                <td class="align-middle text-center px-1 border-right-red col-rank">
                    <span class="text-xxs font-weight-bold text-dark label-rank">
                        {{ $score !== null ? $rank : '-' }}
                    </span>
                </td>

                {{-- Pemilik Risiko --}}
                <td class="align-middle text-center px-1">
                    @php
                        $rawPemilik = $analisis->pemilik_risiko ?? ($item->unit_id ? (string)$item->unit_id : null);
                        $pemilikIdArray = array_filter(explode(',', $rawPemilik));
                        
                        // Map ID to Name
                        $pemilikNames = collect($pemilikIdArray)->map(function($id) use ($units) {
                            $unit = $units->firstWhere('id', (int)trim($id));
                            return $unit ? $unit->nama_unit : '-';
                        })->toArray();
                        
                        $firstPemilik = $pemilikNames[0] ?? '-';
                        $extraCount = count($pemilikNames) > 1 ? count($pemilikNames) - 1 : 0;
                    @endphp
                    <div class="view-mode">
                        <div class="custom-tooltip-wrapper">
                            <span class="text-xs text-dark label-pemilik-container cursor-pointer">
                                <span class="label-pemilik-text">{{ $firstPemilik }}</span>
                                @if($extraCount > 0)
                                    <span class="badge bg-soft-info text-primary p-1 ms-1 label-pemilik-extra" style="font-size: 0.65rem;">+{{ $extraCount }}</span>
                                @endif
                            </span>

                            @if(count($pemilikNames) > 1)
                            <div class="custom-tooltip-content">
                                <div class="px-2 py-1">
                                    <div class="mb-1 font-weight-bold border-bottom pb-1 text-white opacity-8" style="font-size: 10px;">DAFTAR PEMILIK :</div>
                                    <ul class="list-unstyled mb-0 text-start label-pemilik-list">
                                        @foreach($pemilikNames as $p)
                                            <li class="py-1" style="font-size: 11px; white-space: nowrap;">
                                                <i class="fa fa-caret-right me-1 text-info"></i> {{ $p }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="edit-mode d-none">
                        <select class="edit-pemilik ts-multi" multiple style="min-width: 150px;">
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ in_array((string)$u->id, $pemilikIdArray) ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>

                <td class="align-middle text-center px-1">
                    <div class="view-mode d-flex gap-1 justify-content-center">
                        <a href="{{ route('pdf.profile', $item->id) }}?type=analisis" class="btn-action bg-info border-0" title="Cetak Profile PDF" target="_blank" style="background-color: #17a2b8 !important;">
                            <i class="fa fa-file-pdf text-white"></i>
                        </a>
                        <button type="button" class="btn-action btn-edit btn-toggle-edit" title="{{ $analisis ? 'Edit Analisis' : 'Isi Analisis' }}">
                            <i class="fa {{ $analisis ? 'fa-edit' : 'fa-plus' }}"></i>
                        </button>
                    </div>
                    <div class="edit-mode d-none d-flex gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-success btn-inline-save p-2 mb-0 shadow-sm" title="Simpan">
                            <i class="fa fa-check text-xs"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light btn-inline-cancel p-2 mb-0 shadow-sm" title="Batal">
                            <i class="fa fa-times text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center py-6 text-secondary text-xs">Belum ada data risiko.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($data->hasPages())
<div class="card-footer py-3">
    <div class="d-flex justify-content-center pagination-container">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>
@endif
