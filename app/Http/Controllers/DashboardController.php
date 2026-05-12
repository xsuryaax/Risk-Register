<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\AnalisisRisiko;
use App\Models\EvaluasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Base Query for Security
        $baseQuery = IdentifikasiRisiko::with([
            'analisis.probabilitas', 
            'analisis.dampak', 
            'evaluasi.probabilitas', 
            'evaluasi.dampak'
        ]);
        if (!in_array($user->role_id, [1, 2])) {
            $baseQuery->where('unit_id', $user->unit_id);
        }

        // 1. Basic Stats & Risk Levels (Dynamic: Use Residual if evaluated)
        $allIdentifikasi = $baseQuery->get();
        
        $levelStats = [
            'SANGAT TINGGI' => 0,
            'TINGGI'        => 0,
            'SEDANG'        => 0,
            'RENDAH'        => 0,
            'SANGAT RENDAH' => 0,
        ];

        // 5. Heatmap (P x D) Initialize
        $heatmap = [];
        for($p=1; $p<=5; $p++) { 
            for($d=1; $d<=5; $d++) { 
                $heatmap[$p][$d] = 0;
            }
        }

        foreach($allIdentifikasi as $item) {
            // Priority: Residual > Initial
            $score = $item->evaluasi ? $item->evaluasi->skor_residu : ($item->analisis ? $item->analisis->skor_risiko : null);
            $rank = $item->evaluasi ? $item->evaluasi->peringkat_residu : ($item->analisis ? $item->analisis->peringkat_risiko : null);
            
            // Stats
            if($rank && isset($levelStats[strtoupper(trim($rank))])) {
                $levelStats[strtoupper(trim($rank))]++;
            }

            // Heatmap (Dampak = Row, Probabilitas = Column)
            $pVal = $item->evaluasi ? ($item->evaluasi->probabilitas->nilai_probabilitas ?? null) : ($item->analisis->probabilitas->nilai_probabilitas ?? null);
            $dVal = $item->evaluasi ? ($item->evaluasi->dampak->nilai_dampak ?? null) : ($item->analisis->dampak->nilai_dampak ?? null);
            if($pVal && $dVal && isset($heatmap[$dVal][$pVal])) {
                $heatmap[$dVal][$pVal]++;
            }
        }

        $totalAnalyzed = $allIdentifikasi->filter(fn($i) => $i->analisis)->count();
        $totalEvaluated = $allIdentifikasi->filter(fn($i) => $i->evaluasi)->count();
        
        // 2. Mitigation Status
        $completedRisks = $totalEvaluated;
        $pendingRisks = $totalAnalyzed - $totalEvaluated;

        // 3. Risk by Unit (Admin/Mutu see all units, others see only theirs)
        $unitQuery = Unit::whereHas('identifikasi');
        if (!in_array($user->role_id, [1, 2])) {
            $unitQuery->where('id', $user->unit_id);
        }
        $unitData = $unitQuery->withCount('identifikasi')->get();

        // 4. Trend Data (Last 6 Months)
        $trendQuery = IdentifikasiRisiko::query();
        if (!in_array($user->role_id, [1, 2])) {
            $trendQuery->where('unit_id', $user->unit_id);
        }

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFormat = $isSqlite ? "strftime('%m', created_at)" : "DATE_FORMAT(created_at, '%m')";
        
        $trendDataRaw = $trendQuery->select(
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

        // 6. Top Risks (Priority: Sort by Current Score)
        $criticalRisks = $allIdentifikasi->filter(fn($i) => $i->analisis)
            ->sortByDesc(function($item) {
                return $item->evaluasi ? $item->evaluasi->skor_residu : $item->analisis->skor_risiko;
            })
            ->take(10);

        // 7. Recent Activity (Filtered by Unit)
        $activityLogQuery = function($model) use ($user) {
            $q = $model->query();
            if (!in_array($user->role_id, [1, 2])) {
                if ($model instanceof IdentifikasiRisiko) {
                    $q->where('unit_id', $user->unit_id);
                } else {
                    $q->whereHas('identifikasi', function($qi) use ($user) {
                        $qi->where('unit_id', $user->unit_id);
                    });
                }
            }
            return $q->orderBy('created_at', 'desc')->take(5)->get();
        };

        $recentIdentifikasi = $activityLogQuery(new IdentifikasiRisiko())->map(function($item) {
            return ['type' => 'ID', 'msg' => 'Identifikasi Baru', 'date' => $item->created_at, 'risk' => $item->kode_risiko];
        });
        $recentAnalisis = $activityLogQuery(new AnalisisRisiko())->map(function($item) {
            return ['type' => 'AN', 'msg' => 'Analisis Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-'];
        });
        $recentEvaluasi = $activityLogQuery(new EvaluasiRisiko())->map(function($item) {
            return ['type' => 'EV', 'msg' => 'Evaluasi Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-'];
        });

        $activities = $recentIdentifikasi->concat($recentAnalisis)->concat($recentEvaluasi)->sortByDesc('date')->take(6);

        // 5. Risk by Category Distribution
        $categoryStats = KategoriRisiko::withCount('identifikasi')
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->nama_kategori,
                    'count' => $cat->identifikasi_count
                ];
            });

        $totalRisks = $allIdentifikasi->count();

        return view('dashboard', compact(
            'allIdentifikasi', 'totalRisks', 'totalAnalyzed', 'totalEvaluated', 'levelStats',
            'pendingRisks', 'completedRisks',
            'unitData', 'trendData', 'criticalRisks', 'heatmap', 'categoryStats', 'activities'
        ));
    }
}
