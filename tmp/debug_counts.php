<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AnalisisRisiko;
use Illuminate\Support\Facades\DB;

$stats = AnalisisRisiko::groupBy('peringkat_risiko')
    ->select('peringkat_risiko', DB::raw('count(*) as total'))
    ->get();

foreach ($stats as $stat) {
    echo $stat->peringkat_risiko . ": " . $stat->total . "\n";
}
