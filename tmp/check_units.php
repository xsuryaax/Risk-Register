<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$units = \App\Models\Unit::whereHas('identifikasi')->withCount('identifikasi')->get();
foreach($units as $u) {
    echo $u->nama_unit . ':' . $u->identifikasi_count . "\n";
}
