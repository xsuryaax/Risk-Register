<?php

namespace Database\Seeders;

use App\Models\AnalisisRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;
use Illuminate\Database\Seeder;

class AnalisisRisikoSeeder extends Seeder
{
    public function run(): void
    {
        $identifications = IdentifikasiRisiko::all();
        $prob3 = Probabilitas::where('nilai_probabilitas', 3)->first();
        $dam3 = Dampak::where('nilai_dampak', 3)->first();

        foreach ($identifications as $index => $item) {
            // Only fill the first 2 to show partial data
            if ($index >= 2) {
                break;
            }

            $score = 9 + $index; 
            $ranking = 'SEDANG';
            if ($score >= 20) $ranking = 'SANGAT TINGGI';
            elseif ($score >= 13) $ranking = 'TINGGI';

            AnalisisRisiko::create([
                'identifikasi_risiko_id' => $item->id,
                'uraian_pengendalian' => 'Sudah ada prosedur standar namun belum optimal',
                'desain_pengendalian' => 'Ada',
                'efektifitas_pengendalian' => 'Kurang Efektif',
                'probabilitas_id' => $prob3->id ?? 1,
                'dampak_id' => $dam3->id ?? 1,
                'skor_risiko' => $score,
                'peringkat_risiko' => $ranking,
                'pemilik_risiko' => 'SIRS',
            ]);
        }
    }
}
