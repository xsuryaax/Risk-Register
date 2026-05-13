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
        Schema::table('tbl_evaluasi_risiko', function (Blueprint $table) {
            $table->string('status_kejadian')->nullable()->after('identifikasi_risiko_id');
            $table->text('uraian_kejadian')->nullable()->after('status_kejadian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_evaluasi_risiko', function (Blueprint $table) {
            $table->dropColumn(['status_kejadian', 'uraian_kejadian']);
        });
    }
};
