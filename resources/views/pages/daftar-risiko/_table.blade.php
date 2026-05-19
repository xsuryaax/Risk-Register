<div class="table-responsive p-0" id="tableWrapper" data-total="{{ $risikos->total() }}" data-count="{{ $risikos->count() }}">
    @if($activePeriode && $viewPeriodeId != $activePeriode->id && $risikos->total() > $risikos->count())
        <div id="selectAllGlobalContainer" class="d-none alert border-0 border-radius-0 mb-0 py-2 px-3 text-center text-xs shadow-none d-flex align-items-center justify-content-center text-white" style="background-color: #007774 !important; border-top-left-radius: 10px; border-top-right-radius: 10px;">
            <i class="fa fa-info-circle me-2"></i>
            <span id="selectAllText">Terpilih <strong>{{ $risikos->count() }}</strong> risiko di halaman ini.</span>
            <button type="button" id="btnSelectAllGlobal" class="btn btn-link text-white p-0 ms-2 text-xs font-weight-bolder mb-0" style="text-transform: none; text-decoration: underline;">
                Pilih semua <strong>{{ $risikos->total() }}</strong> risiko?
            </button>
            <button type="button" id="btnClearSelection" class="btn btn-link text-white p-0 ms-3 text-xs mb-0 d-none" style="text-transform: none; opacity: 0.8;">
                <i class="fa fa-times-circle me-1"></i> Batal
            </button>
        </div>
    @endif
    <table class="table align-items-center mb-0 table-bordered-light table-daftar" id="mainTable">
        <thead class="bg-light sticky-top" style="z-index: 2;">
            <tr>
                <th rowspan="2" class="text-center dl-no">
                    @if($activePeriode && $viewPeriodeId != $activePeriode->id)
                        <div class="form-check d-flex justify-content-center p-0">
                            <input class="form-check-input custom-chk-azra" type="checkbox" id="checkAll" style="margin-left: 0;">
                        </div>
                    @else
                        No
                    @endif
                </th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-kegiatan">
                    Kegiatan</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-risiko">
                    Pernyataan<br>Risiko</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-sebab">
                    Sebab</th>
                <th colspan="3"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100">
                    Analisis Risiko</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-rank">
                    PR</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pengendalian">
                    Pengendalian<br>Yang Ada</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-rencana">
                    Rencana<br>Tindak Lanjut</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pj">
                    Pemilik</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 80px !important; min-width: 80px !important;">
                    Aksi</th>
            </tr>
            <tr>
                <th
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                    P</th>
                <th
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                    D</th>
                <th
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-num">
                    TR</th>
            </tr>
        </thead>
        <tbody>
            @forelse($risikos as $item)
                @php
                    $isPulled = in_array($item->kegiatan, $pulledActivities);
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

                    $evaluasi = $showValue ? $item->evaluasi : null;
                    $analisis = $showValue ? $item->analisis : null;
                    $analisisKecukupan = $showValue ? $item->analisisKecukupan : null;

                    $score = $evaluasi ? $evaluasi->skor_residu : ($analisis->skor_risiko ?? null);
                    
                    $rank = null;
                    $bgColor = 'transparent';
                    $textColor = 'text-dark';

                    if ($score !== null) {
                        $rank = $score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')));
                        $bgColor = $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')));
                        $textColor = ($score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                    }
                @endphp
                <tr>
                    <td class="align-middle text-center">
                        @if($activePeriode && $viewPeriodeId != $activePeriode->id)
                            <div class="form-check d-flex justify-content-center p-0">
                                @if($isPulled)
                                    <i class="fa fa-check-square" style="color: #d1d1d1; font-size: 1.15rem;" title="Sudah ditarik"></i>
                                @else
                                    <input class="form-check-input risk-checkbox custom-chk-azra" type="checkbox" value="{{ $item->id }}" style="margin-left: 0;">
                                @endif
                            </div>
                        @else
                            <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($risikos->currentPage() - 1) * $risikos->perPage() }}</span>
                        @endif
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">
                            {{ $item->kegiatan }}</p>
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs mb-0 text-wrap text-dark">{{ $item->pernyataan_risiko }}</p>
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs mb-0 text-wrap text-dark">{{ $item->sebab }}</p>
                    </td>

                    <td class="align-middle text-center">
                        <span
                            class="text-xs font-weight-bold text-dark">{{ $evaluasi ? ($evaluasi->probabilitas?->nilai_probabilitas ?? '-') : ($analisis->probabilitas?->nilai_probabilitas ?? '-') }}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span
                            class="text-xs font-weight-bold text-dark">{{ $evaluasi ? ($evaluasi->dampak?->nilai_dampak ?? '-') : ($analisis->dampak?->nilai_dampak ?? '-') }}</span>
                    </td>
                    
                    <td class="align-middle text-center" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                        <span class="text-xs font-weight-bold {{ $score !== null ? $textColor : 'text-dark' }}">{{ $score ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="text-xxs font-weight-bold text-dark">
                            {{ $score !== null ? $rank : '-' }}
                        </span>
                    </td>

                    <td class="align-middle text-start text-wrap">
                        <span
                            class="text-xs text-dark">{{ $analisis->uraian_pengendalian ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-start text-wrap">
                        <span
                            class="text-xs text-dark">{{ $analisisKecukupan->uraian_rencana ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-center text-wrap">
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
                        <div class="custom-tooltip-wrapper">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <span class="text-xs text-dark font-weight-bold cursor-pointer text-truncate d-inline-block" style="max-width: 80px;" title="{{ $firstPemilik }}">
                                    {{ $firstPemilik }}
                                </span>
                                @if($extraCount > 0)
                                    <span class="badge bg-soft-info text-primary p-1" style="font-size: 0.65rem; min-width: 18px;">+{{ $extraCount }}</span>
                                @endif
                            </div>

                            @if(count($pemilikNames) > 1)
                            <div class="custom-tooltip-content">
                                <div class="p-2">
                                    <div class="mb-2 font-weight-bold border-bottom pb-1 text-info" style="font-size: 10px; letter-spacing: 0.5px;">DAFTAR PEMILIK :</div>
                                    <ul class="list-unstyled mb-0 text-start">
                                        @foreach($pemilikNames as $p)
                                            <li class="py-1 d-flex align-items-start gap-2" style="font-size: 11px; line-height: 1.2;">
                                                <i class="fa fa-circle text-info mt-1" style="font-size: 6px;"></i>
                                                <span class="text-white">{{ trim($p) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="align-middle text-center" style="width: 80px !important;">
                         <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('pdf.profile', $item->id) }}?type=daftar" class="btn-action bg-info border-0" title="Cetak Profile PDF" target="_blank" style="width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #17a2b8 !important;">
                                <i class="fa fa-file-pdf text-white" style="font-size: 0.75rem;"></i>
                            </a>
                            @if($activePeriode && $viewPeriodeId != $activePeriode->id)
                                @if(!$isPulled)
                                    <button type="button" class="btn-action btn-copy-single" 
                                        data-id="{{ $item->id }}" 
                                        title="Tarik ke Periode Aktif" 
                                        style="background-color: #ff9800; border: none; color: white; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <i class="fa fa-copy" style="font-size: 0.75rem;"></i>
                                    </button>
                                @else
                                    <i class="fa fa-check-double" style="color: #007774; font-size: 0.7rem; align-self: center;" title="Sudah ditarik"></i>
                                @endif
                            @endif
                         </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center py-6 text-secondary text-xs">Belum ada daftar lengkap risiko.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($risikos->hasPages())
    <div class="card-footer py-3">
        <div class="d-flex justify-content-center">
            {{ $risikos->onEachSide(1)->links() }}
        </div>
    </div>
@endif
