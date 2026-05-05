<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UnitSeeder::class,
            KaryawanSeeder::class,
            KategoriRisikoSeeder::class,
            RuangLingkupSeeder::class,
            ProbabilitasSeeder::class,
            DampakSeeder::class,
            IdentifikasiRisikoSeeder::class,
            AnalisisRisikoSeeder::class,
            AnalisisKecukupanSeeder::class,
            EvaluasiRisikoSeeder::class,
        ]);
    }
}
