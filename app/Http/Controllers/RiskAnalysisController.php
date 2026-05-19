<?php

namespace App\Http\Controllers;

use App\Models\AnalisisRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiskAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activePeriode = \App\Models\Periode::getActive();
        $viewTriwulan = $request->view_triwulan ?? 'all';
        $query = IdentifikasiRisiko::with([
            'analisis.probabilitas', 
            'analisis.dampak', 
            'evaluasi.probabilitas', 
            'evaluasi.dampak',
            'unit', 
            'kategori', 
            'ruangLingkup'
        ]);

        if ($activePeriode) {
            $query->where('periode_id', $activePeriode->id);
            
            /**
             * MASTER LIST LOGIC:
             * - Shows every unique risk in the period exactly once.
             * - Priority: Matches current triwulan filter.
             * - Fallback: Latest (max) triwulan available for that risk.
             */
            $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : [$viewTriwulan]));
            $ids = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                ->get()
                ->groupBy('kode_risiko')
                ->map(function($group) use ($targetVal, $viewTriwulan) {
                    // Sort descending to prioritize LATEST triwulan in range (e.g. Q2 > Q1)
                    $group = $group->sortByDesc('triwulan');

                    if ($viewTriwulan === 'all') {
                        return $group->first()->id;
                    }
                    
                    $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                    return $match ? $match->id : $group->first()->id;
                })
                ->values()
                ->toArray();

            $query->whereIn('id', $ids);
        } else {
            $query->whereRaw('1 = 0');
        }

        // Security: Scoping by Unit
        if (!in_array($user->role_id, [1, 2])) {
            $query->where('unit_id', $user->unit_id);
        } elseif ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kegiatan', 'like', "%$search%")
                  ->orWhere('kode_risiko', 'like', "%$search%");
            });
        }

        // Status Filter (Pending/Evaluated)
        if ($request->status == 'pending') {
            $query->whereDoesntHave('evaluasi');
        } elseif ($request->status == 'evaluated') {
            $query->whereHas('evaluasi');
        }

        // Peringkat (Warna) Filter - Dynamic: Residual > Initial
        if ($request->filled('peringkat')) {
            $peringkat = strtoupper($request->peringkat);
            $query->where(function($q) use ($peringkat) {
                $q->whereHas('evaluasi', function($qe) use ($peringkat) {
                    $qe->where('peringkat_residu', $peringkat);
                })
                ->orWhere(function($qo) use ($peringkat) {
                    $qo->whereDoesntHave('evaluasi')
                       ->whereHas('analisis', function($qa) use ($peringkat) {
                           $qa->where('peringkat_risiko', $peringkat);
                       });
                });
            });
        }

        // Unit Filter
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $data = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $units = \App\Models\Unit::orderBy('nama_unit')->get();
        $probs = Probabilitas::orderBy('nilai_probabilitas')->get();
        $damps = Dampak::orderBy('nilai_dampak')->get();

        if ($request->ajax()) {
            return view('pages.analisis-risiko._table', compact('data', 'units', 'probs', 'damps', 'viewTriwulan', 'activePeriode'))->render();
        }

        return view('pages.analisis-risiko.index', compact('data', 'units', 'probs', 'damps', 'viewTriwulan', 'activePeriode'));
    }
    public function edit($id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::with('analisis')->findOrFail($id);

        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return redirect()->route('analisis-risiko.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
        }

        $probabilitas = Probabilitas::orderBy('nilai_probabilitas', 'asc')->get();
        $dampak = Dampak::orderBy('nilai_dampak', 'asc')->get();
        $units = \App\Models\Unit::orderBy('nama_unit', 'asc')->get();
        
        return view('pages.analisis-risiko.form', compact('identifikasi', 'probabilitas', 'dampak', 'units'));
    }

    public function store(Request $request, $id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::findOrFail($id);

        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        // --- IMPROVED: Frequency-Aware Smart Auto-Duplication ---
        $targetTW = $request->view_triwulan_active;
        $activeTW = $identifikasi->triwulan;
        $frekuensi = $identifikasi->frekuensi_pelaporan ?? 'triwulan';
        
        $shouldDuplicate = false;
        if (in_array($targetTW, ['1', '2', '3', '4']) && $activeTW != $targetTW) {
            if ($frekuensi === 'triwulan') {
                $shouldDuplicate = true;
            } elseif ($frekuensi === 'semester') {
                // Duplicate only if moving to a different semester
                $currentSem = $activeTW <= 2 ? 1 : 2;
                $targetSem = $targetTW <= 2 ? 1 : 2;
                if ($currentSem != $targetSem) {
                    $shouldDuplicate = true;
                }
            }
            // For 'tahunan', $shouldDuplicate stays false -> use same record for all q
        }
        
        if ($shouldDuplicate) {
            // Check if a copy already exists for this target TW
            $existing = IdentifikasiRisiko::where('kode_risiko', $identifikasi->kode_risiko)
                ->where('triwulan', $targetTW)
                ->where('periode_id', $identifikasi->periode_id)
                ->first();
            
            if ($existing) {
                $id = $existing->id;
            } else {
                // Duplicate identity
                $newIdent = $identifikasi->replicate();
                $newIdent->triwulan = $targetTW;
                $newIdent->save();
                $id = $newIdent->id;
            }
        }

        $request->validate([
            'uraian_pengendalian' => 'required',
            'desain_pengendalian' => 'required',
            'efektifitas_pengendalian' => 'required',
            'probabilitas_id' => 'required',
            'dampak_id' => 'required',
            'pemilik_risiko' => 'required',
        ]);

        $prob = Probabilitas::findOrFail($request->probabilitas_id);
        $dam = Dampak::findOrFail($request->dampak_id);
        $score = $prob->nilai_probabilitas * $dam->nilai_dampak;

        // Determine Peringkat Risiko based on reference matrix
        if ($score >= 15) {
            $ranking = 'SANGAT TINGGI';
        } elseif ($score >= 10) {
            $ranking = 'TINGGI';
        } elseif ($score >= 5) {
            $ranking = 'SEDANG';
        } elseif ($score >= 3) {
            $ranking = 'RENDAH';
        } else {
            $ranking = 'SANGAT RENDAH';
        }

        AnalisisRisiko::updateOrCreate(
            ['identifikasi_risiko_id' => $id],
            [
                'uraian_pengendalian' => $request->uraian_pengendalian,
                'desain_pengendalian' => $request->desain_pengendalian,
                'efektifitas_pengendalian' => $request->efektifitas_pengendalian,
                'probabilitas_id' => $request->probabilitas_id,
                'dampak_id' => $request->dampak_id,
                'skor_risiko' => $score,
                'peringkat_risiko' => $ranking,
                'pemilik_risiko' => $request->pemilik_risiko,
            ]
        );

        // --- IMPROVED: Forward-Only Multi-Quarter Sync ---
        // Sync logic: Only update/create records for FUTURE quarters.
        $allQuarters = [1, 2, 3, 4];
        foreach ($allQuarters as $q) {
            if ($q <= $targetTW) continue; // Only Sync Forward

            // Find or Copy Identification for this quarter
            $otherIdent = IdentifikasiRisiko::where('kode_risiko', $identifikasi->kode_risiko)
                ->where('periode_id', $identifikasi->periode_id)
                ->where('triwulan', $q)
                ->first();

            if (!$otherIdent) {
                // If the quarter doesn't even have the risk record yet, create it
                $otherIdent = $identifikasi->replicate();
                $otherIdent->triwulan = $q;
                $otherIdent->save();
            }

            // Now check if it has analysis. If empty, sync it.
            // We overwrite future quarters if the user edits a previous one (Forward Override)
            AnalisisRisiko::updateOrCreate(
                ['identifikasi_risiko_id' => $otherIdent->id],
                [
                    'uraian_pengendalian' => $request->uraian_pengendalian,
                    'desain_pengendalian' => $request->desain_pengendalian,
                    'efektifitas_pengendalian' => $request->efektifitas_pengendalian,
                    'probabilitas_id' => $request->probabilitas_id,
                    'dampak_id' => $request->dampak_id,
                    'skor_risiko' => $score,
                    'peringkat_risiko' => $ranking,
                    'pemilik_risiko' => $request->pemilik_risiko,
                ]
            );
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Analisis berhasil disimpan',
                'score' => $score,
                'rank' => ucfirst(strtolower($ranking)),
                'color' => $score >= 15 ? '#c00000' : ($score >= 10 ? '#ff9900' : ($score >= 5 ? '#ffeb3b' : ($score >= 3 ? '#0d6efd' : '#198754'))),
                'new_id' => $id,
                'text_color' => ($ranking == 'SEDANG') ? 'text-dark' : 'text-white'
            ]);
        }

        return redirect()->route('analisis-risiko.index')->with('success', 'Analisis risiko berhasil disimpan.');
    }
}
