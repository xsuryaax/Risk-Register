<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProbabilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $probabilities = [
            [
                'nama_probabilitas' => 'Hampir Tidak Terjadi',
                'nilai_probabilitas' => 1,
                'warna' => '#198754', // Green Bold
                'keterangan' => 'Peristiwa hanya akan timbul pada kondisi yang luar biasa. Terjadi dalam lebih dari 5 tahun (1-5%).',
                'status_probabilitas' => 'aktif',
            ],
            [
                'nama_probabilitas' => 'Jarang Terjadi',
                'nilai_probabilitas' => 2,
                'warna' => '#0dcaf0', // Cyan/Blue Bold
                'keterangan' => 'Peristiwa diharapkan tidak terjadi. Terjadi dalam 2-5 tahun (6-20%).',
                'status_probabilitas' => 'aktif',
            ],
            [
                'nama_probabilitas' => 'Kadang Terjadi',
                'nilai_probabilitas' => 3,
                'warna' => '#ffc107', // Yellow Bold
                'keterangan' => 'Peristiwa kadang-kadang bisa terjadi. Terjadi tiap 1-2 tahun (21-50%).',
                'status_probabilitas' => 'aktif',
            ],
            [
                'nama_probabilitas' => 'Sering Terjadi',
                'nilai_probabilitas' => 4,
                'warna' => '#fd7e14', // Orange Bold
                'keterangan' => 'Peristiwa sangat mungkin terjadi pada sebagian kondisi. Terjadi beberapa kali per tahun (51-80%).',
                'status_probabilitas' => 'aktif',
            ],
            [
                'nama_probabilitas' => 'Hampir Pasti Terjadi',
                'nilai_probabilitas' => 5,
                'warna' => '#dc3545', // Red Bold
                'keterangan' => 'Peristiwa selalu terjadi hampir pada setiap kondisi. Terjadi beberapa kali per bulan (81-100%).',
                'status_probabilitas' => 'aktif',
            ],
        ];

        foreach ($probabilities as $probability) {
            \App\Models\Probabilitas::create($probability);
        }
    }
}
