<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignKeyConstraints;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        Schema::create('evaluasi_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identifikasi_risiko_id')->constrained('identifikasi_risiko')->onDelete('cascade');
            $table->text('uraian_rencana_pengendalian')->nullable();
            $table->string('jadwal_pelaksanaan')->nullable();
            $table->string('pj_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_risiko');
    }
};
