<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdentifikasiRisiko;

$peringkat = 'SANGAT TINGGI';
$query = IdentifikasiRisiko::whereHas('analisis', function($q) use ($peringkat) {
    $q->where('peringkat_risiko', $peringkat);
});

echo "Count for $peringkat: " . $query->count() . "\n";

$peringkat = 'TINGGI';
$query = IdentifikasiRisiko::whereHas('analisis', function($q) use ($peringkat) {
    $q->where('peringkat_risiko', $peringkat);
});

echo "Count for $peringkat: " . $query->count() . "\n";
