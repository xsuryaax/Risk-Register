<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        @php
            $titleMap = [
                'identifikasi' => 'Identifikasi Risiko',
                'analisis' => 'Analisis Risiko',
                'kecukupan' => 'Mitigasi Risiko',
                'evaluasi' => 'Evaluasi Risiko',
                'daftar' => 'Profil Risiko Lengkap',
                'profil' => 'Profil Risiko'
            ];
            echo ($titleMap[$type] ?? 'Profil Risiko') . ' - ' . $item->kode_risiko;
        @endphp
    </title>
    <style>
        @page { 
            margin: 1cm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            line-height: 1.5; 
            color: #333; 
        }
        
        /* Premium Header Design (No Photos) */
        .header-wrapper {
            border-bottom: 3px solid #007774;
            padding-bottom: 15px;
            margin-bottom: 25px;
            width: 100%;
        }
        .header-left {
            width: 60%;
            float: left;
        }
        .header-right {
            width: 40%;
            float: right;
            text-align: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        
        .doc-label {
            font-size: 8pt;
            font-weight: bold;
            color: #007774;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .doc-title {
            font-size: 18pt;
            font-weight: bold;
            color: #222;
            margin-bottom: 5px;
        }
        .doc-meta {
            font-size: 9pt;
            color: #666;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #007774;
            margin: 0;
        }
        .company-sub {
            font-size: 8pt;
            color: #888;
            margin-top: 2px;
        }

        /* Form Structure */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007774;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-header {
            background-color: #f1f3f5;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 8pt;
            color: #007774;
            text-transform: uppercase;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            letter-spacing: 1px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            width: 30%;
            text-align: left;
            background-color: #fbfbfb;
            padding: 12px 15px;
            border-bottom: 1px solid #eeeeee;
            border-right: 1px solid #eeeeee;
            color: #2c3e50;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: top;
            font-size: 9pt;
            color: #333;
            background-color: #ffffff;
        }

        /* Badges */
        .badge { 
            padding: 4px 10px; 
            border-radius: 4px; 
            font-weight: bold; 
            font-size: 8pt;
            color: white;
            display: inline-block;
        }
        .bg-st { background-color: #c00000; }
        .bg-t { background-color: #ff9900; }
        .bg-s { background-color: #ffeb3b; color: #000 !important; }
        .bg-r { background-color: #0d6efd; }
        .bg-sr { background-color: #198754; }

        .footer { 
            position: fixed; 
            bottom: -0.5cm; 
            width: 100%; 
            text-align: center; 
            font-size: 7pt; 
            color: #999;
            border-top: 1px solid #eeeeee;
            padding-top: 5px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-item {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
            background-color: #fcfcfc;
        }
        .sum-label { font-size: 7pt; color: #888; text-transform: uppercase; margin-bottom: 4px; display: block; }
        .sum-value { font-size: 13pt; font-weight: bold; color: #222; }
    </style>
</head>
<body>
    {{-- High-End Header --}}
    <div class="header-wrapper clearfix">
        <div class="header-left">
            <div class="doc-label">Official Document</div>
            <div class="doc-title">
                @if($type == 'identifikasi') IDENTIFIKASI RISIKO 
                @elseif($type == 'analisis') ANALISIS RISIKO 
                @elseif($type == 'kecukupan') RENCANA MITIGASI 
                @elseif($type == 'evaluasi') EVALUASI RISIKO 
                @else PROFIL RISIKO LENGKAP @endif
            </div>
            <div class="doc-meta">
                ID: <strong>{{ $item->kode_risiko }}</strong> | 
                Periode: <strong>{{ $item->periode->tahun }}</strong> | 
                Unit: <strong>{{ $item->unit->nama_unit ?? 'General' }}</strong>
            </div>
        </div>
        <div class="header-right">
            <div class="company-name">RS AZRA</div>
            <div class="company-sub">Risk Management Information System</div>
            <div class="company-sub"><i>Dokumen Terkendali - {{ date('d/m/Y') }}</i></div>
        </div>
    </div>

    {{-- Comprehensive Stats for Summary --}}
    @if(in_array($type, ['evaluasi', 'daftar']))
    <table class="summary-grid">
        <tr>
            @php
                $sScore = $item->analisis->skor_risiko ?? 0;
                $sRank = $sScore >= 15 ? 'st' : ($sScore >= 10 ? 't' : ($sScore >= 5 ? 's' : ($sScore >= 3 ? 'r' : 'sr')));
                $sColors = ['st' => '#c00000', 't' => '#ff9900', 's' => '#ffeb3b', 'r' => '#0d6efd', 'sr' => '#198754'];
                $sBg = $sColors[$sRank];
                $sTxt = ($sRank == 's') ? '#000' : '#fff';
            @endphp
            <td class="summary-item" style="border-left: 4px solid #007774; background-color: {{ $sBg }};">
                <span class="sum-label" style="color: {{ ($sRank == 's') ? '#555' : '#eee' }};">SKOR RISIKO AWAL</span>
                <span class="sum-value" style="color: {{ $sTxt }};">{{ $sScore > 0 ? $sScore : '-' }}</span>
            </td>
            <td class="summary-item">
                <span class="sum-label">STATUS KEJADIAN</span>
                <span class="sum-value" style="color: {{ ($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya') ? '#c00000' : '#198754' }}">
                    {{ ($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya') ? 'TERJADI' : 'TIDAK TERJADI' }}
                </span>
            </td>
            @php
                $rScore = $item->evaluasi->skor_residu ?? 0;
                $rRank = $rScore >= 15 ? 'st' : ($rScore >= 10 ? 't' : ($rScore >= 5 ? 's' : ($rScore >= 3 ? 'r' : 'sr')));
                $rBg = $sColors[$rRank];
                $rTxt = ($rRank == 's') ? '#000' : '#fff';
            @endphp
            <td class="summary-item" style="background-color: {{ $rBg }};">
                <span class="sum-label" style="color: {{ ($rRank == 's') ? '#555' : '#eee' }};">SKOR RISIKO RESIDU</span>
                <span class="sum-value" style="color: {{ $rTxt }};">{{ $rScore > 0 ? $rScore : '-' }}</span>
            </td>
            <td class="summary-item">
                <span class="sum-label">INDEKS EFEKTIVITAS</span>
                @php $val = $item->evaluasi->penurunan_persen ?? 0; @endphp
                <span class="sum-value" style="color: {{ $val > 0 ? '#198754' : ($val < 0 ? '#c00000' : '#333') }}; font-family: DejaVu Sans, sans-serif;">
                    {!! $val > 0 ? '&darr;' : ($val < 0 ? '&uarr;' : '') !!} {{ number_format(abs($val), 0) }}%
                </span>
            </td>
        </tr>
    </table>
    @elseif($type == 'kecukupan' && $item->analisis)
    <table class="summary-grid">
        <tr>
            <td class="summary-item" style="border-left: 4px solid #007774;">
                <span class="sum-label">SKOR RISIKO AWAL</span>
                <span class="sum-value">{{ $item->analisis->skor_risiko ?? '-' }}</span>
            </td>
            <td class="summary-item">
                <span class="sum-label">STATUS KEJADIAN</span>
                <span class="sum-value" style="color: {{ ($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya') ? '#c00000' : '#198754' }}">
                    {{ ($item->evaluasi && $item->evaluasi->status_kejadian == 'Ya') ? 'TERJADI' : 'TIDAK TERJADI' }}
                </span>
            </td>
        </tr>
    </table>
    @endif

    <div class="card">
        <div class="card-header">
            Detail Laporan: @if($type == 'identifikasi') Identifikasi @elseif($type == 'analisis') Analisis @elseif($type == 'kecukupan') Mitigasi @elseif($type == 'evaluasi') Evaluasi @else Profil Lengkap @endif
        </div>

        {{-- Always show a core section for identification basics to ensure the doc isn't "empty" --}}
        <div class="section-header">INFORMASI DASAR & KONTEKS</div>
        <table class="data-table">
            <tr>
                <th>Kode Risiko</th>
                <td><strong>{{ $item->kode_risiko }}</strong></td>
            </tr>
            <tr>
                <th>Nama Kegiatan</th>
                <td>{{ $item->kegiatan }}</td>
            </tr>
            <tr>
                <th>Unit Terkait</th>
                <td>{{ $item->unit->nama_unit ?? '-' }}</td>
            </tr>
            @if(in_array($type, ['identifikasi', 'daftar']))
            <tr>
                <th>Tujuan Kegiatan</th>
                <td>{{ $item->tujuan_kegiatan }}</td>
            </tr>
            <tr>
                <th>Frekuensi Pelaporan</th>
                <td>{{ ucfirst($item->frekuensi_pelaporan ?? 'Triwulan') }}</td>
            </tr>
            @endif
        </table>

        {{-- Section I, II, III (Identifikasi Details) --}}
        @if(in_array($type, ['identifikasi', 'evaluasi', 'daftar']))
            <div class="section-header">KLASIFIKASI & IDENTIFIKASI RISIKO</div>
            <table class="data-table">
                <tr>
                    <th>Kategori Risiko</th>
                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Ruang Lingkup</th>
                    <td>{{ $item->ruangLingkup->nama_ruang_lingkup ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Pernyataan Risiko</th>
                    <td>{{ $item->pernyataan_risiko }}</td>
                </tr>
                <tr>
                    <th>Sebab (Cause)</th>
                    <td>{{ $item->sebab }}</td>
                </tr>
                <tr>
                    <th>Jenis Risiko</th>
                    <td>{{ $item->jenis_risiko == 'UC' ? 'Uncontrollable (UC)' : 'Controllable (C)' }}</td>
                </tr>
                <tr>
                    <th>Dampak (Impact)</th>
                    <td>{{ $item->dampak }}</td>
                </tr>
            </table>
        @endif

        {{-- Section IV (Analisis) --}}
        @if(in_array($type, ['analisis', 'kecukupan', 'evaluasi', 'daftar']))
            @if($item->analisis)
                <div class="section-header">ANALISIS RISIKO & PENGENDALIAN</div>
                <table class="data-table">
                    <tr>
                        <th>Uraian Pengendalian</th>
                        <td>{{ $item->analisis->uraian_pengendalian ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Evaluasi Kontrol</th>
                        <td>
                            Desain: <strong>{{ $item->analisis->desain_pengendalian ?? '-' }}</strong> | 
                            Efektivitas: <strong>{{ $item->analisis->efektifitas_pengendalian ?? '-' }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th style="background-color: #fbfbfb;">Skor & Peringkat</th>
                        <td>
                            @php
                                $score = $item->analisis->skor_risiko ?? 0;
                                $class = $score >= 15 ? 'st' : ($score >= 10 ? 't' : ($score >= 5 ? 's' : ($score >= 3 ? 'r' : 'sr')));
                                $label = $score >= 15 ? 'Sangat Tinggi' : ($score >= 10 ? 'Tinggi' : ($score >= 5 ? 'Sedang' : ($score >= 3 ? 'Rendah' : 'Sangat Rendah')));
                            @endphp
                            <table style="width: 100%; border: none;">
                                <tr>
                                    <td style="border: none; padding: 0; width: 25%; text-align: center;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">PROBABILITAS (P)</div>
                                        <div style="font-size: 11pt; font-weight: bold;">{{ $item->analisis->probabilitas->nilai_probabilitas ?? '-' }}</div>
                                    </td>
                                    <td style="border: none; padding: 0; width: 25%; text-align: center; border-left: 1px solid #eee !important;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">DAMPAK (D)</div>
                                        <div style="font-size: 11pt; font-weight: bold;">{{ $item->analisis->dampak->nilai_dampak ?? '-' }}</div>
                                    </td>
                                    @php
                                        $bgHex = ['st' => '#c00000', 't' => '#ff9900', 's' => '#ffeb3b', 'r' => '#0d6efd', 'sr' => '#198754'];
                                        $currBg = $bgHex[$class];
                                        $currText = ($class == 's') ? '#000' : '#fff';
                                        $labelColor = ($class == 's') ? '#555' : '#eee';
                                    @endphp
                                    <td style="border: none; padding: 5px; width: 20%; text-align: center; border-left: 1px solid #eee !important; background-color: {{ $currBg }};">
                                        <div style="font-size: 7pt; color: {{ $labelColor }}; margin-bottom: 2px;">SKOR TR</div>
                                        <div style="font-size: 11pt; font-weight: bold; color: {{ $currText }};">{{ $score }}</div>
                                    </td>
                                    <td style="border: none; padding: 0; width: 30%; text-align: center; border-left: 1px solid #eee !important;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">PERINGKAT (PR)</div>
                                        <div style="font-size: 10pt; font-weight: bold; color: #333; margin-top: 2px;">{{ $label }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Pemilik Risiko</th>
                        <td>{{ $item->analisis->pemilik_risiko ?? ($item->unit->nama_unit ?? '-') }}</td>
                    </tr>
                </table>
            @else
                <div class="section-header">ANALISIS RISIKO</div>
                <div style="padding: 15px; color: #999; text-align: center; font-style: italic;">Data analisis belum diinput.</div>
            @endif
        @endif

        {{-- Section V (Evaluasi) --}}
        @if(in_array($type, ['evaluasi', 'daftar']))
            @if($item->evaluasi)
                <div class="section-header">PEMANTAUAN & EVALUASI KEJADIAN</div>
                <table class="data-table">
                    <tr>
                        <th>Status Temuan</th>
                        <td>
                            <strong style="color: {{ $item->evaluasi->status_kejadian == 'Ya' ? '#c00000' : '#198754' }}">
                                {{ $item->evaluasi->status_kejadian == 'Ya' ? 'TERDAPAT KEJADIAN RISIKO' : 'TIDAK TERDAPAT KEJADIAN RISIKO' }}
                            </strong>
                        </td>
                    </tr>
                    @if($item->evaluasi->status_kejadian == 'Ya')
                    <tr>
                        <th>Frekuensi Kejadian</th>
                        <td>{{ $item->evaluasi->frekuensi_kejadian ?? '-' }} kali dalam periode ini</td>
                    </tr>
                    <tr>
                        <th>Uraian Kejadian</th>
                        <td>{{ $item->evaluasi->uraian_kejadian ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            @else
                <div class="section-header">EVALUASI KEJADIAN</div>
                <div style="padding: 15px; color: #999; text-align: center; font-style: italic;">Data evaluasi belum diinput.</div>
            @endif
        @endif

        {{-- Section VI (Mitigasi) --}}
        @if(in_array($type, ['kecukupan', 'evaluasi', 'daftar']))
            @if($item->analisisKecukupan || ($item->evaluasi && $item->evaluasi->skor_residu))
                <div class="section-header">RENCANA TINGAK LANJUT & EVALUASI RESIDU</div>
                <table class="data-table">
                    @if($item->analisisKecukupan)
                    <tr>
                        <th>Rencana Kerja</th>
                        <td>{{ $item->analisisKecukupan->uraian_rencana ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pelaksanaan</th>
                        <td>
                            Jadwal: <strong>{{ $item->analisisKecukupan->jadwal ?? '-' }}</strong> | 
                            PJ: <strong>{{ $item->analisisKecukupan->pj_tindak_lanjut ?? '-' }}</strong>
                        </td>
                    </tr>
                    @endif
                    
                    @if($item->evaluasi && $item->evaluasi->skor_residu)
                    <tr>
                        <th style="background-color: #fbfbfb;">Evaluasi Residu</th>
                        <td>
                            @php
                                $resScore = $item->evaluasi->skor_residu ?? 0;
                                $resClass = $resScore >= 15 ? 'st' : ($resScore >= 10 ? 't' : ($resScore >= 5 ? 's' : ($resScore >= 3 ? 'r' : 'sr')));
                                $resLabel = $resScore >= 15 ? 'Sangat Tinggi' : ($resScore >= 10 ? 'Tinggi' : ($resScore >= 5 ? 'Sedang' : ($resScore >= 3 ? 'Rendah' : 'Sangat Rendah')));
                            @endphp
                            <table style="width: 100%; border: none;">
                                <tr>
                                    <td style="border: none; padding: 0; width: 25%; text-align: center;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">PROBABILITAS</div>
                                        <div style="font-size: 11pt; font-weight: bold;">{{ $item->evaluasi->probabilitas->nilai_probabilitas ?? '-' }}</div>
                                    </td>
                                    <td style="border: none; padding: 0; width: 25%; text-align: center; border-left: 1px solid #eee !important;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">DAMPAK</div>
                                        <div style="font-size: 11pt; font-weight: bold;">{{ $item->evaluasi->dampak->nilai_dampak ?? '-' }}</div>
                                    </td>
                                    @php
                                        $resBgHex = ['st' => '#c00000', 't' => '#ff9900', 's' => '#ffeb3b', 'r' => '#0d6efd', 'sr' => '#198754'];
                                        $currResBg = $resBgHex[$resClass];
                                        $currResText = ($resClass == 's') ? '#000' : '#fff';
                                        $resLabelColor = ($resClass == 's') ? '#555' : '#eee';
                                    @endphp
                                    <td style="border: none; padding: 5px; width: 20%; text-align: center; border-left: 1px solid #eee !important; background-color: {{ $currResBg }};">
                                        <div style="font-size: 7pt; color: {{ $resLabelColor }}; margin-bottom: 2px;">SKOR</div>
                                        <div style="font-size: 11pt; font-weight: bold; color: {{ $currResText }};">{{ $resScore }}</div>
                                    </td>
                                    <td style="border: none; padding: 0; width: 30%; text-align: center; border-left: 1px solid #eee !important;">
                                        <div style="font-size: 7pt; color: #888; margin-bottom: 2px;">PERINGKAT</div>
                                        <div style="font-size: 10pt; font-weight: bold; color: #333; margin-top: 2px;">{{ $resLabel }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif
                </table>
            @else
                <div class="section-header">MITIGASI & RESIDU</div>
                <div style="padding: 15px; color: #999; text-align: center; font-style: italic;">Belum terdapat rencana mitigasi atau evaluasi residu.</div>
            @endif
        @endif
    </div>

    <div class="footer">
        Dicetak secara otomatis oleh Risk Register System - RS Azra. Dokumen ini sah tanpa tanda tangan fisik. | {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
