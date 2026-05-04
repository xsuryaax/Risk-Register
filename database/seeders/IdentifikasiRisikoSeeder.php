<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IdentifikasiRisiko;
use App\Models\Unit;
use App\Models\User;
use App\Models\KategoriRisiko;
use App\Models\RuangLingkup;

class IdentifikasiRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa data relasi untuk disematkan
        $user = User::first() ?? User::factory()->create();
        $unit = Unit::first() ?? Unit::create(['nama_unit' => 'IT']);

        $kategoriOperasional = KategoriRisiko::where('nama_kategori', 'Operasional')->first();
        $kategoriLegal = KategoriRisiko::where('nama_kategori', 'Legal')->first();
        $lingkupBisnis = RuangLingkup::where('nama_ruang_lingkup', 'Bisnis RS')->first();

        $risks = [
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Belum terinstallnya anti virus license',
                'tujuan_kegiatan' => 'Memastikan potensi penyerangan dari virus dan sejenisnya',
                'kode_risiko' => 'B-2026-001',
                'kategori_risiko_id' => $kategoriLegal->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'belum adanya anti virus lisensi, potensi data terserang virus lebih besar',
                'sebab' => 'belum ada lisensi',
                'jenis_risiko' => 'C',
                'dampak' => 'terkena serangan virus dan potensi kehilangan data',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Proses backdata belum dilakukan secara rutin',
                'tujuan_kegiatan' => 'Memastikan data terbackup dengan baik',
                'kode_risiko' => 'B-2026-002',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'proses backup data belum dilakukan secara rutin, potensi kehilangan data lebih besar',
                'sebab' => 'belum rutin dilakukan',
                'jenis_risiko' => 'C',
                'dampak' => 'kehilangan data - data penting',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Lisensi windows dan microsoft office belum secara menyeluruh',
                'tujuan_kegiatan' => 'Memastikan sistem informasi yang digunakan original',
                'kode_risiko' => 'B-2026-003',
                'kategori_risiko_id' => $kategoriLegal->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'karena belum ada lisensi windows dan office secara menyeluruh berpotensi rawan virus',
                'sebab' => 'belum ada lisensi',
                'jenis_risiko' => 'C',
                'dampak' => 'pelanggaran hak cipta dan resiko denda',
                'user_id' => $user->id,
            ],
        ];

        foreach ($risks as $risk) {
            IdentifikasiRisiko::create($risk);
        }
    }
}
