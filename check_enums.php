<?php
$csvFile = 'RISK REGISTER TAHUN 2026.xlsx - All Unit.csv';
$handle = fopen($csvFile, 'r');
$desain = [];
$efek = [];
$row = 0;
while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
    $row++;
    if ($row <= 7) continue;
    if (empty(trim($data[1] ?? ''))) continue;
    $desain[$data[12]] = ($desain[$data[12]] ?? 0) + 1;
    $efek[$data[13]] = ($efek[$data[13]] ?? 0) + 1;
}
fclose($handle);
print_r($desain);
print_r($efek);
