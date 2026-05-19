<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\AnalisisKecukupan;
use App\Models\Unit;
use Illuminate\Http\Request;

class AnalisisKecukupanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activePeriode = \App\Models\Periode::getActive();
        $viewTriwulan = $request->view_triwulan ?? 'all';
        $query = IdentifikasiRisiko::with(['analisis', 'kategori', 'ruangLingkup', 'analisisKecukupan', 'evaluasi'])
            ->has('analisis');

        if ($activePeriode) {
            $query->where('periode_id', $activePeriode->id);
            
            $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : ($viewTriwulan == 'all' ? [1,2,3,4] : [$viewTriwulan])));
            
            $ids = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                ->get()
                ->groupBy('kode_risiko')
                ->map(function($group) use ($targetVal, $viewTriwulan) {
                    // Sorting DESC so that we always pick the LATEST (e.g. Q2 over Q1 in S1)
                    $group = $group->sortByDesc('triwulan');

                    // If 'all', just take the absolute latest available
                    if ($viewTriwulan == 'all') {
                        return $group->first()->id;
                    }

                    // 1. Priority: Match in target (e.g. pick Q4 if filtering for S2 and Q4 exists)
                    $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                    if ($match) return $match->id;
                    
                    // 2. Fallback: Take anything available (already sorted DESC)
                    return $group->first()->id;
                })
                ->filter()->values()->toArray();

            $query->whereIn('id', $ids);
        } else {
            $query->whereRaw('1 = 0');
        }

        // Security: Non-Admin/Mutu can only see their own unit
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

        // Peringkat Filter (Initial Only for Adequacy)
        if ($request->filled('peringkat')) {
            $peringkat = strtoupper($request->peringkat);
            $query->whereHas('analisis', function($qa) use ($peringkat) {
                $qa->where('peringkat_risiko', $peringkat);
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $units = Unit::orderBy('nama_unit')->get();
        
        if ($request->ajax()) {
            return view('pages.analisis-kecukupan._table', compact('data', 'units', 'viewTriwulan', 'activePeriode'))->render();
        }

        return view('pages.analisis-kecukupan.index', compact('data', 'units', 'viewTriwulan', 'activePeriode'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::with(['analisis', 'analisisKecukupan'])->findOrFail($id);
        
        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return redirect()->route('analisis-kecukupan.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
        }

        $units = Unit::orderBy('nama_unit')->get();
        return view('pages.analisis-kecukupan.form', compact('identifikasi', 'units'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::findOrFail($id);

        // Security: Prevent updating other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return redirect()->route('analisis-kecukupan.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
        }

        // --- IMPROVED: Frequency-Aware Smart Auto-Duplication ---
        $targetTW = $request->view_triwulan;
        $activeTW = $identifikasi->triwulan;
        $frekuensi = $identifikasi->frekuensi_pelaporan ?? 'triwulan';
        
        $shouldDuplicate = false;
        if (in_array($targetTW, ['1', '2', '3', '4']) && $activeTW != $targetTW) {
            if ($frekuensi === 'triwulan') {
                $shouldDuplicate = true;
            } elseif ($frekuensi === 'semester') {
                $currentSem = $activeTW <= 2 ? 1 : 2;
                $targetSem = $targetTW <= 2 ? 1 : 2;
                if ($currentSem != $targetSem) {
                    $shouldDuplicate = true;
                }
            }
        }
        
        if ($shouldDuplicate) {
            $existing = IdentifikasiRisiko::where('kode_risiko', $identifikasi->kode_risiko)
                ->where('triwulan', $targetTW)
                ->where('periode_id', $identifikasi->periode_id)
                ->first();
            
            if ($existing) {
                $id = $existing->id;
            } else {
                $newIdent = $identifikasi->replicate();
                $newIdent->triwulan = $targetTW;
                $newIdent->save();
                
                if ($identifikasi->analisis) {
                    $newAnalisis = $identifikasi->analisis->replicate();
                    $newAnalisis->identifikasi_risiko_id = $newIdent->id;
                    $newAnalisis->save();
                }
                $id = $newIdent->id;
            }
        }

        $request->validate([
            'uraian_rencana' => 'required',
            'jadwal' => 'required',
            'pj_tindak_lanjut' => 'required',
        ]);

        AnalisisKecukupan::updateOrCreate(
            ['identifikasi_risiko_id' => $id],
            [
                'uraian_rencana' => $request->uraian_rencana,
                'jadwal' => $request->jadwal,
                'pj_tindak_lanjut' => $request->pj_tindak_lanjut,
            ]
        );

        // --- IMPROVED: Forward-Only Multi-Quarter Sync ---
        $allQuarters = [1, 2, 3, 4];
        foreach ($allQuarters as $q) {
            if ($q <= $targetTW) continue; // Only Sync Forward

            $otherIdent = IdentifikasiRisiko::where('kode_risiko', $identifikasi->kode_risiko)
                ->where('periode_id', $identifikasi->periode_id)
                ->where('triwulan', $q)
                ->first();

            if (!$otherIdent) {
                $otherIdent = $identifikasi->replicate();
                $otherIdent->triwulan = $q;
                $otherIdent->save();
                
                if ($identifikasi->analisis) {
                    $newAnalisis = $identifikasi->analisis->replicate();
                    $newAnalisis->identifikasi_risiko_id = $otherIdent->id;
                    $newAnalisis->save();
                }
            }

            // Overwrite future quarters to keep them synced with current change
            AnalisisKecukupan::updateOrCreate(
                ['identifikasi_risiko_id' => $otherIdent->id],
                [
                    'uraian_rencana' => $request->uraian_rencana,
                    'jadwal' => $request->jadwal,
                    'pj_tindak_lanjut' => $request->pj_tindak_lanjut,
                ]
            );
        }

        return redirect()->route('analisis-kecukupan.index')->with('success', 'Analisis kecukupan berhasil disimpan.');
    }
}
