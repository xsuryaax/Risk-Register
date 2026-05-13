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
        Schema::table('tbl_evaluasi_risiko', function (Blueprint $table) {
            $table->integer('frekuensi_kejadian')->default(0)->after('identifikasi_risiko_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_evaluasi_risiko', function (Blueprint $table) {
            $table->dropColumn('frekuensi_kejadian');
        });
    }
};
