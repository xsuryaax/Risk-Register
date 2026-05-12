<div class="p-0">
    <table class="table align-items-center mb-0 table-bordered-light table-daftar" id="mainTable">
        <thead class="bg-light sticky-top" style="z-index: 2;">
            <tr>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-no">
                    No</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-kegiatan">
                    Kegiatan</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-risiko">
                    Pernyataan<br>Risiko</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-sebab">
                    Sebab</th>
                <th colspan="4"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100">
                    Analisis Risiko</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pengendalian">
                    Pengendalian<br>Yang Ada</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-rencana">
                    Rencana<br>Tindak Lanjut</th>
                <th rowspan="2"
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dl-pj">
                    PJ</th>
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
                <th
                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 dl-rank">
                    Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse($risikos as $item)
                <tr>
                    <td class="align-middle text-center">
                        <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($risikos->currentPage() - 1) * $risikos->perPage() }}</span>
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">
                            {{ $item->kegiatan }}</p>
                        <span class="text-xxs text-primary">{{ $item->kode_risiko }}</span>
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs mb-0 text-wrap text-dark">{{ $item->pernyataan_risiko }}</p>
                    </td>
                    <td class="align-middle text-start">
                        <p class="text-xs mb-0 text-wrap text-dark">{{ $item->sebab }}</p>
                    </td>

                    <td class="align-middle text-center">
                        <span
                            class="text-xs font-weight-bold text-dark">{{ $item->evaluasi ? ($item->evaluasi->probabilitas->nilai_probabilitas ?? '-') : ($item->analisis->probabilitas->nilai_probabilitas ?? '-') }}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span
                            class="text-xs font-weight-bold text-dark">{{ $item->evaluasi ? ($item->evaluasi->dampak->nilai_dampak ?? '-') : ($item->analisis->dampak->nilai_dampak ?? '-') }}</span>
                    </td>
                    @php
                        $score = $item->evaluasi ? $item->evaluasi->skor_residu : ($item->analisis->skor_risiko ?? null);
                        $rank = strtoupper($item->evaluasi ? $item->evaluasi->peringkat_residu : ($item->analisis->peringkat_risiko ?? ''));
                        $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffeb3b' : ($rank == 'RENDAH' ? '#0d6efd' : '#198754')));
                        $textColor = ($rank == 'SEDANG' || $rank == '') ? 'text-dark' : 'text-white';
                    @endphp
                    <td class="align-middle text-center" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                        <span class="text-xs font-weight-bold {{ $score !== null ? $textColor : 'text-dark' }}">{{ $score ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span
                            class="text-xxs font-weight-bold {{ $rank ? 'text-dark' : 'text-secondary' }}">{{ $rank ? ucfirst(strtolower($rank)) : '-' }}</span>
                    </td>

                    <td class="align-middle text-start text-wrap">
                        <span
                            class="text-xs text-dark">{{ $item->analisis->uraian_pengendalian ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-start text-wrap">
                        <span
                            class="text-xs text-dark">{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</span>
                    </td>
                    <td class="align-middle text-center text-wrap">
                        <span
                            class="text-xs text-dark">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? ($item->analisis->pemilik_risiko ?? '-') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-6 text-secondary text-xs">Belum ada daftar
                        lengkap risiko.</td>
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
