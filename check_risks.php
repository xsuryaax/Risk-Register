<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = App\Models\AnalisisRisiko::with(['probabilitas', 'dampak'])->get()->map(function($a) {
    return [
        'id' => $a->identifikasi_risiko_id,
        'P' => $a->probabilitas->nilai_probabilitas ?? 0,
        'D' => $a->dampak->nilai_dampak ?? 0,
        'skor' => $a->skor_risiko,
        'level' => $a->peringkat_risiko
    ];
});

echo json_encode($data, JSON_PRETTY_PRINT);
