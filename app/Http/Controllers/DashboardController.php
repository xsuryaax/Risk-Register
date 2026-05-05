<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\AnalisisRisiko;
use App\Models\EvaluasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Stats
        $totalRisks = IdentifikasiRisiko::count();
        $totalAnalyzed = AnalisisRisiko::count();
        $totalEvaluated = EvaluasiRisiko::count();
        
        // 2. Control Performance (Based on Effectiveness in Analisis)
        $controlPerformance = AnalisisRisiko::select('efektifitas_pengendalian', DB::raw('count(*) as total'))
            ->groupBy('efektifitas_pengendalian')
            ->get();

        // 3. Open Issues (Risks identified but not yet evaluated)
        $openIssues = IdentifikasiRisiko::whereDoesntHave('evaluasi')->count();
        $evaluatedIssues = $totalEvaluated;

        // 4. Heatmap Data (P x D)
        $heatmapRaw = AnalisisRisiko::with(['probabilitas', 'dampak'])->get();
        $heatmap = [];
        $totalsByImpact = [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
        $totalsByProb = [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];

        for($p=1; $p<=5; $p++) {
            for($d=1; $d<=5; $d++) {
                $heatmap[$p][$d] = 0;
            }
        }
        
        foreach($heatmapRaw as $item) {
            $pVal = $item->probabilitas->nilai_probabilitas ?? null;
            $dVal = $item->dampak->nilai_dampak ?? null;
            if($pVal && $dVal && isset($heatmap[$pVal][$dVal])) {
                $heatmap[$pVal][$dVal]++;
                $totalsByImpact[$dVal]++;
                $totalsByProb[$pVal]++;
            }
        }

        // 5. Critical Risks (Top 10)
        $criticalRisks = IdentifikasiRisiko::with(['analisis'])
            ->whereHas('analisis')
            ->get()
            ->sortByDesc(function($item) {
                return $item->analisis->skor_risiko;
            })
            ->take(10);

        // 6. Risk by Category (Stacked Data)
        $categories = KategoriRisiko::with(['identifikasi.analisis'])->get();
        $categoryData = $categories->map(function($cat) {
            $risks = $cat->identifikasi;
            return [
                'name' => $cat->nama_kategori,
                'extreme' => $risks->where('analisis.peringkat_risiko', 'SANGAT TINGGI')->count(),
                'high' => $risks->where('analisis.peringkat_risiko', 'TINGGI')->count(),
                'medium' => $risks->where('analisis.peringkat_risiko', 'SEDANG')->count(),
                'low' => $risks->where('analisis.peringkat_risiko', 'RENDAH')->count(),
            ];
        });

        return view('dashboard', compact(
            'totalRisks', 'totalAnalyzed', 'totalEvaluated',
            'controlPerformance', 'openIssues', 'evaluatedIssues',
            'heatmap', 'totalsByImpact', 'totalsByProb',
            'criticalRisks', 'categoryData'
        ));
    }
}
