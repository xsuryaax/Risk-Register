<?php
$handle = fopen('RISK REGISTER TAHUN 2026.xlsx - All Unit.csv', 'r');
$header1 = fgetcsv($handle);
$header2 = fgetcsv($handle);
$header3 = fgetcsv($handle);
$units = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 2) continue;
    $units[] = trim($row[1]);
}
fclose($handle);
$unique = array_unique($units);
asort($unique);
print_r($unique);
