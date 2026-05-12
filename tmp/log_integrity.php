<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AnalisisRisiko;
use Illuminate\Support\Facades\DB;

$ranks = AnalisisRisiko::distinct()->pluck('peringkat_risiko');
echo "Distinct ranks in DB:\n";
foreach ($ranks as $r) {
    echo "'$r' (length: " . strlen($r) . ")\n";
}
