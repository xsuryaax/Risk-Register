<div class="table-responsive p-0">
    <table class="table align-items-center mb-0 table-bordered-light table-evaluasi" id="mainTable">
        <thead class="bg-light">
            <!-- Header Row 1 -->
            <tr>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-no">No</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-kode">Kode</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-pernyataan">Pernyataan Risiko</th>
                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100">Daftar Risiko (Awal)</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-pemilik">Pemilik</th>
                <th colspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-info text-white">Evaluasi Risiko (Residu)</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-rank">PR</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-penurunan" style="line-height: 1.1;">Indeks<br>Efektivitas (%)</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-action">Aksi</th>
            </tr>
            <!-- Header Row 2 -->
            <tr>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">P</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-num">D</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 col-tr">TR</th>
                
                <th class="text-center text-uppercase text-white text-xxs font-weight-bolder bg-info col-num">P</th>
                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-num">D</th>
                <th class="text-center text-white text-uppercase text-xxs font-weight-bolder bg-info col-tr">TR</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            @php
                // Filter logic for consistent triwulan view
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
                $isMatch = $showValue;
            @endphp
            <tr data-peringkat="{{ strtolower($item->analisis->peringkat_risiko ?? '') }}" data-pemilik="{{ strtolower($item->analisis->pemilik_risiko ?? '') }}">
                <td class="align-middle text-center">
                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                </td>
                <td class="text-start text-wrap">
                    <p class="text-xs font-weight-bold mb-0 text-dark">{{ $item->kegiatan }}</p>
                    {{-- Badge removed as requested --}}
                </td>

                <!-- Risiko Awal -->
                <td class="align-middle text-center bg-gray-50">
                    <span class="text-xs text-dark">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</span>
                </td>
                <td class="align-middle text-center bg-gray-50">
                    <span class="text-xs text-dark">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</span>
                </td>
                @php
                    $score = $item->analisis->skor_risiko;
                    $rank = $score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')));
                    $bgColor = $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')));
                    $textColor = ($score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                @endphp
                <td class="align-middle text-center" style="{{ isset($item->analisis) ? 'background-color: '.$bgColor.';' : '' }}">
                    <span class="text-xs font-weight-bold {{ isset($item->analisis) ? $textColor : 'text-dark' }}">{{ $item->analisis->skor_risiko ?? '-' }}</span>
                </td>

                <td class="align-middle text-center px-2">
                    @php
                        $rawPemilik = $item->analisis->pemilik_risiko ?? '-';
                        $pemiliks = array_filter(explode(',', $rawPemilik));
                        $firstPemilik = $pemiliks[0] ?? '-';
                        $extraCount = count($pemiliks) > 1 ? count($pemiliks) - 1 : 0;
                    @endphp
                    <div class="custom-tooltip-wrapper">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="text-xs text-dark font-weight-bold cursor-pointer text-truncate" style="max-width: 100px;">
                                {{ $firstPemilik }}
                            </span>
                            @if($extraCount > 0)
                                <span class="badge bg-soft-info text-primary p-1 ms-1" style="font-size: 0.65rem; min-width: 18px;">+{{ $extraCount }}</span>
                            @endif
                        </div>

                        @if(count($pemiliks) > 1)
                        <div class="custom-tooltip-content">
                            <div class="p-2">
                                <div class="mb-2 font-weight-bold border-bottom pb-1 text-info" style="font-size: 10px; letter-spacing: 0.5px;">DAFTAR PEMILIK :</div>
                                <ul class="list-unstyled mb-0 text-start">
                                    @foreach($pemiliks as $p)
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

                <!-- Risiko Residu -->
                <td class="align-middle text-center bg-info-soft">
                    <span class="text-xs text-dark">{{ $isMatch ? ($item->evaluasi?->probabilitas?->nilai_probabilitas ?? '-') : '-' }}</span>
                </td>
                <td class="align-middle text-center bg-info-soft">
                    <span class="text-xs text-dark">{{ $isMatch ? ($item->evaluasi?->dampak?->nilai_dampak ?? '-') : '-' }}</span>
                </td>
                @php
                    $resScore = $isMatch ? ($item->evaluasi->skor_residu ?? null) : null;
                    $resRank = $resScore !== null ? ($resScore >= 15 ? 'Sangat Tinggi' : ($resScore >= 10 ? 'Tinggi' : ($resScore >= 5 ? 'Sedang' : ($resScore >= 3 ? 'Rendah' : 'Sangat Rendah')))) : '-';
                    $resBgColor = $resScore !== null ? ($resScore >= 15 ? '#c00000' : ($resScore >= 10 ? '#ff9900' : ($resScore >= 5 ? '#ffeb3b' : ($resScore >= 3 ? '#0d6efd' : '#198754')))) : '';
                    $resTextColor = ($resScore !== null && $resScore >= 5 && $resScore < 10) ? 'text-dark' : 'text-white';
                @endphp
                <td class="align-middle text-center" style="{{ $resScore !== null ? 'background-color: '.$resBgColor.';' : '' }}">
                    <span class="text-xs font-weight-bold {{ $resScore !== null ? $resTextColor : 'text-dark' }}">{{ $resScore ?? '-' }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-xxs font-weight-bold text-dark">{{ $resScore !== null ? $resRank : '-' }}</span>
                </td>

                <td class="align-middle text-center">
                    @if($resScore !== null)
                        @php $val = $item->evaluasi->penurunan_persen; @endphp
                        <span class="text-xs font-weight-bold {{ $val > 0 ? 'text-success' : ($val < 0 ? 'text-danger' : 'text-secondary') }}">
                            {{ $val > 0 ? '↓' : ($val < 0 ? '↑' : '') }} {{ number_format(abs($val), 0) }}%
                        </span>
                    @else
                        <span class="text-xs text-secondary">-</span>
                    @endif
                </td>

                <td class="align-middle text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('pdf.profile', $item->id) }}?type=evaluasi" class="btn-action bg-info border-0" title="Cetak Profile PDF" target="_blank" style="background-color: #17a2b8 !important;">
                            <i class="fa fa-file-pdf text-white"></i>
                        </a>
                        <a href="{{ route('evaluasi-risiko.edit', $item->id) }}?view_triwulan={{ $viewTri }}" class="btn-action btn-edit" title="{{ $isMatch && isset($item->evaluasi) ? 'Edit Evaluasi' : 'Tambah Evaluasi' }}">
                            <i class="fa {{ $isMatch && isset($item->evaluasi) ? 'fa-edit' : 'fa-plus' }}"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="text-center py-6 text-secondary text-xs">Belum ada data analisis risiko.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($data->hasPages())
<div class="card-footer py-3 border-top">
    <div class="d-flex justify-content-center">
        {{ $data->links() }}
    </div>
</div>
@endif
