<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_dampak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dampak');
            $table->integer('nilai_dampak');
            $table->string('warna');
            $table->text('area_dampak')->nullable();
            $table->text('cidera_pasien')->nullable();
            $table->text('pelayanan_operasional')->nullable();
            $table->text('biaya_keuangan')->nullable();
            $table->text('publikasi')->nullable();
            $table->text('reputasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_dampak');
    }
};
