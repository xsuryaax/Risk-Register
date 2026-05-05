<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnalisisRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;

class AnalisisRisikoSeeder extends Seeder
{
    public function run(): void
    {
        $identifikasi = IdentifikasiRisiko::all();

        if ($identifikasi->isEmpty()) {
            return;
        }

        // Mapping data analisis berdasarkan urutan identifikasi
        $analisisData = [
            [
                'kegiatan_idx' => 0, // Belum terinstallnya anti virus
                'uraian' => 'Belum ada',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 2,
                'd' => 3,
                'skor' => 6,
                'peringkat' => 'SEDANG',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 1, // Proses backdata
                'uraian' => 'Masih berupa anjuran dan belum berupa panduan atau SPO',
                'desain' => 'Tidak',
                'efektifitas' => 'Efektif',
                'p' => 4,
                'd' => 5,
                'skor' => 20,
                'peringkat' => 'SANGAT TINGGI',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 2, // Lisensi windows
                'uraian' => 'Belum ada',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 2,
                'd' => 3,
                'skor' => 6,
                'peringkat' => 'SEDANG',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 3, // Pemeliharaan PC
                'uraian' => 'Masih berupa anjuran dan belum berupa panduan atau SPO',
                'desain' => 'Tidak',
                'efektifitas' => 'Efektif',
                'p' => 3,
                'd' => 3,
                'skor' => 9,
                'peringkat' => 'SEDANG',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 4, // Akun user dokter
                'uraian' => 'Masih berupa anjuran dan belum berupa panduan atau SPO',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 3,
                'd' => 4,
                'skor' => 12,
                'peringkat' => 'TINGGI',
                'pemilik' => 'All',
            ],
            [
                'kegiatan_idx' => 5, // Permintaan perubahan
                'uraian' => 'Masih berupa anjuran dan belum berupa panduan atau SPO',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 3,
                'd' => 4,
                'skor' => 12,
                'peringkat' => 'TINGGI',
                'pemilik' => 'All',
            ],
            [
                'kegiatan_idx' => 6, // Downtime sistem
                'uraian' => 'SPO',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 5,
                'd' => 5,
                'skor' => 25,
                'peringkat' => 'SANGAT TINGGI',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 7, // Sosialisasi teramedik
                'uraian' => 'Belum ada',
                'desain' => 'Tidak',
                'efektifitas' => 'Efektif',
                'p' => 2,
                'd' => 2,
                'skor' => 4,
                'peringkat' => 'RENDAH',
                'pemilik' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 8, // Pemadaman listrik
                'uraian' => 'belum ada',
                'desain' => 'Tidak',
                'efektifitas' => 'Kurang Efektif',
                'p' => 4,
                'd' => 4,
                'skor' => 16,
                'peringkat' => 'SANGAT TINGGI',
                'pemilik' => 'SIRS',
            ],
        ];

        foreach ($analisisData as $data) {
            if (isset($identifikasi[$data['kegiatan_idx']])) {
                $idRisk = $identifikasi[$data['kegiatan_idx']];
                
                // Cari ID Probabilitas & Dampak berdasarkan nilai
                $probId = Probabilitas::where('nilai_probabilitas', $data['p'])->first()->id ?? 1;
                $dampId = Dampak::where('nilai_dampak', $data['d'])->first()->id ?? 1;

                AnalisisRisiko::create([
                    'identifikasi_risiko_id' => $idRisk->id,
                    'uraian_pengendalian' => $data['uraian'],
                    'desain_pengendalian' => $data['desain'],
                    'efektifitas_pengendalian' => $data['efektifitas'],
                    'probabilitas_id' => $probId,
                    'dampak_id' => $dampId,
                    'skor_risiko' => $data['skor'],
                    'peringkat_risiko' => $data['peringkat'],
                    'pemilik_risiko' => $data['pemilik'],
                ]);
            }
        }
    }
}
