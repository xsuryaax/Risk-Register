@extends('pdf.layout')

@section('title', 'Evaluasi Risiko')
@section('doc_title', 'Evaluasi Risiko (Residual Risk)')
@section('doc_subtitle')
    Periode {{ $periode->tahun }} {{ $triwulanText ? '- ' . $triwulanText : '' }} {{ $unit ? '| Unit: ' . $unit->nama_unit : '' }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No</th>
                <th rowspan="2" style="width: 70px;">Kode</th>
                <th rowspan="2">Pernyataan Risiko</th>
                <th colspan="3" style="background-color: #f8f9fa;">Risiko Awal</th>
                <th colspan="3" style="background-color: #e3f2fd;">Risiko Residu</th>
                <th rowspan="2" style="width: 70px;">PR Residu</th>
                <th rowspan="2" style="width: 50px;">Indeks<br>Efektivitas (%)</th>
            </tr>
            <tr>
                <th style="width: 20px;">P</th>
                <th style="width: 20px;">D</th>
                <th style="width: 25px;">TR</th>
                <th style="width: 20px;">P</th>
                <th style="width: 20px;">D</th>
                <th style="width: 25px;">TR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                @php
                    $initScore = $item->analisis->skor_risiko ?? null;
                    $initClass = '';
                    if ($initScore !== null) {
                        if ($initScore >= 15) $initClass = 'rank-st';
                        elseif ($initScore >= 10) $initClass = 'rank-t';
                        elseif ($initScore >= 5) $initClass = 'rank-s';
                        elseif ($initScore >= 3) $initClass = 'rank-r';
                        else $initClass = 'rank-sr';
                    }

                    $resScore = $item->evaluasi->skor_residu ?? null;
                    $resClass = '';
                    if ($resScore !== null) {
                        if ($resScore >= 15) $resClass = 'rank-st';
                        elseif ($resScore >= 10) $resClass = 'rank-t';
                        elseif ($resScore >= 5) $resClass = 'rank-s';
                        elseif ($resScore >= 3) $resClass = 'rank-r';
                        else $resClass = 'rank-sr';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-bold">{{ $item->kode_risiko }}</td>
                    <td>
                        {{ $item->pernyataan_risiko }}
                        @if($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya')
                            <div style="font-size: 7pt; color: #c00000; margin-top: 3px; font-weight: bold;">
                                [Terjadi: {{ $item->evaluasi->frekuensi_kejadian }}]
                            </div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</td>
                    <td class="text-center">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</td>
                    <td class="text-center font-bold {{ $initClass }}">{{ $initScore ?? '-' }}</td>
                    
                    <td class="text-center">{{ $item->evaluasi->probabilitas->nilai_probabilitas ?? '-' }}</td>
                    <td class="text-center">{{ $item->evaluasi->dampak->nilai_dampak ?? '-' }}</td>
                    <td class="text-center font-bold {{ $resClass }}">{{ $resScore ?? '-' }}</td>
                    
                    <td class="text-center font-bold">
                        {{ $item->evaluasi->peringkat_residu ?? '-' }}
                    </td>
                    <td class="text-center font-bold">
                        @if($item->evaluasi && $item->evaluasi->penurunan_persen !== null)
                            @php $val = $item->evaluasi->penurunan_persen; @endphp
                            <span style="color: {{ $val > 0 ? '#198754' : ($val < 0 ? '#c00000' : '#333') }}; font-family: DejaVu Sans, sans-serif;">
                                {!! $val > 0 ? '&darr;' : ($val < 0 ? '&uarr;' : '') !!} {{ number_format(abs($val), 0) }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
