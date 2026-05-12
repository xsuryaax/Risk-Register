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
        $user = Auth::user();
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup']);

        // Security: Non-Admin/Mutu can only see their own unit
        if (!in_array($user->role_id, [1, 2])) {
            $query->where('unit_id', $user->unit_id);
        } elseif ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);
        $units = Unit::all();
            
        return view('pages.identifikasi-risiko.index', compact('data', 'units'));
    }

    public function create()
    {
        $user = Auth::user();
        $kategori = KategoriRisiko::all();
        $ruangLingkup = RuangLingkup::all();
        
        // Non-Admin/Mutu only see their own unit in select (if applicable)
        if (!in_array($user->role_id, [1, 2])) {
            $units = Unit::where('id', $user->unit_id)->get();
        } else {
            $units = Unit::all();
        }
        
        return view('pages.identifikasi-risiko.create', compact('kategori', 'ruangLingkup', 'units'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $risk = IdentifikasiRisiko::findOrFail($id);
        
        // Security: Prevent accessing other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $risk->unit_id != $user->unit_id) {
            return redirect()->route('identifikasi-risiko.index')->with('error', 'Anda tidak memiliki hak akses ke data ini.');
        }

        $kategori = KategoriRisiko::all();
        $ruangLingkup = RuangLingkup::all();
        
        if (!in_array($user->role_id, [1, 2])) {
            $units = Unit::where('id', $user->unit_id)->get();
        } else {
            $units = Unit::all();
        }
        
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
            // Get category first letter
            $kat = KategoriRisiko::find($request->kategori_risiko_id);
            $prefix = $kat ? strtoupper(substr($kat->nama_kategori, 0, 1)) : 'R';
            
            // Get count for this prefix in current year
            $year = date('Y');
            $count = IdentifikasiRisiko::where('kode_risiko', 'like', $prefix . '-' . $year . '-%')->count() + 1;
            $kode = $prefix . '-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
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
        $user = Auth::user();
        $risk = IdentifikasiRisiko::findOrFail($id);

        // Security: Prevent updating other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $risk->unit_id != $user->unit_id) {
            return redirect()->route('identifikasi-risiko.index')->with('error', 'Anda tidak memiliki hak akses untuk merubah data ini.');
        }

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

        $risk->update($request->except('unit_id'));

        return redirect()->route('identifikasi-risiko.index')->with('success', 'Identifikasi risiko berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $risk = IdentifikasiRisiko::findOrFail($id);

        // Security: Prevent deleting other unit's risks
        if (!in_array($user->role_id, [1, 2]) && $risk->unit_id != $user->unit_id) {
            return redirect()->route('identifikasi-risiko.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data ini.');
        }
        
        $risk->delete();

        return redirect()->back()->with('success', 'Identifikasi risiko berhasil dihapus.');
    }
}
