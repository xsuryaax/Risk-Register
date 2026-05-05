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
        $unit = Unit::where('nama_unit', 'IT')->first() ?? Unit::first() ?? Unit::create(['nama_unit' => 'IT']);

        $kategoriOperasional = KategoriRisiko::where('nama_kategori', 'Operasional')->first();
        $kategoriLegal = KategoriRisiko::where('nama_kategori', 'Legal')->first();
        $kategoriKeuangan = KategoriRisiko::where('nama_kategori', 'Keuangan')->first();
        
        $lingkupBisnis = RuangLingkup::where('nama_ruang_lingkup', 'Bisnis RS')->first();
        $lingkupFasilitas = RuangLingkup::where('nama_ruang_lingkup', 'Fasilitas RS')->first();

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
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Pemeliharaan PC dan perangkat keras lainnya belum dilakukan secara rutin',
                'tujuan_kegiatan' => 'Agar lifetime komputer lebih lama',
                'kode_risiko' => 'B-2026-004',
                'kategori_risiko_id' => $kategoriKeuangan->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'pemeliharaan PC dan perangkat keras dilakukan secara rutin agar umur PC lebih lama dan awet',
                'sebab' => 'belum ada jadwal rutin',
                'jenis_risiko' => 'C',
                'dampak' => 'perangkat tidak awet dan berkurang lifetimenya',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Akun user dokter untuk akses ke teramedik diketahui oleh perawat/tenaga medis lainnya',
                'tujuan_kegiatan' => 'Kewenangan akses harus dapat dipastikan sesuai dengan levelnya masing-masing',
                'kode_risiko' => 'B-2026-005',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'penggunaan akun user akses tidak sesuai hak, menyalahi aturan penggunaan kewenangan',
                'sebab' => 'belum ada aturan/SPO yang mengatur',
                'jenis_risiko' => 'C',
                'dampak' => 'potensi penyalahgunaan akses',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Permintaan perubahan/perbaikan sistem membutuhkan waktu yang lama',
                'tujuan_kegiatan' => 'Memastikan setiap permintaan dapat terjangkau waktunya sesuai kesepakatan dengan vendor',
                'kode_risiko' => 'B-2026-006',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'Permintaan perubahan/perbaikan sistem yang lama akan menghambat operasional',
                'sebab' => 'ketentuan mandays pengerjaan perbaikan yang perlu disampaikan kembali',
                'jenis_risiko' => 'UC',
                'dampak' => 'Menghambat operasional',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Terjadinya downtime sistem teramedik/core sistem belum tersedia',
                'tujuan_kegiatan' => 'Mengantisipasi terjadinya downtime yang dapat mengganggu kegiatan operasional',
                'kode_risiko' => 'B-2026-007',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'jika downtime sistem tidak diantisipasi akan mengganggu kelancaran operasional',
                'sebab' => 'belum ada aturan/SPO yang mengatur',
                'jenis_risiko' => 'C',
                'dampak' => 'Menghambat operasional',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Sosialisasi teramedik tentang penginputan SOAP',
                'tujuan_kegiatan' => 'Meminimalisir terjadinya kesalahan penginputan SOAP',
                'kode_risiko' => 'B-2026-008',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupBisnis->id ?? 1,
                'pernyataan_risiko' => 'sosialisasi modul teramedik tidak dilakukan secara berkala berpotensi terjadi kelupaan cara penginputan',
                'sebab' => 'jadwal sosialisasi belum ada',
                'jenis_risiko' => 'C',
                'dampak' => 'potensi kesalahan penginputan',
                'user_id' => $user->id,
            ],
            [
                'unit_id' => $unit->id,
                'kegiatan' => 'Mengantisipasi Pemadaman listrik secara tiba-tiba/tidak terencana',
                'tujuan_kegiatan' => 'Memastikan operasional tidak terganggu',
                'kode_risiko' => 'F-2026-001',
                'kategori_risiko_id' => $kategoriOperasional->id ?? 1,
                'ruang_lingkup_id' => $lingkupFasilitas->id ?? 1,
                'pernyataan_risiko' => 'Tidak mengantisipasi kejadian pemadaman listrik secara tiba-tiba berpotensi menyebabkan server juga mati',
                'sebab' => 'belum ada aturan/SPO yang mengatur',
                'jenis_risiko' => 'C',
                'dampak' => 'kerusakan perangkat keras komputer dan server',
                'user_id' => $user->id,
            ],
        ];

        foreach ($risks as $risk) {
            IdentifikasiRisiko::create($risk);
        }
    }
}
