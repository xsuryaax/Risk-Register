<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $data = Periode::orderBy('tahun', 'desc')->get();
        return view('pages.periode', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|unique:tbl_periode,tahun',
        ]);

        Periode::create([
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
            'status' => false
        ]);

        return redirect()->back()->with('success', 'Periode berhasil ditambahkan');
    }

    public function activate($id)
    {
        // Deactivate all
        Periode::where('status', true)->update(['status' => false]);

        // Activate target
        $periode = Periode::findOrFail($id);
        $periode->update(['status' => true]);

        return redirect()->back()->with('success', "Periode Tahun $periode->tahun Berhasil Diaktifkan");
    }

    public function destroy($id)
    {
        $periode = Periode::findOrFail($id);
        if ($periode->status) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus periode yang sedang aktif');
        }
        $periode->delete();
        return redirect()->back()->with('success', 'Periode berhasil dihapus');
    }
}
