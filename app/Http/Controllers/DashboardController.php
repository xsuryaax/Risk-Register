<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\AnalisisRisiko;
use App\Models\EvaluasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\Unit;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get period to view (from navbar dropdown or current active)
        $activePeriode = Periode::getActive();
        $viewPeriodeId = $request->view_periode;
        
        if ($viewPeriodeId) {
            $activePeriode = Periode::find($viewPeriodeId);
        }
        
        $activeId = $activePeriode ? $activePeriode->id : 0;
        
        // Base Query for Security & Period
        $baseQuery = IdentifikasiRisiko::with([
            'analisis.probabilitas', 
            'analisis.dampak', 
            'evaluasi.probabilitas', 
            'evaluasi.dampak'
        ]);

        if ($activePeriode) {
            $baseQuery->where('periode_id', $activePeriode->id);
        } else {
            $baseQuery->whereRaw('1 = 0');
        }

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

        // 3. Risk by Unit 
        $unitQuery = Unit::query();
        if (!in_array($user->role_id, [1, 2])) {
            $unitQuery->where('id', $user->unit_id);
        }
        $activeId = $activePeriode ? $activePeriode->id : 0;
        $unitData = $unitQuery->withCount(['identifikasi' => function($q) use ($activeId) {
            $q->where('periode_id', $activeId);
        }])
        ->orderBy('identifikasi_count', 'desc')
        ->get()
        ->filter(fn($u) => $u->identifikasi_count > 0)
        ->values();

        // 4. Trend Data
        $trendQuery = IdentifikasiRisiko::query();
        if ($activePeriode) {
            $trendQuery->where('periode_id', $activePeriode->id);
        } else {
            $trendQuery->whereRaw('1 = 0');
        }
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

        // 6. Top Risks
        $criticalRisks = $allIdentifikasi->filter(fn($i) => $i->analisis)
            ->sortByDesc(function($item) {
                return $item->evaluasi ? $item->evaluasi->skor_residu : $item->analisis->skor_risiko;
            })
            ->take(10);

        // 7. Recent Activity (Filtered by Period)
        $activityLogQuery = function($model) use ($user, $activeId) {
            $q = $model->query();
            if ($model instanceof IdentifikasiRisiko) {
                $q->where('periode_id', $activeId);
                if (!in_array($user->role_id, [1, 2])) {
                    $q->where('unit_id', $user->unit_id);
                }
            } else {
                $q->whereHas('identifikasi', function($qi) use ($user, $activeId) {
                    $qi->where('periode_id', $activeId);
                    if (!in_array($user->role_id, [1, 2])) {
                        $qi->where('unit_id', $user->unit_id);
                    }
                });
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

        // 5. Risk by Category Distribution (Strictly filtered by Unit if not Admin)
        $categoryStats = KategoriRisiko::withCount(['identifikasi' => function($q) use ($activeId, $user) {
            $q->where('periode_id', $activeId);
            if (!in_array($user->role_id, [1, 2])) {
                $q->where('unit_id', $user->unit_id);
            }
        }])->get()
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
            'unitData', 'trendData', 'criticalRisks', 'heatmap', 'categoryStats', 'activities', 'activePeriode'
        ));
    }
}
