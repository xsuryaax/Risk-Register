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
        $periodes = \App\Models\Periode::orderBy('tahun', 'desc')->get();
        
        // Target period to view
        $viewPeriodeId = $request->periode_id ?? ($activePeriode->id ?? null);

        $viewTriwulan = $request->view_triwulan ?? 'all';
        $query = IdentifikasiRisiko::with([
            'kategori', 
            'ruangLingkup', 
            'analisis.probabilitas', 
            'analisis.dampak', 
            'analisisKecukupan',
            'evaluasi'
        ]);

        if ($viewPeriodeId) {
            $query->where('periode_id', $viewPeriodeId);

            $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : [$viewTriwulan]));
            $ids = IdentifikasiRisiko::where('periode_id', $viewPeriodeId)
                ->get()
                ->groupBy('kode_risiko')
                ->map(function($group) use ($targetVal, $viewTriwulan) {
                    if ($viewTriwulan === 'all') {
                        return $group->sortByDesc('triwulan')->first()->id;
                    }
                    $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                    return $match ? $match->id : $group->sortByDesc('triwulan')->first()->id;
                })
                ->values()->toArray();

            $query->whereIn('id', $ids);
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

        $risikos = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $units = \App\Models\Unit::orderBy('nama_unit')->get();

        // Get list of activities already pulled to active period to prevent duplicates
        $pulledActivities = [];
        if ($activePeriode && $viewPeriodeId != $activePeriode->id) {
            $pulledActivities = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                ->when(!in_array($user->role_id, [1, 2]), function($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })
                ->pluck('kegiatan')
                ->toArray();
        }

        if ($request->ajax()) {
            return view('pages.daftar-risiko._table', compact('risikos', 'units', 'activePeriode', 'viewPeriodeId', 'pulledActivities', 'viewTriwulan'))->render();
        }

        return view('pages.daftar-risiko.index', compact('risikos', 'units', 'activePeriode', 'periodes', 'viewPeriodeId', 'pulledActivities', 'viewTriwulan'));
    }
}
