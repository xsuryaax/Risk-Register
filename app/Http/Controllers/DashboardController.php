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
        // 1. Basic Stats & Risk Levels
        $totalRisks = IdentifikasiRisiko::count();
        $totalAnalyzed = AnalisisRisiko::count();
        $totalEvaluated = EvaluasiRisiko::count();
        
        $levelStats = [
            'SANGAT TINGGI' => AnalisisRisiko::where('peringkat_risiko', 'SANGAT TINGGI')->count(),
            'TINGGI' => AnalisisRisiko::where('peringkat_risiko', 'TINGGI')->count(),
            'SEDANG' => AnalisisRisiko::where('peringkat_risiko', 'SEDANG')->count(),
            'RENDAH' => AnalisisRisiko::where('peringkat_risiko', 'RENDAH')->count(),
        ];

        // 2. Open vs Completed (Mitigation Status)
        $completedRisks = $totalEvaluated;
        $pendingRisks = IdentifikasiRisiko::whereDoesntHave('evaluasi')->count();

        // 3. Risk by Unit
        $unitData = Unit::withCount('identifikasi')->get();

        // 4. Trend Data (Last 6 Months)
        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFormat = $isSqlite ? "strftime('%m', created_at)" : "DATE_FORMAT(created_at, '%m')";
        
        $trendDataRaw = IdentifikasiRisiko::select(
            DB::raw('count(*) as total'),
            DB::raw("$monthFormat as month_num"),
            DB::raw('max(created_at) as date')
        )
        ->groupBy('month_num')
        ->orderBy('date', 'asc')
        ->take(6)
        ->get();

        $trendData = $trendDataRaw->map(function($item) {
            $months = [
                '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', 
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', 
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
            ];
            $item->month = $months[$item->month_num] ?? $item->month_num;
            return $item;
        });

        // 5. Heatmap (P x D)
        $heatmapRaw = AnalisisRisiko::with(['probabilitas', 'dampak'])->get();
        $heatmap = [];
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
            }
        }

        // 6. Top Risks (Priority)
        $criticalRisks = IdentifikasiRisiko::with(['analisis', 'evaluasi'])
            ->whereHas('analisis')
            ->get()
            ->sortByDesc(function($item) {
                return $item->analisis->skor_risiko;
            })
            ->take(8);

        // 7. Recent Activity (Unified Log)
        $recentIdentifikasi = IdentifikasiRisiko::orderBy('created_at', 'desc')->take(5)->get()->map(function($item) {
            return ['type' => 'ID', 'msg' => 'Identifikasi Baru', 'date' => $item->created_at, 'risk' => $item->kode_risiko];
        });
        $recentAnalisis = AnalisisRisiko::with('identifikasi')->orderBy('created_at', 'desc')->take(5)->get()->map(function($item) {
            return ['type' => 'AN', 'msg' => 'Analisis Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-'];
        });
        $recentEvaluasi = EvaluasiRisiko::with('identifikasi')->orderBy('created_at', 'desc')->take(5)->get()->map(function($item) {
            return ['type' => 'EV', 'msg' => 'Evaluasi Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-'];
        });

        $activities = $recentIdentifikasi->concat($recentAnalisis)->concat($recentEvaluasi)->sortByDesc('date')->take(6);

        return view('dashboard', compact(
            'totalRisks', 'levelStats', 'pendingRisks', 'completedRisks',
            'unitData', 'trendData', 'heatmap', 'criticalRisks', 'activities'
        ));
    }
}
