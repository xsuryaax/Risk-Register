<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $p2026 = \App\Models\Periode::create([
            'tahun' => '2026',
            'status' => true,
            'keterangan' => 'Periode Berjalan'
        ]);

        \App\Models\Periode::create([
            'tahun' => '2027',
            'status' => false,
            'keterangan' => 'Periode Mendatang'
        ]);

        // Assign existing risks to 2026
        \App\Models\IdentifikasiRisiko::whereNull('periode_id')->update(['periode_id' => $p2026->id]);
    }
}
