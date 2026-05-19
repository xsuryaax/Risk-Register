@extends('pdf.layout')

@section('title', 'Daftar Lengkap Risiko')
@section('doc_title', 'Daftar Lengkap Risiko')
@section('doc_subtitle')
    Periode {{ $periode->tahun }} {{ $triwulanText ? '- ' . $triwulanText : '' }} {{ $unit ? '| Unit: ' . $unit->nama_unit : '' }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 60px;">Kode</th>
                <th>Pernyataan Risiko</th>
                <th style="width: 20px;">P</th>
                <th style="width: 20px;">D</th>
                <th style="width: 25px;">TR</th>
                <th style="width: 65px;">PR</th>
                <th>Pengendalian Ada</th>
                <th>Rencana Tindak Lanjut</th>
                <th style="width: 80px;">PJ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                @php
                    $evaluasi = $item->evaluasi;
                    $analisis = $item->analisis;
                    $score = $evaluasi ? $evaluasi->skor_residu : ($analisis->skor_risiko ?? null);
                    
                    $rankClass = '';
                    $rankLabel = '-';
                    if ($score !== null) {
                        $rankLabel = $evaluasi ? $evaluasi->peringkat_residu : ($analisis->peringkat_risiko ?? '-');
                        if ($score >= 15) $rankClass = 'rank-st';
                        elseif ($score >= 10) $rankClass = 'rank-t';
                        elseif ($score >= 5) $rankClass = 'rank-s';
                        elseif ($score >= 3) $rankClass = 'rank-r';
                        else $rankClass = 'rank-sr';
                    }

                    // Mapping Pemilik (Fallback for PJ)
                    $rawPemilik = $analisis->pemilik_risiko ?? '';
                    $pemilikIds = array_filter(explode(',', $rawPemilik));
                    $pemilikNames = collect($pemilikIds)->map(function($id) use ($units) {
                        $u = $units->firstWhere('id', trim($id));
                        return $u ? $u->nama_unit : '-';
                    })->toArray();
                    $firstPemilik = $pemilikNames[0] ?? '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-bold">{{ $item->kode_risiko }}</td>
                    <td>{{ $item->pernyataan_risiko }}</td>
                    <td class="text-center">{{ $evaluasi ? ($evaluasi->probabilitas->nilai_probabilitas ?? '-') : ($analisis->probabilitas->nilai_probabilitas ?? '-') }}</td>
                    <td class="text-center">{{ $evaluasi ? ($evaluasi->dampak->nilai_dampak ?? '-') : ($analisis->dampak->nilai_dampak ?? '-') }}</td>
                    <td class="text-center font-bold {{ $rankClass }}">{{ $score ?? '-' }}</td>
                    <td class="text-center font-bold">{{ $rankLabel }}</td>
                    <td>{{ $analisis->uraian_pengendalian ?? '-' }}</td>
                    <td>{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</td>
                    <td class="text-center">{{ $item->analisisKecukupan->pj_tindak_lanjut ?? ($firstPemilik ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
