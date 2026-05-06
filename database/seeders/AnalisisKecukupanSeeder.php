<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnalisisKecukupan;
use App\Models\IdentifikasiRisiko;

class AnalisisKecukupanSeeder extends Seeder
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

        $kecukupanData = [
            [
                'kegiatan_idx' => 0,
                'uraian' => 'Pengajuan pembelian anti virus lisensi',
                'jadwal' => 'Tentative',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 1,
                'uraian' => 'Pembuatan SPO dan anjuran kepada user',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 2,
                'uraian' => 'Pengajuan pembelian sistem operasi dan microsoft office',
                'jadwal' => 'Tentative',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 3,
                'uraian' => 'Panduan dan penjadwalan',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 4,
                'uraian' => 'Pembuatan SPO dan anjuran kepada user',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 5,
                'uraian' => 'Dibuatkan laporan sementara',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 6,
                'uraian' => 'Pembuatan SPO dan sosialisasi kepada user',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 7,
                'uraian' => 'Timetable rencana pelatihan kepda dokter',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 8,
                'uraian' => 'Membuata panduan dan SPO untuk mengatisipasi terjadinya pemadaman baik terencana maupun tidak terencana',
                'jadwal' => 'Setiap bulan',
                'pj' => 'SIRS',
            ],
            [
                'kegiatan_idx' => 9,
                'uraian' => 'Implementasi sistem backup otomatis terjadwal ke server storage terpisah',
                'jadwal' => 'Mingguan',
                'pj' => 'IT Admin',
            ],
            [
                'kegiatan_idx' => 10,
                'uraian' => 'Audit lisensi menyeluruh dan pengajuan anggaran lisensi bertahap',
                'jadwal' => 'Kuartalan',
                'pj' => 'IT Manager',
            ],
            [
                'kegiatan_idx' => 11,
                'uraian' => 'Pemasangan access control (fingerprint/card) di pintu ruang server',
                'jadwal' => 'Satu Kali',
                'pj' => 'GA & IT',
            ],
        ];

        foreach ($kecukupanData as $data) {
            if (isset($identifikasi[$data['kegiatan_idx']])) {
                AnalisisKecukupan::create([
                    'identifikasi_risiko_id' => $identifikasi[$data['kegiatan_idx']]->id,
                    'uraian_rencana' => $data['uraian'],
                    'jadwal' => $data['jadwal'],
                    'pj_tindak_lanjut' => $data['pj'],
                ]);
            }
        }
    }
}
