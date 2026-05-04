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
        Schema::create('tbl_analisis_kecukupan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identifikasi_risiko_id')->constrained('tbl_identifikasi_risiko')->onDelete('cascade');
            $table->text('uraian_rencana')->nullable();
            $table->string('jadwal')->nullable();
            $table->string('pj_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_analisis_kecukupan');
    }
};
