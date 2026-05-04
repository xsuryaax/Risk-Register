<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangLingkupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scopes = [
            [
                'nama_ruang_lingkup' => 'Pasien',
                'kode_prefix' => 'P',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan pasien',
                'status_ruang_lingkup' => 'aktif',
            ],
            [
                'nama_ruang_lingkup' => 'Staf Medis',
                'kode_prefix' => 'S',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan staf medis dalam hal ini para dokter',
                'status_ruang_lingkup' => 'aktif',
            ],
            [
                'nama_ruang_lingkup' => 'Tenaga Kesehatan dan tenaga lainnya',
                'kode_prefix' => 'N',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan staf yang bekerja di rumah sakit selain dokter',
                'status_ruang_lingkup' => 'aktif',
            ],
            [
                'nama_ruang_lingkup' => 'Fasilitas RS',
                'kode_prefix' => 'F',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan fasilitas yang dimiliki oleh rumah sakit',
                'status_ruang_lingkup' => 'aktif',
            ],
            [
                'nama_ruang_lingkup' => 'Lingkungan RS',
                'kode_prefix' => 'L',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan lingkungan baik di dalam maupun sekitar rumah sakit',
                'status_ruang_lingkup' => 'aktif',
            ],
            [
                'nama_ruang_lingkup' => 'Bisnis RS',
                'kode_prefix' => 'B',
                'keterangan' => 'Semua risiko yang berhubungan atau terkait dengan bisnis rumah sakit',
                'status_ruang_lingkup' => 'aktif',
            ],
        ];

        foreach ($scopes as $scope) {
            \App\Models\RuangLingkup::create($scope);
        }
    }
}
