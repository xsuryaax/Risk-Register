<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Strategis',
                'keterangan' => 'Implementasi rencana strategis, rencana bisnis. Terkait dengan tujuan organisasi. Risiko yang disebabkan oleh adanya penetapan strategi organisasi atau strategi dari internal maupun eksternal organisasi yang berdampak langsung terhadap organisasi.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Keuangan',
                'keterangan' => 'Segala sesuatu yang menimbulkan tekanan terhadap pendapatan dan belanja organisasi. Perubahan nilai uang, perubahan sistem simpan pinjam, klaim dll.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Kebijakan',
                'keterangan' => 'Adanya penetapan kebijakan organisasi baik internal maupun eksternal yang berdampak langsung terhadap organisasi.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Kepatuhan',
                'keterangan' => 'Kepatuhan terhadap hukum, Risiko yang disebabkan organisasi atau pihak eksternal tidak mematuhi dan/atau tidak melaksanakan peraturan perundang-undangan dan ketentuan lain yang berlaku.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Legal',
                'keterangan' => 'Tuntutan hukum dari konsumen, pemasok dll terhadap organisasi.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Fraud',
                'keterangan' => 'Disebabkan oleh kecurangan yang disengaja oleh pihak internal yang merugikan keuangan negara atau organisasi.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Reputasi',
                'keterangan' => 'Penurunan nilai yang berdampak pada kehilangan percaya diri, dll. Image yang dirasakan oleh masyarakat. Risiko yang disebabkan oleh menurunnya tingkat kepercayaan public / masyarakat, pemangku kepentingan eksternal yang bersumber dari persepsi negatif terhadap organisasi.',
                'status_kategori' => 'aktif',
            ],
            [
                'nama_kategori' => 'Operasional',
                'keterangan' => 'Rencana pengembangan untuk mencapai tujuan. Penyediaan layanan, Credensialing dan staffing, klinis, parkir, laboraorium, radiologi, program yang dipersyaratkan dll. Disebabkan oleh ketidakcukupan dan/atau tidak berfungsinya proses internal, kesalahan manusia dan kegagalan sistem. Disebabkan adanya kejadian eksternal yang mempengaruhi operasional organisasi.',
                'status_kategori' => 'aktif',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\KategoriRisiko::create($category);
        }
    }
}
