<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\Unit;
use App\Models\Periode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    private function applyFilters($query, Request $request)
    {
        $user = Auth::user();
        $periodeId = $request->periode_id ?? (Periode::getActive()->id ?? null);
        $viewTriwulan = $request->view_triwulan ?? 'all';

        // 1. Initial Filtering by Period and Security
        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        if (!in_array($user->role_id, [1, 2])) {
            $query->where('unit_id', $user->unit_id);
        } elseif ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // 2. SEARCH Filter (Need this BEFORE grouping to find matching codes/activities)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kegiatan', 'like', "%$search%")
                  ->orWhere('kode_risiko', 'like', "%$search%");
            });
        }

        // 3. MASTER LIST GROUPING LOGIC (One row per risk code)
        // We pick the IDs we want to show based on the Triwulan selection
        $targetVal = ($viewTriwulan == 's1' ? [1, 2] : ($viewTriwulan == 's2' ? [3, 4] : ($viewTriwulan == 'all' ? [1,2,3,4] : [(int)$viewTriwulan])));
        
        // Use a sub-query style search to get unique IDs per risk code matching criteria
        $ids = IdentifikasiRisiko::where('periode_id', $periodeId)
            ->when(!in_array($user->role_id, [1, 2]), function($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            })
            ->when($request->filled('unit_id'), function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            })
            ->get()
            ->groupBy('kode_risiko')
            ->map(function($group) use ($targetVal, $viewTriwulan) {
                $group = $group->sortByDesc('triwulan');
                if ($viewTriwulan == 'all') return $group->first()->id;
                
                $match = $group->first(fn($item) => in_array($item->triwulan, $targetVal));
                return $match ? $match->id : $group->first()->id;
            })
            ->values()->toArray();

        $query->whereIn('id', $ids);

        // 4. Peringkat/Warna Filter (Final check on the selected rows)
        if ($request->filled('peringkat')) {
            $peringkat = $request->peringkat;
            $query->whereHas('analisis', function($q) use ($peringkat) {
                $q->where('peringkat_risiko', $peringkat);
            });
        }

        return $query;
    }

    private function getCommonData(Request $request)
    {
        $periode = Periode::find($request->periode_id) ?? Periode::getActive();
        $unit = Unit::find($request->unit_id);
        $triwulanText = $this->getTriwulanText($request->view_triwulan ?? 'all');
        
        return compact('periode', 'unit', 'triwulanText');
    }

    private function getTriwulanText($val)
    {
        $map = ['all' => '', 's1' => 'Semester 1', 's2' => 'Semester 2', '1' => 'Triwulan 1', '2' => 'Triwulan 2', '3' => 'Triwulan 3', '4' => 'Triwulan 4'];
        return $map[$val] ?? '';
    }

    public function identifikasiRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        $units = Unit::all();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.identifikasi', array_merge(['data' => $data, 'units' => $units], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Identifikasi_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function analisisRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        $units = Unit::all();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.analisis', array_merge(['data' => $data, 'units' => $units], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Analisis_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function analisisKecukupanAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis', 'analisisKecukupan']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        $units = Unit::all();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.kecukupan', array_merge(['data' => $data, 'units' => $units], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Analisis_Kecukupan_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function evaluasiRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak', 'evaluasi.probabilitas', 'evaluasi.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        $units = Unit::all();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.evaluasi', array_merge(['data' => $data, 'units' => $units], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Evaluasi_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function daftarRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak', 'analisisKecukupan', 'evaluasi.probabilitas', 'evaluasi.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        $units = Unit::all();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.daftar', array_merge(['data' => $data, 'units' => $units], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Daftar_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function singleProfile($id)
    {
        $item = IdentifikasiRisiko::with([
            'unit', 'kategori', 'ruangLingkup', 
            'analisis.probabilitas', 'analisis.dampak', 
            'analisisKecukupan', 
            'evaluasi.probabilitas', 'evaluasi.dampak'
        ])->findOrFail($id);
        
        $type = request('type', 'profil');
        $filenameMap = [
            'identifikasi' => 'Identifikasi_Risiko_',
            'analisis' => 'Analisis_Risiko_',
            'kecukupan' => 'Mitigasi_Risiko_',
            'evaluasi' => 'Evaluasi_Risiko_',
            'daftar' => 'Profil_Lengkap_',
            'profil' => 'Profil_Risiko_'
        ];
        $prefix = $filenameMap[$type] ?? 'Profil_Risiko_';

        $units = Unit::all();
        $pdf = Pdf::loadView('pdf.profile', compact('item', 'type', 'units'))->setPaper('a4', 'portrait');
        return $pdf->download($prefix . $item->kode_risiko . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }
}
