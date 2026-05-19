<div class="table-responsive p-0">
    <table class="table align-items-center mb-0 table-bordered-light" id="mainTable">
        <thead class="bg-light">
            <!-- Header Row 1 -->
            <tr>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">No</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 70px;">Kode</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Pernyataan Risiko</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 50px;">PR</th>
                <th colspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 border-bottom bg-gray-100">Analisis Kecukupan</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 100px;">Pemilik</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100" style="width: 100px;">PJ Lanjut</th>
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Action</th>
            </tr>
            <!-- Header Row 2 -->
            <tr>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top" style="min-width: 250px;">Uraian Rencana</th>
                <th rowspan="2" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-top" style="width: 120px;">Jadwal</th>
            </tr>
            <!-- Header Row 3 -->
            <tr>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 bg-gray-100 border-bottom">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            @php
                // Consistent filter check
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
                <td class="align-middle text-center px-1">
                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                </td>
                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                </td>
                <td class="px-2 text-start">
                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                </td>

                @php
                    $score = $item->analisis->skor_risiko ?? null;
                    $rankLabel = $score !== null ? ($score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')))) : '-';
                    $bgColor = $score !== null ? ($score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754')))) : '';
                    $textColor = ($score !== null && $score >= 5 && $score < 10) ? 'text-dark' : 'text-white';
                @endphp
                <td class="align-middle text-center px-1" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                    @if($score !== null)
                        <span class="text-xs font-weight-bold {{ $textColor }}">
                            {{ $rankLabel }}
                        </span>
                    @else
                        <span class="text-xs text-secondary">-</span>
                    @endif
                </td>

                <!-- Rencana Pengendalian (DIISI USER) -->
                <td class="px-2 text-start bg-gray-50">
                    <p class="text-xs mb-0 text-wrap text-dark" style="min-width: 250px;">{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</p>
                </td>
                <td class="align-middle text-center px-1 bg-gray-50">
                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $item->analisisKecukupan->jadwal ?? '-' }}</p>
                </td>

                <td class="align-middle text-center px-1">
                    @php
                        $rawPemilik = $item->analisis->pemilik_risiko ?? ($item->unit_id ? (string)$item->unit_id : null);
                        $pemilikIdArray = array_filter(explode(',', $rawPemilik));
                        
                        // Map ID to Name
                        $pemilikNames = collect($pemilikIdArray)->map(function($id) use ($units) {
                            $unit = $units->firstWhere('id', (int)trim($id));
                            return $unit ? $unit->nama_unit : '-';
                        })->toArray();
                        
                        $firstPemilik = $pemilikNames[0] ?? '-';
                        $extraCount = count($pemilikNames) > 1 ? count($pemilikNames) - 1 : 0;
                    @endphp
                    <div class="custom-tooltip-wrapper text-center">
                        <span class="text-xs text-dark cursor-pointer text-truncate d-inline-block" style="max-width: 90px;" title="{{ $firstPemilik }}">
                            {{ $firstPemilik }}
                        </span>
                        @if($extraCount > 0)
                            <span class="badge bg-soft-info text-primary p-1 ms-1" style="font-size: 0.65rem;">+{{ $extraCount }}</span>
                        @endif

                        @if(count($pemilikNames) > 1)
                        <div class="custom-tooltip-content">
                            <div class="px-2 py-1">
                                <div class="mb-1 font-weight-bold border-bottom pb-1 text-white opacity-8" style="font-size: 10px;">DAFTAR PEMILIK :</div>
                                <ul class="list-unstyled mb-0 text-start">
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
                </td>
                <td class="align-middle text-center px-1 bg-gray-50">
                    <p class="text-xs text-dark text-wrap mb-0" style="min-width: 100px;">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? '-' }}</p>
                </td>

                <td class="align-middle text-center px-1">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('pdf.profile', $item->id) }}?type=kecukupan" class="btn-action bg-info border-0" title="Cetak Profile PDF" target="_blank" style="background-color: #17a2b8 !important;">
                            <i class="fa fa-file-pdf text-white"></i>
                        </a>
                        <a href="{{ route('analisis-kecukupan.edit', $item->id) }}?view_triwulan={{ $viewTri }}" class="btn-action btn-edit" title="{{ isset($item->analisisKecukupan) && $item->triwulan == $viewTri ? 'Edit Rencana' : 'Tambah Rencana' }}">
                            <i class="fa {{ isset($item->analisisKecukupan) && $item->triwulan == $viewTri ? 'fa-edit' : 'fa-plus' }}"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-6 text-secondary text-xs">Belum ada data analisis risiko.</td>
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
