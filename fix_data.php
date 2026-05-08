<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$risks = App\Models\AnalisisRisiko::with(['probabilitas', 'dampak'])->get();
$count = 0;
foreach ($risks as $r) {
    $p = $r->probabilitas->nilai_probabilitas ?? 0;
    $d = $r->dampak->nilai_dampak ?? 0;
    $score = $p * $d;
    
    $ranking = 'RENDAH';
    if ($score >= 20) {
        $ranking = 'SANGAT TINGGI';
    } elseif ($score >= 13) {
        $ranking = 'TINGGI';
    } elseif ($score >= 5) {
        $ranking = 'SEDANG';
    }
    
    $r->update([
        'peringkat_risiko' => $ranking,
        'skor_risiko' => $score
    ]);
    $count++;
}
echo "Updated {$count} records successfully.\n";
