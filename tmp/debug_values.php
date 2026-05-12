<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Probabilities:\n";
print_r(App\Models\Probabilitas::orderBy('nilai_probabilitas')->get(['id', 'nilai_probabilitas', 'nama_probabilitas'])->toArray());
echo "\nImpacts:\n";
print_r(App\Models\Dampak::orderBy('nilai_dampak')->get(['id', 'nilai_dampak', 'nama_dampak'])->toArray());
