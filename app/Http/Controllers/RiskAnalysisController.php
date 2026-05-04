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
    public function index()
    {
        // Get all identifications with their analysis (if any)
        $data = IdentifikasiRisiko::with(['analisis', 'unit', 'kategori', 'ruangLingkup'])->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.analisis-risiko.index', compact('data'));
    }

    public function edit($id)
    {
        $identifikasi = IdentifikasiRisiko::with('analisis')->findOrFail($id);
        $probabilitas = Probabilitas::orderBy('nilai_probabilitas', 'asc')->get();
        $dampak = Dampak::orderBy('nilai_dampak', 'asc')->get();
        $units = \App\Models\Unit::orderBy('nama_unit', 'asc')->get();
        
        return view('pages.analisis-risiko.form', compact('identifikasi', 'probabilitas', 'dampak', 'units'));
    }

    public function store(Request $request, $id)
    {
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

        // Determine Peringkat Risiko
        // 1-4: Rendah, 5-12: Sedang, 13-19: Tinggi, 20-25: Sangat Tinggi
        $ranking = 'RENDAH';
        if ($score >= 20) {
            $ranking = 'SANGAT TINGGI';
        } elseif ($score >= 13) {
            $ranking = 'TINGGI';
        } elseif ($score >= 5) {
            $ranking = 'SEDANG';
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

        return redirect()->route('analisis-risiko.index')->with('success', 'Analisis risiko berhasil disimpan.');
    }
}
