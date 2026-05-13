<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluasiRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;

class EvaluasiRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $identifikasi = IdentifikasiRisiko::all();

        if ($identifikasi->isEmpty()) {
            return;
        }

        $evaluasiData = [
            [
                'kegiatan_idx' => 0,
                'p_residu' => 2,
                'd_residu' => 2,
                'skor_residu' => 4,
                'penurunan' => 33,
            ],
            [
                'kegiatan_idx' => 1,
                'p_residu' => 3,
                'd_residu' => 4,
                'skor_residu' => 12,
                'penurunan' => 40,
            ],
            [
                'kegiatan_idx' => 2,
                'p_residu' => 2,
                'd_residu' => 2,
                'skor_residu' => 4,
                'penurunan' => 33,
            ],
            [
                'kegiatan_idx' => 3,
                'p_residu' => 3,
                'd_residu' => 3,
                'skor_residu' => 9,
                'penurunan' => 0,
            ],
            [
                'kegiatan_idx' => 4,
                'p_residu' => 4,
                'd_residu' => 2,
                'skor_residu' => 8,
                'penurunan' => 33,
            ],
            [
                'kegiatan_idx' => 5,
                'p_residu' => 2,
                'd_residu' => 2,
                'skor_residu' => 4,
                'penurunan' => 67,
            ],
            [
                'kegiatan_idx' => 6,
                'p_residu' => 4,
                'd_residu' => 4,
                'skor_residu' => 16,
                'penurunan' => 36,
            ],
            [
                'kegiatan_idx' => 7,
                'p_residu' => 2,
                'd_residu' => 2,
                'skor_residu' => 4,
                'penurunan' => 0,
            ],
            [
                'kegiatan_idx' => 8,
                'p_residu' => 4,
                'd_residu' => 4,
                'skor_residu' => 16,
                'penurunan' => 0,
            ],
            [
                'kegiatan_idx' => 9,
                'p_residu' => 2,
                'd_residu' => 3,
                'skor_residu' => 6,
                'penurunan' => 60,
            ],
            [
                'kegiatan_idx' => 10,
                'p_residu' => 3,
                'd_residu' => 2,
                'skor_residu' => 6,
                'penurunan' => 50,
            ],
            [
                'kegiatan_idx' => 11,
                'p_residu' => 1,
                'd_residu' => 5,
                'skor_residu' => 5,
                'penurunan' => 50,
            ],
        ];

        foreach ($evaluasiData as $data) {
            if (isset($identifikasi[$data['kegiatan_idx']])) {
                $idRisk = $identifikasi[$data['kegiatan_idx']];
                
                $probId = Probabilitas::where('nilai_probabilitas', $data['p_residu'])->first()->id ?? 1;
                $dampId = Dampak::where('nilai_dampak', $data['d_residu'])->first()->id ?? 1;

                // Tentukan peringkat residu berdasarkan skor
                $skor = $data['skor_residu'];
                $peringkat = 'RENDAH';
                if ($skor >= 15) {
                    $peringkat = 'SANGAT TINGGI';
                } elseif ($skor >= 10) {
                    $peringkat = 'TINGGI';
                } elseif ($skor >= 5) {
                    $peringkat = 'SEDANG';
                } elseif ($skor >= 3) {
                    $peringkat = 'RENDAH';
                } else {
                    $peringkat = 'SANGAT RENDAH';
                }

                EvaluasiRisiko::create([
                    'identifikasi_risiko_id' => $idRisk->id,
                    'probabilitas_residu_id' => $probId,
                    'dampak_residu_id' => $dampId,
                    'skor_residu' => $skor,
                    'peringkat_residu' => $peringkat,
                    'penurunan_persen' => $data['penurunan'],
                ]);
            }
        }
    }
}
