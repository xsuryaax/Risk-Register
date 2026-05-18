<?php

use App\Models\EvaluasiRisiko;
use App\Models\IdentifikasiRisiko;

$evaluasis = EvaluasiRisiko::all();
$count = 0;

foreach ($evaluasis as $eval) {
    $identifikasi = IdentifikasiRisiko::with('analisis')->find($eval->identifikasi_risiko_id);
    
    if ($identifikasi && $identifikasi->analisis) {
        $initialScore = $identifikasi->analisis->skor_risiko;
        $residuScore = $eval->skor_residu;
        
        if ($initialScore > 0) {
            $newReduction = (($initialScore - $residuScore) / $initialScore) * 100;
        } else {
            $newReduction = 0;
        }
        
        if ($eval->penurunan_persen != $newReduction) {
            $eval->penurunan_persen = $newReduction;
            $eval->save();
            $count++;
            echo "Fixed ID {$eval->id}: Initial {$initialScore} -> Residu {$residuScore} = " . number_format($newReduction, 2) . "%\n";
        }
    }
}

echo "\nTotal fixed: $count records.\n";
