<?php
$csvFile = 'RISK REGISTER TAHUN 2026.xlsx - All Unit.csv';
$handle = fopen($csvFile, 'r');
$codes = [];
$row = 0;
while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
    $row++;
    if ($row <= 7) continue;
    $kode = trim($data[3] ?? '');
    if (empty($kode) || $kode == 'o') continue;
    if (isset($codes[$kode])) {
        echo "Duplicate code found: $kode at row $row (previous at row " . $codes[$kode] . ")\n";
    }
    $codes[$kode] = $row;
}
fclose($handle);
