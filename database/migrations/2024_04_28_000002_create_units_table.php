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
        Schema::create('tbl_units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->nullable();
            $table->string('nama_unit');
            $table->text('deskripsi_unit')->nullable();
            $table->enum('status_unit', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_units');
    }
};
