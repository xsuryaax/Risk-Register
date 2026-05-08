<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$all = \App\Models\AnalisisRisiko::where('peringkat_risiko', 'SANGAT TINGGI')->get();

echo "Rank SANGAT TINGGI count: " . $all->count() . "\n";

foreach($all as $a) {
    $p = $a->probabilitas->nilai_probabilitas ?? 'MISSING';
    $d = $a->dampak->nilai_dampak ?? 'MISSING';
    echo "ID: {$a->id} | P: {$p} | D: {$d} | Score: {$a->skor_risiko}\n";
}
