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
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup', 'analisis', 'evaluasi']);

        if ($activePeriode) {
            $query->where('periode_id', $activePeriode->id);
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

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $units = \App\Models\Unit::orderBy('nama_unit')->get();

        if ($request->ajax()) {
            return view('pages.evaluasi-risiko.index', compact('data', 'units'))->render();
        }

        return view('pages.evaluasi-risiko.index', compact('data', 'units'));
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

        $request->validate([
            'probabilitas_residu_id' => 'required',
            'dampak_residu_id' => 'required',
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

        // Calculate % Reduction
        $reduction = $initialScore > 0 ? (($initialScore - $score) / $initialScore) * 100 : 0;

        EvaluasiRisiko::updateOrCreate(
            ['identifikasi_risiko_id' => $id],
            [
                'probabilitas_residu_id' => $request->probabilitas_residu_id,
                'dampak_residu_id' => $request->dampak_residu_id,
                'skor_residu' => $score,
                'peringkat_residu' => $ranking,
                'penurunan_persen' => $reduction,
            ]
        );

        return redirect()->route('evaluasi-risiko.index')->with('success', 'Evaluasi risiko berhasil disimpan.');
    }
}
