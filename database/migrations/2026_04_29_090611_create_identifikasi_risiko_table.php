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
        Schema::create('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->text('kegiatan');
            $table->text('tujuan_kegiatan');
            $table->string('kode_risiko')->unique();
            $table->foreignId('kategori_risiko_id')->constrained('tbl_kategori_risiko')->onDelete('cascade');
            $table->foreignId('ruang_lingkup_id')->constrained('tbl_ruang_lingkup')->onDelete('cascade');
            $table->text('pernyataan_risiko');
            $table->text('sebab');
            $table->string('jenis_risiko')->comment('UC or C');
            $table->text('dampak');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_identifikasi_risiko');
    }
};
