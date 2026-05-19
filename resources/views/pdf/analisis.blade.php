@extends('pdf.layout')

@section('title', 'Analisis Risiko')
@section('doc_title', 'Analisis Risiko')
@section('doc_subtitle')
    Periode {{ $periode->tahun }} {{ $triwulanText ? '- ' . $triwulanText : '' }} {{ $unit ? '| Unit: ' . $unit->nama_unit : '' }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No</th>
                <th rowspan="2" style="width: 80px;">Kode</th>
                <th rowspan="2">Pernyataan Risiko</th>
                <th colspan="3">Analisis Risiko</th>
                <th rowspan="2" style="width: 100px;">Peringkat</th>
                <th rowspan="2">Pengendalian yang Ada</th>
                <th rowspan="2" style="width: 100px;">Pemilik</th>
            </tr>
            <tr>
                <th style="width: 30px;">P</th>
                <th style="width: 30px;">D</th>
                <th style="width: 30px;">TR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                @php
                    $score = $item->analisis->skor_risiko ?? null;
                    $rankClass = '';
                    if ($score !== null) {
                        if ($score >= 15) $rankClass = 'rank-st';
                        elseif ($score >= 10) $rankClass = 'rank-t';
                        elseif ($score >= 5) $rankClass = 'rank-s';
                        elseif ($score >= 3) $rankClass = 'rank-r';
                        else $rankClass = 'rank-sr';
                    }

                    // Mapping Pemilik
                    $rawPemilik = $item->analisis->pemilik_risiko ?? '';
                    $pemilikIds = array_filter(explode(',', $rawPemilik));
                    $pemilikNames = collect($pemilikIds)->map(function($id) use ($units) {
                        $u = $units->firstWhere('id', trim($id));
                        return $u ? $u->nama_unit : '-';
                    })->toArray();
                    $firstPemilik = $pemilikNames[0] ?? '-';
                    $extraCount = count($pemilikNames) > 1 ? count($pemilikNames) - 1 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-bold">{{ $item->kode_risiko }}</td>
                    <td>{{ $item->pernyataan_risiko }}</td>
                    <td class="text-center">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</td>
                    <td class="text-center">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</td>
                    <td class="text-center font-bold {{ $rankClass }}">{{ $score ?? '-' }}</td>
                    <td class="text-center font-bold">
                        {{ $item->analisis->peringkat_risiko ?? '-' }}
                    </td>
                    <td>{{ $item->analisis->uraian_pengendalian ?? '-' }}</td>
                    <td class="text-center">
                        {{ $firstPemilik }}
                        @if($extraCount > 0)
                            <br><small>(+{{ $extraCount }} unit lainnya)</small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
