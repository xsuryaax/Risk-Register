<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$risks = App\Models\AnalisisRisiko::with(['probabilitas', 'dampak'])->get();
$mismatches = 0;
foreach ($risks as $r) {
    $p = $r->probabilitas->nilai_probabilitas ?? 0;
    $d = $r->dampak->nilai_dampak ?? 0;
    $calculatedScore = $p * $d;
    
    $expectedRanking = 'RENDAH';
    if ($calculatedScore >= 20) $expectedRanking = 'SANGAT TINGGI';
    elseif ($calculatedScore >= 13) $expectedRanking = 'TINGGI';
    elseif ($calculatedScore >= 5) $expectedRanking = 'SEDANG';

    if (trim(strtoupper($r->peringkat_risiko)) !== $expectedRanking) {
        echo "MISMATCH - ID: {$r->identifikasi_risiko_id} | P:{$p} D:{$d} | DB Score: {$r->skor_risiko} Calc: {$calculatedScore} | DB Level: {$r->peringkat_risiko} Expected: {$expectedRanking}\n";
        $mismatches++;
    }
}
if ($mismatches === 0) echo "NO MISMATCHES FOUND\n";
echo "Total Risks: " . $risks->count() . "\n";
