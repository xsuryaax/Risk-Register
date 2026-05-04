<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\AnalisisKecukupan;
use Illuminate\Http\Request;

class AnalisisKecukupanController extends Controller
{
    public function index()
    {
        // Only show risks that have been analyzed
        $data = IdentifikasiRisiko::with(['analisis', 'kategori', 'ruangLingkup', 'analisisKecukupan'])
            ->has('analisis')
            ->paginate(10);
            
        return view('pages.analisis-kecukupan.index', compact('data'));
    }

    public function edit($id)
    {
        $identifikasi = IdentifikasiRisiko::with(['analisis', 'analisisKecukupan'])->findOrFail($id);
        return view('pages.analisis-kecukupan.form', compact('identifikasi'));
    }

    public function update(Request $request, $id)
    {
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
