<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Mencoba membuat tabel tbl_rencana_pengendalian...\n";

try {
    Schema::create('tbl_rencana_pengendalian', function (Blueprint $table) {
        $table->id();
        $table->foreignId('identifikasi_risiko_id')->constrained('tbl_identifikasi_risiko')->onDelete('cascade');
        $table->text('uraian_rencana')->nullable();
        $table->string('jadwal')->nullable();
        $table->string('pj_tindak_lanjut')->nullable();
        $table->timestamps();
    });
    echo "Tabel BERHASIL dibuat!\n";
} catch (\Exception $e) {
    echo "GAGAL membuat tabel: " . $e->getMessage() . "\n";
}
