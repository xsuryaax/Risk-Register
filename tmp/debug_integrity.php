<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$all = \App\Models\AnalisisRisiko::all();

foreach($all as $a) {
    $p = $a->probabilitas->nilai_probabilitas ?? 0;
    $d = $a->dampak->nilai_dampak ?? 0;
    $score = $p * $d;
    $rank = strtoupper(trim($a->peringkat_risiko));
    
    echo "ID: {$a->id} | P:{$p} D:{$d} | Score:{$score} vs Rank:{$rank}\n";
}
