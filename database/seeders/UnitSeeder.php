<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['id' => '1', 'kode_unit' => 'UNIT001', 'nama_unit' => 'Administrator', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '2', 'kode_unit' => 'UNIT002', 'nama_unit' => 'Mutu', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '6', 'kode_unit' => 'UNIT006', 'nama_unit' => 'IPSRS', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '7', 'kode_unit' => 'UNIT007', 'nama_unit' => 'Rawat jalan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '9', 'kode_unit' => 'UNIT009', 'nama_unit' => 'Transportasi & kurir', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '10', 'kode_unit' => 'UNIT010', 'nama_unit' => 'NS 1', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '11', 'kode_unit' => 'UNIT011', 'nama_unit' => 'NS 2', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '12', 'kode_unit' => 'UNIT012', 'nama_unit' => 'NS 3', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '13', 'kode_unit' => 'UNIT013', 'nama_unit' => 'NS 4', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '14', 'kode_unit' => 'UNIT014', 'nama_unit' => 'NS 6', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '15', 'kode_unit' => 'UNIT015', 'nama_unit' => 'NS 7', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '16', 'kode_unit' => 'UNIT016', 'nama_unit' => 'NS 8', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '17', 'kode_unit' => 'UNIT017', 'nama_unit' => 'ICU', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '18', 'kode_unit' => 'UNIT018', 'nama_unit' => 'Instalasi gawat darurat', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '20', 'kode_unit' => 'UNIT020', 'nama_unit' => 'Marketing', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '22', 'kode_unit' => 'UNIT022', 'nama_unit' => 'NICU & PICU', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '23', 'kode_unit' => 'UNIT023', 'nama_unit' => 'IT', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '24', 'kode_unit' => 'UNIT024', 'nama_unit' => 'Sekretariat', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '25', 'kode_unit' => 'UNIT025', 'nama_unit' => 'MPP', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '26', 'kode_unit' => 'UNIT026', 'nama_unit' => 'Casemix', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '27', 'kode_unit' => 'UNIT027', 'nama_unit' => 'Kamar operasi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '31', 'kode_unit' => 'UNIT031', 'nama_unit' => 'Maintenance', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '32', 'kode_unit' => 'UNIT032', 'nama_unit' => 'Dokter jaga', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '33', 'kode_unit' => 'UNIT033', 'nama_unit' => 'Hemodialisa', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '35', 'kode_unit' => 'UNIT035', 'nama_unit' => 'PPI', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '36', 'kode_unit' => 'UNIT036', 'nama_unit' => 'SDM', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '37', 'kode_unit' => 'UNIT037', 'nama_unit' => 'AO & Call center', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '38', 'kode_unit' => 'UNIT038', 'nama_unit' => 'Perinatologi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '39', 'kode_unit' => 'UNIT039', 'nama_unit' => 'CSSD', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '41', 'kode_unit' => 'UNIT041', 'nama_unit' => 'Rekam medis', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '42', 'kode_unit' => 'UNIT042', 'nama_unit' => 'Instalasi kamar operasi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '43', 'kode_unit' => 'UNIT043', 'nama_unit' => 'Keuangan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '44', 'kode_unit' => 'UNIT044', 'nama_unit' => 'Kamar bersalin', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '45', 'kode_unit' => 'UNIT045', 'nama_unit' => 'Rawat inap', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '46', 'kode_unit' => 'UNIT046', 'nama_unit' => 'Kesehatan lingkungan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '47', 'kode_unit' => 'UNIT047', 'nama_unit' => 'Legal', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '48', 'kode_unit' => 'UNIT048', 'nama_unit' => 'Logistik umum', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '49', 'kode_unit' => 'UNIT049', 'nama_unit' => 'Duty officer', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '51', 'kode_unit' => 'UNIT051', 'nama_unit' => 'Laundry', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '52', 'kode_unit' => 'UNIT052', 'nama_unit' => 'AR', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '40', 'kode_unit' => 'UNIT040', 'nama_unit' => 'Akuntansi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '30', 'kode_unit' => 'UNIT030', 'nama_unit' => 'Kasir & Pentarifan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '28', 'kode_unit' => 'UNIT028', 'nama_unit' => 'Pengadaan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '54', 'kode_unit' => 'UNIT054', 'nama_unit' => 'Sales & Digital Marketing', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '21', 'kode_unit' => 'UNIT021', 'nama_unit' => 'Farmasi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '50', 'kode_unit' => 'UNIT050', 'nama_unit' => 'Keperawatan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '3', 'kode_unit' => 'UNIT003', 'nama_unit' => 'Gizi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '8', 'kode_unit' => 'UNIT008', 'nama_unit' => 'Laboratorium', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '34', 'kode_unit' => 'UNIT034', 'nama_unit' => 'Radiologi', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '29', 'kode_unit' => 'UNIT029', 'nama_unit' => 'Rehabilitasi Medik & KTKA', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '4', 'kode_unit' => 'UNIT004', 'nama_unit' => 'Rumah Tangga & K3RS', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '5', 'kode_unit' => 'UNIT005', 'nama_unit' => 'Umum & Perizinan', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '55', 'kode_unit' => 'UNIT055', 'nama_unit' => 'Intensif', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
            ['id' => '19', 'kode_unit' => 'UNIT019', 'nama_unit' => 'KTKA', 'deskripsi_unit' => null, 'status_unit' => 'non-aktif'],
            ['id' => '53', 'kode_unit' => 'UNIT053', 'nama_unit' => 'Humas & CC', 'deskripsi_unit' => null, 'status_unit' => 'aktif'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['id' => $unit['id']], $unit);
        }
    }
}
