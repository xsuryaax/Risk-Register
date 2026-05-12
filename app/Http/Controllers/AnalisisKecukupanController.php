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
        $query = IdentifikasiRisiko::with(['analisis', 'kategori', 'ruangLingkup', 'analisisKecukupan', 'evaluasi'])
            ->has('analisis');

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
                // Jika sudah ada evaluasi, cocokkan peringkat residu
                $q->where(function($sq1) use ($peringkat) {
                    $sq1->whereHas('evaluasi', function($qe) use ($peringkat) {
                        $qe->where('peringkat_residu', $peringkat);
                    });
                })
                // Jika belum ada evaluasi, cocokkan peringkat awal
                ->orWhere(function($sq2) use ($peringkat) {
                    $sq2->whereDoesntHave('evaluasi')
                       ->whereHas('analisis', function($qa) use ($peringkat) {
                           $qa->where('peringkat_risiko', $peringkat);
                       });
                });
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $units = Unit::orderBy('nama_unit')->get();
        
        if ($request->ajax()) {
            return view('pages.analisis-kecukupan.index', compact('data', 'units'))->render();
        }

        return view('pages.analisis-kecukupan.index', compact('data', 'units'));
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
