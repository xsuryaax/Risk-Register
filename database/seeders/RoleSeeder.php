<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'nama_role' => 'Administrator', 'deskripsi_role' => 'Memiliki akses penuh ke semua fitur dan pengaturan sistem.'],
            ['id' => 2, 'nama_role' => 'Tim Mutu', 'deskripsi_role' => 'Bertanggung jawab atas manajemen unit dan pelaporan indikator mutu.'],
            ['id' => 3, 'nama_role' => 'Validator', 'deskripsi_role' => 'Menginput dan melaporkan data indikator sesuai unit masing-masing.'],
            ['id' => 4, 'nama_role' => 'Staff', 'deskripsi_role' => 'Pelaksana'],
            ['id' => 5, 'nama_role' => 'Koordinator', 'deskripsi_role' => 'Leader Ruangan'],
            ['id' => 6, 'nama_role' => 'Penanggung Jawab', 'deskripsi_role' => 'PJ Shift / PJ Layanan'],
            ['id' => 7, 'nama_role' => 'Supervisor', 'deskripsi_role' => 'Leader Lintas Unit'],
            ['id' => 8, 'nama_role' => 'Kepala unit', 'deskripsi_role' => 'Manager Unit'],
            ['id' => 9, 'nama_role' => 'Kepala', 'deskripsi_role' => 'Kepala Divisi / Kepala Bagian'],
            ['id' => 10, 'nama_role' => 'Dokter Spesialis', 'deskripsi_role' => 'Dokter Spesialis'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
