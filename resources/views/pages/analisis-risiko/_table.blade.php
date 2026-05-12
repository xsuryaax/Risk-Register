<div class="table-responsive p-0">
    <table class="table align-items-center mb-0 table-bordered-light table-compact-analisis" id="mainTable">
        <colgroup>
            <col style="width: 3%; min-width: 30px;">
            <col style="width: 80px;"> <!-- Kode -->
            <col style="width: 22%; min-width: 150px;"> <!-- Kegiatan -->
            <col style="width: 22%; min-width: 150px;"> <!-- Uraian -->
            <col style="width: 50px;">
            <col style="width: 75px;">
            <col style="width: 35px;">
            <col style="width: 35px;">
            <col style="width: 40px;">
            <col style="width: 70px;">
            <col style="width: 80px;">
            <col style="width: 50px;">
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
                <th rowspan="3" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1 col-action">Action</th>
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
            <tr>
                <td class="align-middle text-center px-1">
                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                </td>
                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                </td>
                <td class="px-1 text-start">
                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark">{{ $item->kegiatan }}</p>
                </td>
                
                <td class="px-1 bg-gray-50 text-start">
                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->analisis?->uraian_pengendalian ?? '-' }}</p>
                </td>
                <td class="align-middle text-center px-1 bg-gray-50">
                    <span class="text-xs text-dark">{{ $item->analisis?->desain_pengendalian ?? '-' }}</span>
                </td>
                <td class="align-middle text-center px-1 bg-gray-50">
                    <span class="text-xs text-dark">{{ $item->analisis?->efektifitas_pengendalian ?? '-' }}</span>
                </td>

                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-dark">{{ $item->evaluasi ? ($item->evaluasi->probabilitas->nilai_probabilitas ?? '-') : ($item->analisis?->probabilitas->nilai_probabilitas ?? '-') }}</span>
                </td>
                <td class="align-middle text-center px-1 border-right-red">
                    <span class="text-xs font-weight-bold text-dark">{{ $item->evaluasi ? ($item->evaluasi->dampak->nilai_dampak ?? '-') : ($item->analisis?->dampak->nilai_dampak ?? '-') }}</span>
                </td>
                @php
                    $score = $item->evaluasi ? $item->evaluasi->skor_residu : ($item->analisis?->skor_risiko ?? null);
                    $rank = strtoupper($item->evaluasi ? $item->evaluasi->peringkat_residu : ($item->analisis?->peringkat_risiko ?? ''));
                    $bgColor = $rank == 'SANGAT TINGGI' ? '#c00000' : ($rank == 'TINGGI' ? '#ff9900' : ($rank == 'SEDANG' ? '#ffeb3b' : ($rank == 'RENDAH' ? '#0d6efd' : '#198754')));
                    $textColor = ($rank == 'SEDANG' || $rank == '') ? 'text-dark' : 'text-white';
                @endphp
                <td class="align-middle text-center px-1 border-right-red" style="{{ $score !== null ? 'background-color: '.$bgColor.';' : '' }}">
                    <span class="text-xs font-weight-bold {{ $score !== null ? $textColor : 'text-dark' }}">
                        {{ $score ?? '-' }}
                    </span>
                </td>
                <td class="align-middle text-center px-1 border-right-red">
                    @if($rank)
                        <span class="text-xxs font-weight-bold text-dark">
                            {{ ucfirst(strtolower($rank)) }}
                        </span>
                    @else
                        <span class="text-xs text-secondary">-</span>
                    @endif
                </td>
                <td class="align-middle text-center px-1">
                    <span class="text-xs text-dark col-pemilik-text">{{ $item->analisis?->pemilik_risiko ?? '-' }}</span>
                </td>
                <td class="align-middle text-center px-1">
                    <a href="{{ route('analisis-risiko.edit', $item->id) }}" class="btn-action btn-edit" title="Evaluasi &amp; Analisis">
                        <i class="fa {{ isset($item->analisis) ? 'fa-edit' : 'fa-plus' }}"></i>
                    </a>
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
