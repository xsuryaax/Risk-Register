<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\RuangLingkup;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiskIdentificationController extends Controller
{
    public function index(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup']);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);
        $units = Unit::all();
            
        return view('pages.identifikasi-risiko.index', compact('data', 'units'));
    }

    public function create()
    {
        $kategori = KategoriRisiko::all();
        $ruangLingkup = RuangLingkup::all();
        $units = Unit::all();
        
        return view('pages.identifikasi-risiko.create', compact('kategori', 'ruangLingkup', 'units'));
    }

    public function edit($id)
    {
        $risk = IdentifikasiRisiko::findOrFail($id);
        $kategori = KategoriRisiko::all();
        $ruangLingkup = RuangLingkup::all();
        $units = Unit::all();
        
        return view('pages.identifikasi-risiko.edit', compact('risk', 'kategori', 'ruangLingkup', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required',
            'tujuan_kegiatan' => 'required',
            'kategori_risiko_id' => 'required',
            'ruang_lingkup_id' => 'required',
            'pernyataan_risiko' => 'required',
            'sebab' => 'required',
            'jenis_risiko' => 'required',
            'dampak' => 'required',
        ]);

        // Handle Kode Risiko
        $kode = $request->kode_risiko;
        if (empty($kode)) {
            $count = IdentifikasiRisiko::count() + 1;
            $kode = 'RSK-' . date('Y') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        IdentifikasiRisiko::create([
            'unit_id' => Auth::user()->unit_id ?? 1,
            'kegiatan' => $request->kegiatan,
            'tujuan_kegiatan' => $request->tujuan_kegiatan,
            'kode_risiko' => $kode,
            'kategori_risiko_id' => $request->kategori_risiko_id,
            'ruang_lingkup_id' => $request->ruang_lingkup_id,
            'pernyataan_risiko' => $request->pernyataan_risiko,
            'sebab' => $request->sebab,
            'jenis_risiko' => $request->jenis_risiko,
            'dampak' => $request->dampak,
            'user_id' => Auth::id() ?? 1,
        ]);

        return redirect()->route('identifikasi-risiko.index')->with('success', 'Identifikasi risiko berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required',
            'tujuan_kegiatan' => 'required',
            'kategori_risiko_id' => 'required',
            'ruang_lingkup_id' => 'required',
            'pernyataan_risiko' => 'required',
            'sebab' => 'required',
            'jenis_risiko' => 'required',
            'dampak' => 'required',
        ]);

        $risk = IdentifikasiRisiko::findOrFail($id);
        $risk->update($request->except('unit_id'));

        return redirect()->route('identifikasi-risiko.index')->with('success', 'Identifikasi risiko berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $risk = IdentifikasiRisiko::findOrFail($id);
        $risk->delete();

        return redirect()->back()->with('success', 'Identifikasi risiko berhasil dihapus.');
    }
}
