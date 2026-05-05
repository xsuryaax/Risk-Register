<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiRisiko;
use App\Models\IdentifikasiRisiko;
use App\Models\Probabilitas;
use App\Models\Dampak;
use Illuminate\Http\Request;

class EvaluasiRisikoController extends Controller
{
    public function index()
    {
        $data = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup', 'analisis', 'evaluasi'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pages.evaluasi-risiko.index', compact('data'));
    }

    public function edit($id)
    {
        $identifikasi = IdentifikasiRisiko::with(['analisis', 'evaluasi'])->findOrFail($id);
        
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

        // Determine Peringkat
        $ranking = 'RENDAH';
        if ($score >= 20) {
            $ranking = 'SANGAT TINGGI';
        } elseif ($score >= 13) {
            $ranking = 'TINGGI';
        } elseif ($score >= 5) {
            $ranking = 'SEDANG';
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
