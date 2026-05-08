<?php
$handle = fopen('RISK REGISTER TAHUN 2026.xlsx - All Unit.csv', 'r');
fgetcsv($handle);
fgetcsv($handle);
fgetcsv($handle);
$units = [];
$row = 0;
while (($data = fgetcsv($handle)) !== false) {
    if (count($data) < 21) continue;
    $row++;
    if ($row <= 4) continue;
    $str = trim($data[20]);
    if ($str) {
        $units[$str] = 1;
    }
}
fclose($handle);
$u = array_keys($units);
sort($u);
file_put_contents('mapped_units.txt', implode("\n", $u));
