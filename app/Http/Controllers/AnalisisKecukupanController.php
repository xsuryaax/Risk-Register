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
            
            if ($viewTriwulan !== 'all') {
                $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : [$viewTriwulan]));
                
                $ids = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                    ->get()
                    ->groupBy('kode_risiko')
                    ->map(function($group) use ($targetVal, $viewTriwulan) {
                        $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                        if ($match) return $match->id;
                        
                        $first = $group->sortBy('triwulan')->first();
                        $frekuensi = $first->frekuensi_pelaporan ?? 'triwulan';
                        
                        if ($frekuensi === 'tahunan') return $first->id;
                        if ($frekuensi === 'semester') {
                            $riskSemester = $first->triwulan <= 2 ? [1, 2] : [3, 4];
                            if (array_intersect($targetVal, $riskSemester)) return $first->id;
                        }
                        return null;
                    })
                    ->filter()->values()->toArray();

                $query->whereIn('id', $ids);
            }
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
            return view('pages.analisis-kecukupan.index', compact('data', 'units', 'viewTriwulan', 'activePeriode'))->render();
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

        return redirect()->route('analisis-kecukupan.index')->with('success', 'Analisis kecukupan berhasil disimpan.');
    }
}
