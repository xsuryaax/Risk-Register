<?php

$csvFile = 'RISK REGISTER TAHUN 2026.xlsx - All Unit.csv';
if (!file_exists($csvFile)) {
    die("File CSV tidak ditemukan!");
}

$handle = fopen($csvFile, 'r');
$risks = [];
$row = 0;

while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
    $row++;
    if ($row <= 7) continue;
    if (empty(trim($data[1] ?? ''))) continue;

    $risks[] = [
        'unit' => trim($data[20] ?? 'Umum') ?: 'Umum',
        'kegiatan' => trim($data[1]),
        'tujuan' => trim($data[2] ?? '-'),
        'kategori' => trim($data[4] ?? 'Operasional') ?: 'Operasional',
        'lingkup' => trim($data[5] ?? 'Umum') ?: 'Umum',
        'pernyataan' => trim($data[6] ?? '-'),
        'sebab' => trim($data[7] ?? '-'),
        'jenis' => trim($data[8] ?? 'C'),
        'prob' => intval($data[9] ?? 1),
        'dmp' => intval($data[10] ?? 1),
        'uraian_pengendalian' => trim($data[11] ?? '-'),
        'desain' => trim($data[12] ?? '-'),
        'efektifitas' => trim($data[13] ?? '-'),
        'rencana' => trim($data[16] ?? '-'),
        'jadwal' => trim($data[17] ?? '-'),
        'pemilik' => trim($data[18] ?? ''),
        'pj' => trim($data[19] ?? '-'),
    ];
}
fclose($handle);

$dataExport = var_export($risks, true);

$content = "<?php

namespace Database\Seeders;

use App\Models\AnalisisRisiko;
use App\Models\AnalisisKecukupan;
use App\Models\IdentifikasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\RuangLingkup;
use App\Models\Unit;
use App\Models\Probabilitas;
use App\Models\Dampak;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IdentifikasiRisikoSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        AnalisisKecukupan::truncate();
        AnalisisRisiko::truncate();
        IdentifikasiRisiko::truncate();
        Schema::enableForeignKeyConstraints();

        \$user = User::first() ?? User::factory()->create();

        // Data 534 Records
        \$risks = $dataExport;

        \$counters = [];
        
        // Cache master data to avoid redundant queries
        \$unitMap = Unit::all()->pluck('id', 'nama_unit')->toArray();
        \$katMap = KategoriRisiko::all()->pluck('id', 'nama_kategori')->toArray();
        \$rlMap = RuangLingkup::all()->pluck('id', 'nama_ruang_lingkup')->toArray();
        \$probMap = Probabilitas::all()->pluck('id', 'nilai_probabilitas')->toArray();
        \$dmpMap = Dampak::all()->pluck('id', 'nilai_dampak')->toArray();

        foreach (\$risks as \$item) {
            // Map strictly to existing Unit, if not exactly matched, leave it empty (null)
            \$unitId = \$unitMap[\$item['unit']] ?? null;

            // Get or Create Kategori
            if (!isset(\$katMap[\$item['kategori']])) {
                \$k = KategoriRisiko::create(['nama_kategori' => \$item['kategori'], 'status_kategori' => 'aktif']);
                \$katMap[\$item['kategori']] = \$k->id;
            }
            \$katId = \$katMap[\$item['kategori']];

            // Get or Create Ruang Lingkup
            if (!isset(\$rlMap[\$item['lingkup']])) {
                \$rl = RuangLingkup::create([
                    'nama_ruang_lingkup' => \$item['lingkup'],
                    'kode_prefix' => strtoupper(substr(\$item['lingkup'], 0, 1)) ?: 'U'
                ]);
                \$rlMap[\$item['lingkup']] = \$rl->id;
            }
            \$rlId = \$rlMap[\$item['lingkup']];

            // Generate clean unique code
            \$prefix = strtoupper(substr(\$item['kategori'], 0, 1)) ?: 'R';
            if (!isset(\$counters[\$prefix])) \$counters[\$prefix] = 0;
            \$counters[\$prefix]++;
            \$kode = \$prefix . '-2026-' . str_pad(\$counters[\$prefix], 3, '0', STR_PAD_LEFT);

            // Create Identifikasi
            \$identifikasi = IdentifikasiRisiko::create([
                'unit_id' => \$unitId,
                'user_id' => \$user->id,
                'kegiatan' => \$item['kegiatan'],
                'tujuan_kegiatan' => \$item['tujuan'],
                'kode_risiko' => \$kode,
                'kategori_risiko_id' => \$katId,
                'ruang_lingkup_id' => \$rlId,
                'pernyataan_risiko' => \$item['pernyataan'],
                'sebab' => \$item['sebab'],
                'jenis_risiko' => \$item['jenis'],
                'dampak' => \$item['pernyataan'],
            ]);

            // Create Analisis
            \$skor = \$item['prob'] * \$item['dmp'];
            \$peringkat = 'RENDAH';
            if (\$skor >= 20) \$peringkat = 'SANGAT TINGGI';
            elseif (\$skor >= 13) \$peringkat = 'TINGGI';
            elseif (\$skor >= 5) \$peringkat = 'SEDANG';

            // Data Cleaning for Enums
            \$desain = trim(\$item['desain']);
            if (stripos(\$desain, 'Ada') !== false) \$desain = 'Ada';
            else \$desain = 'Tidak';

            \$efektifitas = trim(\$item['efektifitas']);
            if (stripos(\$efektifitas, 'Kurang') !== false) \$efektifitas = 'Kurang Efektif';
            elseif (stripos(\$efektifitas, 'Tidak') !== false) \$efektifitas = 'Tidak Efektif';
            elseif (stripos(\$efektifitas, 'Efektif') !== false || stripos(\$efektifitas, 'Cukup') !== false) \$efektifitas = 'Efektif';
            else \$efektifitas = 'Tidak Efektif';

            AnalisisRisiko::create([
                'identifikasi_risiko_id' => \$identifikasi->id,
                'uraian_pengendalian' => \$item['uraian_pengendalian'],
                'desain_pengendalian' => \$desain,
                'efektifitas_pengendalian' => \$efektifitas,
                'probabilitas_id' => \$probMap[\$item['prob']] ?? null,
                'dampak_id' => \$dmpMap[\$item['dmp']] ?? null,
                'skor_risiko' => \$skor,
                'peringkat_risiko' => \$peringkat,
                'pemilik_risiko' => \$item['pemilik'] ?: \$item['unit'],
            ]);

            // Create Analisis Kecukupan
            AnalisisKecukupan::create([
                'identifikasi_risiko_id' => \$identifikasi->id,
                'uraian_rencana' => \$item['rencana'],
                'jadwal' => \$item['jadwal'],
                'pj_tindak_lanjut' => \$item['pj'],
            ]);
        }
    }
}
";

file_put_contents('IdentifikasiRisikoSeeder_Final.php', $content);
echo "Selesai! File IdentifikasiRisikoSeeder_Final.php berhasil dibuat.";
