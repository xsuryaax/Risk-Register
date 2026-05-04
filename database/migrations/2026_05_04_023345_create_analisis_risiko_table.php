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
        Schema::create('tbl_analisis_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identifikasi_risiko_id')->constrained('tbl_identifikasi_risiko')->onDelete('cascade');
            $table->text('uraian_pengendalian')->nullable();
            $table->enum('desain_pengendalian', ['Ada', 'Tidak'])->default('Tidak');
            $table->enum('efektifitas_pengendalian', ['Efektif', 'Kurang Efektif', 'Tidak Efektif'])->default('Tidak Efektif');
            $table->foreignId('probabilitas_id')->nullable()->constrained('tbl_probabilitas');
            $table->foreignId('dampak_id')->nullable()->constrained('tbl_dampak');
            $table->integer('skor_risiko')->default(0);
            $table->string('peringkat_risiko')->nullable();
            $table->string('pemilik_risiko')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_analisis_risiko');
    }
};
