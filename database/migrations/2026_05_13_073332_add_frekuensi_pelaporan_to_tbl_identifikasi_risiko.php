<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->string('frekuensi_pelaporan', 20)->default('triwulan')->after('triwulan')
                ->comment('tahunan, semester, triwulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->dropColumn('frekuensi_pelaporan');
        });
    }
};
