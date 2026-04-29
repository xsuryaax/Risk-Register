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
        Schema::create('tbl_probabilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_probabilitas'); // Misal: Sangat Jarang, Jarang, dst
            $table->integer('nilai_probabilitas'); // 1-5
            $table->text('keterangan')->nullable();
            $table->enum('status_probabilitas', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_probabilitas');
    }
};
