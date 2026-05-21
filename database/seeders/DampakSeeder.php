<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dampak;

class DampakSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_dampak' => 'Sangat Rendah',
                'nilai_dampak' => 1,
                'warna' => '#198754', // Green Bold
                'area_dampak' => "Tidak berdampak pada pencapaian tujuan instansi / kegiatan secara umum\nAgak mengganggu pelayanan\nDampaknya dapat ditangani pada tahap kegiatan rutin\nKerugian kurang material dan tidak mempengaruhi stakeholder",
                'cidera_pasien' => 'Tidak cidera',
                'pelayanan_operasional' => 'Terhenti lebih dari 1 jam',
                'biaya_keuangan' => 'Kerugian kecil',
                'publikasi' => 'Rumor',
                'reputasi' => 'Rumor',
                'status_dampak' => 'aktif',
            ],
            [
                'nama_dampak' => 'Rendah',
                'nilai_dampak' => 2,
                'warna' => '#0dcaf0', // Cyan/Blue Bold
                'area_dampak' => "Mengganggu pencapaian tujuan instansi / kegiatan meskipun tidak signifikan\nCukup mengganggu jalannya pelayanan\nMengancam efisiensi dan efektivitas beberapa aspek program\nKerugian kurang material dan sedikit mempengaruhi stakeholder",
                'cidera_pasien' => 'Dapat diatasi dengan pertolongan pertama',
                'pelayanan_operasional' => 'Terhenti lebih dari 8 jam',
                'biaya_keuangan' => 'Kerugian lebih dari 0,1% anggaran',
                'publikasi' => 'Media lokal, waktu singkat',
                'reputasi' => 'Dampak kecil terhadap moril karyawan dan kepercayaan masyarakat',
                'status_dampak' => 'aktif',
            ],
            [
                'nama_dampak' => 'Sedang',
                'nilai_dampak' => 3,
                'warna' => '#ffc107', // Yellow Bold
                'area_dampak' => "Mengganggu pencapaian tujuan instansi / kegiatan secara signifikan\nMengganggu kegiatan pelayanan secara signifikan\nMengganggu administrasi program\nKerugian keuangan cukup besar",
                'cidera_pasien' => 'Berkurangnya fungsi motorik / sensorik. Setiap kasus yang memperpanjang perawatan',
                'pelayanan_operasional' => 'Terhenti lebih dari 1 hari',
                'biaya_keuangan' => 'Kerugian lebih dari 0,25% anggaran',
                'publikasi' => 'Media lokal, waktu lama',
                'reputasi' => 'Dampak bermakna terhadap moril karyawan dan kepercayaan masyarakat',
                'status_dampak' => 'aktif',
            ],
            [
                'nama_dampak' => 'Tinggi',
                'nilai_dampak' => 4,
                'warna' => '#fd7e14', // Orange Bold
                'area_dampak' => "Sebagian tujuan instansi / kegiatan gagal dilaksanakan\nTerganggunya pelayanan lebih dari 2 hari tetapi kurang dari 1 minggu\nMengancam fungsi program yang efektif dan organisasi\nKerugian besar bagi organisasi dari segi keuangan maupun non keuangan",
                'cidera_pasien' => 'Cidera luas. Kehilangan fungsi utama secara permanen',
                'pelayanan_operasional' => 'Terhenti lebih dari 1 minggu',
                'biaya_keuangan' => 'Kerugian lebih dari 0,5% anggaran',
                'publikasi' => 'Media nasional kurang dari 3 hari',
                'reputasi' => 'Dampak serius terhadap moril karyawan dan kepercayaan masyarakat',
                'status_dampak' => 'aktif',
            ],
            [
                'nama_dampak' => 'Sangat Tinggi',
                'nilai_dampak' => 5,
                'warna' => '#dc3545', // Red Bold
                'area_dampak' => "Sebagian besar tujuan instansi/kegiatan gagal dilaksanakan\nTerganggunya pelayanan lebih dari 1 minggu\nMengancam program dan organisasi serta stakeholders\nKerugian sangat besar bagi organisasi dari segi keuangan maupun non keuangan",
                'cidera_pasien' => 'Kematian',
                'pelayanan_operasional' => 'Terhenti permanen',
                'biaya_keuangan' => 'Kerugian lebih dari 1% anggaran',
                'publikasi' => 'Media nasional lebih dari 3 hari',
                'reputasi' => 'Menjadi masalah berat bagi public relation (PR)',
                'status_dampak' => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            Dampak::create($item);
        }
    }
}
