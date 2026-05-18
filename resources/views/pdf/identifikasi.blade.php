@extends('pdf.layout')

@section('title', 'Identifikasi Risiko')
@section('doc_title', 'Identifikasi Risiko')
@section('doc_subtitle')
    Periode {{ $periode->tahun }} {{ $triwulanText ? '- ' . $triwulanText : '' }} {{ $unit ? '| Unit: ' . $unit->nama_unit : '' }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Kode</th>
                <th>Kegiatan</th>
                <th>Tujuan</th>
                <th>Pernyataan Risiko</th>
                <th>Sebab</th>
                <th>Dampak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-bold">{{ $item->kode_risiko }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td>{{ $item->tujuan_kegiatan }}</td>
                    <td>{{ $item->pernyataan_risiko }}</td>
                    <td>{{ $item->sebab }}</td>
                    <td>{{ $item->dampak }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
