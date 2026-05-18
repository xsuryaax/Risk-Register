<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique(['kode_risiko']);
            
            // Add a more flexible unique constraint (Unit + Code + Period + Triwulan)
            // Or better, just make it NOT unique at DB level and handle it in logic
            // Since we want the same risk to exist across quarters.
        });
    }

    public function down(): void
    {
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->string('kode_risiko')->unique()->change();
        });
    }
};
