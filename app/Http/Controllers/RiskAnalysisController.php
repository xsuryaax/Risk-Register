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
        $query = IdentifikasiRisiko::with(['analisis', 'unit', 'kategori', 'ruangLingkup']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kegiatan', 'like', "%$search%")
                  ->orWhere('kode_risiko', 'like', "%$search%");
            });
        }

        // Peringkat (Warna) Filter
        if ($request->filled('peringkat')) {
            $peringkat = strtoupper($request->peringkat);
            $query->whereHas('analisis', function($q) use ($peringkat) {
                $q->where('peringkat_risiko', $peringkat);
            });
        }

        // Pemilik Filter
        if ($request->filled('pemilik')) {
            $pemilik = $request->pemilik;
            $query->whereHas('analisis', function($q) use ($pemilik) {
                $q->where('pemilik_risiko', 'like', "%$pemilik%");
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Get unique owners (cleaned)
        $owners = AnalisisRisiko::whereNotNull('pemilik_risiko')
            ->select('pemilik_risiko')
            ->distinct()
            ->pluck('pemilik_risiko')
            ->map(fn($o) => trim($o)) 
            ->filter()
            ->unique()
            ->sort();

        if ($request->ajax()) {
            return view('pages.analisis-risiko._table', compact('data'))->render();
        }

        return view('pages.analisis-risiko.index', compact('data', 'owners'));
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
