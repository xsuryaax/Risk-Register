<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use Illuminate\Http\Request;

class DaftarRisikoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activePeriode = \App\Models\Periode::getActive();
        $query = IdentifikasiRisiko::with([
            'kategori', 
            'ruangLingkup', 
            'analisis.probabilitas', 
            'analisis.dampak', 
            'analisisKecukupan',
            'evaluasi'
        ]);

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
                // If it HAS EVALUATION, it MUST match the rank.
                $q->where(function($sq1) use ($peringkat) {
                    $sq1->whereHas('evaluasi', function($qe) use ($peringkat) {
                        $qe->where('peringkat_residu', $peringkat);
                    });
                })
                // OR it HAS NO EVALUATION and its INITIAL rank matches.
                ->orWhere(function($sq2) use ($peringkat) {
                    $sq2->whereDoesntHave('evaluasi')
                       ->whereHas('analisis', function($qa) use ($peringkat) {
                           $qa->where('peringkat_risiko', $peringkat);
                       });
                });
            });
        }

        $risikos = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $units = \App\Models\Unit::orderBy('nama_unit')->get();

        if ($request->ajax()) {
            return view('pages.daftar-risiko._table', compact('risikos', 'units'))->render();
        }

        return view('pages.daftar-risiko.index', compact('risikos', 'units'));
    }
}
