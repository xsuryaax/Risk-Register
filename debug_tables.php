<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$tables = Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table'");
foreach ($tables as $table) {
    echo $table->name . PHP_EOL;
}
