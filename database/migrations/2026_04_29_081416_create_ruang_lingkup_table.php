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
        Schema::create('tbl_ruang_lingkup', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruang_lingkup');
            $table->text('keterangan')->nullable();
            $table->enum('status_ruang_lingkup', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ruang_lingkup');
    }
};
