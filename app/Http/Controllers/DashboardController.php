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
        
        $triwulan = $request->view_triwulan ?? 'all';
        
        // MASTER LIST IDs (Robust Calculation)
        $masterIds = [];
        if ($activePeriode) {
            $allBase = IdentifikasiRisiko::where('periode_id', $activePeriode->id)->get();
            
            $grouped = $allBase->groupBy('kode_risiko');
            
            if ($triwulan === 'all') {
                // For 'all' view, pick the latest updated version of each unique risk
                $masterIds = $grouped->map(fn($group) => $group->sortByDesc('triwulan')->first()->id)
                    ->values()->toArray();
            } else {
                $targetVal = ($triwulan == 's1' ? [1, 2] : ($triwulan == 's2' ? [3, 4] : [$triwulan]));
                $masterIds = $grouped->map(function($group) use ($targetVal) {
                    // 1. Exact match for current triwulan (pick this if available)
                    $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                    if ($match) return $match->id;
                    
                    // 2. Fallback: Always return the first record ID so it's counted in 'Total Risiko'
                    // This ensures the row count is identical to the tables.
                    return $group->sortBy('triwulan')->first()->id;
                })->values()->toArray();
            }
        }

        // Base Query for Stats
        $baseQuery = IdentifikasiRisiko::with(['analisis.probabilitas', 'analisis.dampak', 'evaluasi.probabilitas', 'evaluasi.dampak'])
            ->whereIn('id', $masterIds);

        if (!in_array($user->role_id, [1, 2])) {
            $baseQuery->where('unit_id', $user->unit_id);
        }

        // 1. Basic Stats & Risk Levels
        $allIdentifikasi = $baseQuery->get();
        
        $levelStats = ['SANGAT TINGGI' => 0, 'TINGGI' => 0, 'SEDANG' => 0, 'RENDAH' => 0, 'SANGAT RENDAH' => 0];
        $heatmap = [];
        for($p=1; $p<=5; $p++) { for($d=1; $d<=5; $d++) { $heatmap[$p][$d] = 0; } }

        $totalAnalyzed = 0;
        $totalEvaluated = 0;

        foreach($allIdentifikasi as $item) {
            /** 
             * DASHBOARD VISIBILITY LOGIC (Sync with Table)
             */
            $frekuensi = $item->frekuensi_pelaporan ?? 'triwulan';
            $showValue = false;
            
            if ($triwulan === 'all') {
                $showValue = true;
            } else {
                $targetArr = ($triwulan == 's1' ? [1, 2] : ($triwulan == 's2' ? [3, 4] : [$triwulan]));
                if ($frekuensi == 'tahunan') {
                    $showValue = true;
                } elseif ($frekuensi == 'semester') {
                    $itemSem = $item->triwulan <= 2 ? [1, 2] : [3, 4];
                    if (array_intersect($targetArr, $itemSem)) $showValue = true;
                } elseif ($frekuensi == 'triwulan') {
                    if (in_array($item->triwulan, $targetArr)) $showValue = true;
                }
            }

            if ($showValue) {
                $analysis = $item->analisis;
                $evaluasi = $item->evaluasi;
                
                $score = $evaluasi ? $evaluasi->skor_residu : ($analysis ? $analysis->skor_risiko : null);
                $rank = $evaluasi ? $evaluasi->peringkat_residu : ($analysis ? $analysis->peringkat_risiko : null);
                
                if($rank && isset($levelStats[strtoupper(trim($rank))])) {
                    $levelStats[strtoupper(trim($rank))]++;
                }

                // Heatmap coords
                $pVal = $evaluasi ? ($evaluasi->probabilitas->nilai_probabilitas ?? null) : ($analysis->probabilitas ?? null ? $analysis->probabilitas->nilai_probabilitas : null);
                $dVal = $evaluasi ? ($evaluasi->dampak->nilai_dampak ?? null) : ($analysis->dampak ?? null ? $analysis->dampak->nilai_dampak : null);
                if($pVal && $dVal && isset($heatmap[$dVal][$pVal])) {
                    $heatmap[$dVal][$pVal]++;
                }

                if ($analysis) $totalAnalyzed++;
                if ($evaluasi) $totalEvaluated++;
            }
        }

        $completedRisks = $totalEvaluated;
        $pendingRisks = $allIdentifikasi->count() - $totalEvaluated;

        // 3. Risk by Unit (Using Master IDs)
        $unitQuery = Unit::query();
        if (!in_array($user->role_id, [1, 2])) $unitQuery->where('id', $user->unit_id);
        
        $unitData = $unitQuery->withCount(['identifikasi' => function($q) use ($masterIds) {
            $q->whereIn('id', $masterIds);
        }])->orderBy('identifikasi_count', 'desc')->get()->filter(fn($u) => $u->identifikasi_count > 0)->values();

        // 4. Trend Data (Strictly Identifikasi creation)
        $trendQuery = IdentifikasiRisiko::whereIn('id', $masterIds);
        if (!in_array($user->role_id, [1, 2])) $trendQuery->where('unit_id', $user->unit_id);

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFormat = $isSqlite ? "strftime('%m', created_at)" : "DATE_FORMAT(created_at, '%m')";
        $trendData = $trendQuery->select(DB::raw('count(*) as total'), DB::raw("$monthFormat as month_num"), DB::raw('max(created_at) as date'))
            ->groupBy('month_num')->orderBy('date', 'asc')->take(12)->get()->map(function($item) {
                $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
                $item->month = $months[$item->month_num] ?? $item->month_num;
                return $item;
            });

        // 6. Top Risks
        $criticalRisks = $allIdentifikasi->filter(fn($i) => $i->analisis)
            ->sortByDesc(function($item) {
                return $item->evaluasi ? $item->evaluasi->skor_residu : $item->analisis->skor_risiko;
            })->take(10);

        // 7. Recent Activity
        $activityLogQuery = function($model) use ($user, $masterIds) {
            $q = $model->query();
            if ($model instanceof IdentifikasiRisiko) {
                $q->whereIn('id', $masterIds);
                if (!in_array($user->role_id, [1, 2])) $q->where('unit_id', $user->unit_id);
            } else {
                $q->whereHas('identifikasi', function($qi) use ($user, $masterIds) {
                    $qi->whereIn('id', $masterIds);
                    if (!in_array($user->role_id, [1, 2])) $qi->where('unit_id', $user->unit_id);
                });
            }
            return $q->orderBy('created_at', 'desc')->take(5)->get();
        };

        $recentIdentifikasi = $activityLogQuery(new IdentifikasiRisiko())->map(fn($item) => ['type' => 'ID', 'msg' => 'Identifikasi Baru', 'date' => $item->created_at, 'risk' => $item->kode_risiko]);
        $recentAnalisis = $activityLogQuery(new AnalisisRisiko())->map(fn($item) => ['type' => 'AN', 'msg' => 'Analisis Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-']);
        $recentEvaluasi = $activityLogQuery(new EvaluasiRisiko())->map(fn($item) => ['type' => 'EV', 'msg' => 'Evaluasi Selesai', 'date' => $item->created_at, 'risk' => $item->identifikasi->kode_risiko ?? '-']);
        $activities = $recentIdentifikasi->concat($recentAnalisis)->concat($recentEvaluasi)->sortByDesc('date')->take(6);

        // 5. Risk by Category Distribution
        $categoryStats = KategoriRisiko::withCount(['identifikasi' => function($q) use ($masterIds, $user) {
            $q->whereIn('id', $masterIds);
            if (!in_array($user->role_id, [1, 2])) $q->where('unit_id', $user->unit_id);
        }])->get()->map(fn($cat) => ['name' => $cat->nama_kategori, 'count' => $cat->identifikasi_count]);

        $totalRisks = $allIdentifikasi->count();

        if ($request->ajax()) {
            return response()->json([
                'totalRisks' => $totalRisks,
                'totalAnalyzed' => $totalAnalyzed,
                'totalEvaluated' => $totalEvaluated,
                'levelStats' => $levelStats,
                'pendingRisks' => $pendingRisks,
                'completedRisks' => $completedRisks,
                'unitData' => $unitData,
                'trendData' => $trendData,
                'heatmap' => $heatmap,
                'categoryStats' => $categoryStats,
                'criticalRisks' => $criticalRisks->map(function($rk) {
                    $score = $rk->evaluasi ? $rk->evaluasi->skor_residu : ($rk->analisis->skor_risiko ?? 0);
                    $rank = strtoupper($rk->evaluasi ? $rk->evaluasi->peringkat_residu : ($rk->analisis->peringkat_risiko ?? '—'));
                    return [
                        'iteration' => 0, // Will be set in JS
                        'kegiatan' => $rk->kegiatan,
                        'kode' => $rk->kode_risiko,
                        'score' => $score,
                        'rank' => $rank,
                        'status' => $rk->evaluasi ? 'SELESAI' : 'PROSES'
                    ];
                })->values(),
                'activities' => $activities->map(function($a) {
                    return [
                        'type' => $a['type'],
                        'msg' => $a['msg'],
                        'risk' => $a['risk'],
                        'date' => $a['date']->diffForHumans()
                    ];
                })->values()
            ]);
        }

        return view('dashboard', compact(
            'allIdentifikasi', 'totalRisks', 'totalAnalyzed', 'totalEvaluated', 'levelStats',
            'pendingRisks', 'completedRisks',
            'unitData', 'trendData', 'criticalRisks', 'heatmap', 'categoryStats', 'activities', 'activePeriode'
        ));
    }
}
