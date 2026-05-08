<?php
$handle = fopen('RISK REGISTER TAHUN 2026.xlsx - All Unit.csv', 'r');
fgetcsv($handle);
fgetcsv($handle);
$r = fgetcsv($handle);
foreach($r as $k => $v) {
    if ($v) echo "[$k] => $v\n";
}
fclose($handle);
