<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnalisisKecukupan;
use App\Models\IdentifikasiRisiko;

class AnalisisKecukupanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua risiko yang sudah memiliki analisis
        $risks = IdentifikasiRisiko::has('analisis')->get();

        foreach ($risks as $risk) {
            AnalisisKecukupan::updateOrCreate(
                ['identifikasi_risiko_id' => $risk->id],
                [
                    'uraian_rencana' => 'Implementasi sistem monitoring otomatis dan pengawasan berkala melalui dashboard real-time.',
                    'jadwal' => 'Triwulan III - 2026',
                    'pj_tindak_lanjut' => 'Kepala Unit ' . ($risk->analisis->pemilik_risiko ?? 'Terkait'),
                ]
            );
        }
    }
}
