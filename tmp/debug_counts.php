<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$all = \App\Models\AnalisisRisiko::with(['probabilitas', 'dampak'])->get();

echo "Total Analisis: " . $all->count() . "\n";

$levels = ['SANGAT TINGGI' => 0, 'TINGGI' => 0, 'SEDANG' => 0, 'RENDAH' => 0];
$matrix_count = 0;

foreach($all as $a) {
    echo "ID: {$a->id} | Rank: {$a->peringkat_risiko} | P: " . ($a->probabilitas->nilai_probabilitas ?? 'null') . " | D: " . ($a->dampak->nilai_dampak ?? 'null') . "\n";
    
    $r = strtoupper(trim($a->peringkat_risiko));
    if(isset($levels[$r])) $levels[$r]++;
    
    if($a->probabilitas && $a->dampak) {
        $matrix_count++;
    }
}

print_r($levels);
echo "Total in Matrix: $matrix_count\n";
