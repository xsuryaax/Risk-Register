<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$probs = App\Models\Probabilitas::pluck('nilai_probabilitas')->toArray();
$dmps = App\Models\Dampak::pluck('nilai_dampak')->toArray();

echo "Probs: " . implode(',', $probs) . "\n";
echo "Dmps: " . implode(',', $dmps) . "\n";
