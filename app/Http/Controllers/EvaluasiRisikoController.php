<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;
use Illuminate\Http\Request;

class EvaluasiRisikoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activePeriode = \App\Models\Periode::getActive();
        $viewTriwulan = $request->view_triwulan ?? 'all';
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup', 'analisis', 'evaluasi']);

        if ($activePeriode) {
            $query->where('periode_id', $activePeriode->id);
            
            $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : [$viewTriwulan]));
            $ids = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                ->get()
                ->groupBy('kode_risiko')
                ->map(function($group) use ($targetVal, $viewTriwulan) {
                    if ($viewTriwulan === 'all') {
                        return $group->sortByDesc('triwulan')->first()->id;
                    }
                    $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                    return $match ? $match->id : $group->sortByDesc('triwulan')->first()->id;
                })
                ->values()->toArray();

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

        // Peringkat Filter (Current Status) - Dynamic: Residual > Initial
        if ($request->filled('peringkat')) {
            $peringkat = strtoupper($request->peringkat);
            $query->where(function($q) use ($peringkat) {
                // If evaluated, match residual rank
                $q->whereHas('evaluasi', function($qe) use ($peringkat) {
                    $qe->where('peringkat_residu', $peringkat);
                })
                // If not evaluated, match initial rank
                ->orWhere(function($qn) use ($peringkat) {
                    $qn->whereDoesntHave('evaluasi')
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

        if ($request->ajax()) {
            return view('pages.evaluasi-risiko._table', compact('data', 'units', 'viewTriwulan', 'activePeriode'))->render();
        }

        return view('pages.evaluasi-risiko.index', compact('data', 'units', 'viewTriwulan', 'activePeriode'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::with(['analisis', 'evaluasi'])->findOrFail($id);
        
        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return redirect()->route('evaluasi-risiko.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
        }

        // Ensure analysis exists first
        if (!$identifikasi->analisis) {
            return redirect()->route('evaluasi-risiko.index')->with('error', 'Selesaikan Analisis Risiko terlebih dahulu.');
        }

        $probabilitas = Probabilitas::orderBy('nilai_probabilitas', 'asc')->get();
        $dampak = Dampak::orderBy('nilai_dampak', 'asc')->get();
        
        return view('pages.evaluasi-risiko.form', compact('identifikasi', 'probabilitas', 'dampak'));
    }

    public function store(Request $request, $id)
    {
        $user = auth()->user();
        $identifikasi = IdentifikasiRisiko::with('analisis')->findOrFail($id);

        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $identifikasi->unit_id != $user->unit_id) {
            return redirect()->route('evaluasi-risiko.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
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
                if ($identifikasi->analisisKecukupan) {
                    $newKecukupan = $identifikasi->analisisKecukupan->replicate();
                    $newKecukupan->identifikasi_risiko_id = $newIdent->id;
                    $newKecukupan->save();
                }
                $id = $newIdent->id;
            }
        }

        $request->validate([
            'frekuensi_kejadian' => 'required|string',
            'uraian_kejadian' => 'required|string',
            'probabilitas_residu_id' => 'required',
            'dampak_residu_id' => 'required',
            'rekomendasi_tindak_lanjut' => 'required|string',
        ]);

        $prob = Probabilitas::findOrFail($request->probabilitas_residu_id);
        $dam = Dampak::findOrFail($request->dampak_residu_id);
        $score = $prob->nilai_probabilitas * $dam->nilai_dampak;

        // Baseline score for reduction calculation
        $identifikasi = IdentifikasiRisiko::with('analisis')->findOrFail($id);
        $initialScore = $identifikasi->analisis->skor_risiko;

        // Determine Peringkat based on reference matrix
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

        // Calculate % Change (Positive = Reduction, Negative = Increase)
        $reduction = $initialScore > 0 ? (($initialScore - $score) / $initialScore) * 100 : 0;

        EvaluasiRisiko::updateOrCreate(
            ['identifikasi_risiko_id' => $id],
            [
                'frekuensi_kejadian' => $request->frekuensi_kejadian,
                'status_kejadian' => (!empty($request->frekuensi_kejadian) && !in_array(strtolower($request->frekuensi_kejadian), ['0', 'tidak ada', 'nihil', '-'])) ? 'Ya' : 'Tidak',
                'uraian_kejadian' => $request->uraian_kejadian,
                'probabilitas_residu_id' => $request->probabilitas_residu_id,
                'dampak_residu_id' => $request->dampak_residu_id,
                'skor_residu' => $score,
                'peringkat_residu' => $ranking,
                'penurunan_persen' => $reduction,
                'rekomendasi_tindak_lanjut' => $request->rekomendasi_tindak_lanjut,
            ]
        );

        return redirect()->route('evaluasi-risiko.index')->with('success', 'Evaluasi risiko berhasil disimpan.');
    }
}
