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
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->tinyInteger('triwulan')->default(1)->after('periode_id')->comment('1-4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_identifikasi_risiko', function (Blueprint $table) {
            $table->dropColumn('triwulan');
        });
    }
};
