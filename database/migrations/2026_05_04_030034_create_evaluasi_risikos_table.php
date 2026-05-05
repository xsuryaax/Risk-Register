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
    public function up(): void
    {
        Schema::create('tbl_evaluasi_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identifikasi_risiko_id')->constrained('tbl_identifikasi_risiko')->onDelete('cascade');
            $table->foreignId('probabilitas_residu_id')->nullable()->constrained('tbl_probabilitas')->onDelete('set null');
            $table->foreignId('dampak_residu_id')->nullable()->constrained('tbl_dampak')->onDelete('set null');
            $table->integer('skor_residu')->nullable();
            $table->string('peringkat_residu')->nullable();
            $table->decimal('penurunan_persen', 5, 2)->nullable();
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
