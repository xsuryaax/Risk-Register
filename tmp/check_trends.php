<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
$monthFormat = $isSqlite ? "strftime('%m', created_at)" : "DATE_FORMAT(created_at, '%m')";

$trends = \App\Models\IdentifikasiRisiko::select(
    \Illuminate\Support\Facades\DB::raw('count(*) as total'),
    \Illuminate\Support\Facades\DB::raw("$monthFormat as month_num"),
    \Illuminate\Support\Facades\DB::raw('max(created_at) as date')
)
->groupBy('month_num')
->get();

foreach($trends as $t) {
    echo $t->month_num . ':' . $t->total . "\n";
}
