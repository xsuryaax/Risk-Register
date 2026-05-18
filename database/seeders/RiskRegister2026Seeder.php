<?php

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
use Illuminate\Support\Facades\DB;

class RiskRegister2026Seeder extends Seeder
{
    private $counters = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('RISK REGISTER TAHUN 2026.xlsx - All Unit.csv');
        
        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        // Ambil user pertama sebagai default creator
        $user = User::first() ?? User::factory()->create();

        // 1. Bersihkan data lama untuk menghindari duplikasi/kesalahan
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        AnalisisKecukupan::truncate();
        AnalisisRisiko::truncate();
        IdentifikasiRisiko::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $file = fopen($filePath, 'r');
        $row = 0;
        $countSuccess = 0;

        while (($data = fgetcsv($file, 4000, ",")) !== FALSE) {
            $row++;
            
            // Skip 7 baris pertama (Header & Noise)
            if ($row <= 7) continue;
            
            // Skip jika kolom Kegiatan (index 1) kosong
            if (empty(trim($data[1] ?? ''))) continue;

            try {
                // a. Handle Unit (Index 20)
                $unitName = trim($data[20] ?? 'Umum');
                if (empty($unitName)) $unitName = 'Umum';
                $unit = Unit::firstOrCreate(['nama_unit' => $unitName]);

                // b. Handle Kategori (Index 4)
                $katName = trim($data[4] ?? 'Operasional');
                if (empty($katName)) $katName = 'Operasional';
                $kategori = KategoriRisiko::where('nama_kategori', 'like', $katName)->first();
                if (!$kategori) {
                    $kategori = KategoriRisiko::create([
                        'nama_kategori' => $katName,
                        'status_kategori' => 'aktif'
                    ]);
                }

                // c. Handle Ruang Lingkup (Index 5)
                $rlName = trim($data[5] ?? '');
                if (empty($rlName)) $rlName = 'Umum';
                
                $rl = RuangLingkup::where('nama_ruang_lingkup', 'like', $rlName)->first();
                if (!$rl) {
                    $rl = RuangLingkup::create([
                        'nama_ruang_lingkup' => $rlName,
                        'kode_prefix' => strtoupper(substr($rlName, 0, 1)) ?: 'U'
                    ]);
                }

                // d. Generate Kode Risiko (Rule: Huruf Depan Kategori)
                $kode = trim($data[3] ?? '');
                // Jika kode kosong, 'o', 'k', 'r', atau hanya 1 karakter, generate baru
                if (empty($kode) || strlen($kode) <= 1) {
                    $prefix = strtoupper(substr($kategori->nama_kategori, 0, 1));
                    if (empty($prefix)) $prefix = 'R';
                    $year = '2026';
                    
                    if (!isset($this->counters[$prefix])) {
                        $this->counters[$prefix] = IdentifikasiRisiko::where('kode_risiko', 'like', $prefix . '-' . $year . '-%')->count();
                    }
                    
                    $this->counters[$prefix]++;
                    $kode = $prefix . '-' . $year . '-' . str_pad($this->counters[$prefix], 3, '0', STR_PAD_LEFT);
                }

                // e. Simpan Identifikasi Risiko
                $activePeriode = \App\Models\Periode::where('tahun', '2026')->first();
                $identifikasi = IdentifikasiRisiko::create([
                    'unit_id' => $unit->id,
                    'user_id' => $user->id,
                    'kegiatan' => trim($data[1]),
                    'tujuan_kegiatan' => trim($data[2] ?? '-'),
                    'kode_risiko' => $kode,
                    'kategori_risiko_id' => $kategori->id,
                    'ruang_lingkup_id' => $rl ? $rl->id : null,
                    'pernyataan_risiko' => trim($data[6] ?? '-'),
                    'sebab' => trim($data[7] ?? '-'),
                    'jenis_risiko' => trim($data[8] ?? 'C'),
                    'dampak' => trim($data[6] ?? '-'), // Default ke pernyataan jika tidak ada kolom dampak tekstual
                    'triwulan' => ($countSuccess % 4) + 1,
                    'frekuensi_pelaporan' => 'triwulan',
                    'periode_id' => $activePeriode->id ?? null,
                ]);

                // f. Simpan Analisis Risiko
                $probVal = intval($data[9] ?? 1);
                $dampakVal = intval($data[10] ?? 1);
                
                $prob = Probabilitas::where('nilai_probabilitas', $probVal)->first();
                $dmp = Dampak::where('nilai_dampak', $dampakVal)->first();

                // Mapping P x D = Skor
                $skor = $probVal * $dampakVal;
                
                // Logika Peringkat Berdasarkan Skor (Sinkronisasi dengan Dashboard)
                $peringkat = 'RENDAH';
                if ($skor >= 20) $peringkat = 'SANGAT TINGGI';
                elseif ($skor >= 13) $peringkat = 'TINGGI';
                elseif ($skor >= 5) $peringkat = 'SEDANG';

                AnalisisRisiko::create([
                    'identifikasi_risiko_id' => $identifikasi->id,
                    'uraian_pengendalian' => trim($data[11] ?? '-'),
                    'desain_pengendalian' => trim($data[12] ?? 'Tidak Ada'),
                    'efektifitas_pengendalian' => trim($data[13] ?? 'Tidak Efektif'),
                    'probabilitas_id' => $prob ? $prob->id : null,
                    'dampak_id' => $dmp ? $dmp->id : null,
                    'skor_risiko' => $skor,
                    'peringkat_risiko' => $peringkat,
                    'pemilik_risiko' => trim($data[18] ?? $unitName),
                ]);

                // g. Simpan Analisis Kecukupan (Rencana Pengendalian)
                AnalisisKecukupan::create([
                    'identifikasi_risiko_id' => $identifikasi->id,
                    'uraian_rencana' => trim($data[16] ?? '-'),
                    'jadwal' => trim($data[17] ?? '-'),
                    'pj_tindak_lanjut' => trim($data[19] ?? '-'),
                ]);

                $countSuccess++;

            } catch (\Exception $e) {
                $this->command->error("Gagal mengimpor baris $row: " . $e->getMessage());
                // Opsional: dump data jika ingin debug lebih lanjut
                // $this->command->warn(json_encode($data));
            }
        }

        fclose($file);
        $this->command->info("Selesai! Berhasil mengimpor $countSuccess data risiko.");
    }
}
