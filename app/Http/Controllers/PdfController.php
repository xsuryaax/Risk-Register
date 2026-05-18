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

        // Target period
        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        // Security: Non-Admin/Mutu can only see their own unit
        if (!in_array($user->role_id, [1, 2])) {
            $query->where('unit_id', $user->unit_id);
        } elseif ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kegiatan', 'like', "%$search%")
                  ->orWhere('kode_risiko', 'like', "%$search%");
            });
        }

        // Peringkat Filter
        if ($request->filled('peringkat')) {
            $peringkat = $request->peringkat;
            $query->whereHas('analisis', function($q) use ($peringkat) {
                $q->where('peringkat_risiko', $peringkat);
            });
        }

        // Larik Filter (Triwulan) with Fallback Logic
        if ($request->filled('view_triwulan') && $request->view_triwulan != 'all') {
            $tri = $request->view_triwulan;
            $query->where(function($q) use ($tri) {
                // 1. Match specific triwulan
                if (in_array($tri, [1, 2, 3, 4])) {
                    $q->where('triwulan', $tri);
                    // 2. Include Tahunan
                    $q->orWhere('frekuensi_pelaporan', 'tahunan');
                    // 3. Include Semester
                    if ($tri <= 2) {
                        $q->orWhere(function($sq) {
                            $sq->where('frekuensi_pelaporan', 'semester')->whereIn('triwulan', [1, 2]);
                        });
                    } else {
                        $q->orWhere(function($sq) {
                            $sq->where('frekuensi_pelaporan', 'semester')->whereIn('triwulan', [3, 4]);
                        });
                    }
                } elseif ($tri == 's1') {
                    $q->whereIn('triwulan', [1, 2])
                      ->orWhere('frekuensi_pelaporan', 'tahunan');
                } elseif ($tri == 's2') {
                    $q->whereIn('triwulan', [3, 4])
                      ->orWhere('frekuensi_pelaporan', 'tahunan');
                }
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
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.identifikasi', array_merge(['data' => $data], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Identifikasi_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function analisisRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.analisis', array_merge(['data' => $data], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Analisis_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function analisisKecukupanAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis', 'analisisKecukupan']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.kecukupan', array_merge(['data' => $data], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Analisis_Kecukupan_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function evaluasiRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak', 'evaluasi.probabilitas', 'evaluasi.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.evaluasi', array_merge(['data' => $data], $common))->setPaper('a4', 'landscape');
        return $pdf->download('Evaluasi_Risiko_' . date('Ymd') . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }

    public function daftarRisikoAll(Request $request)
    {
        $query = IdentifikasiRisiko::with(['unit', 'analisis.probabilitas', 'analisis.dampak', 'analisisKecukupan', 'evaluasi.probabilitas', 'evaluasi.dampak']);
        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('id', 'asc')->get();
        
        $common = $this->getCommonData($request);
        $pdf = Pdf::loadView('pdf.daftar', array_merge(['data' => $data], $common))->setPaper('a4', 'landscape');
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

        $pdf = Pdf::loadView('pdf.profile', compact('item', 'type'))->setPaper('a4', 'portrait');
        return $pdf->download($prefix . $item->kode_risiko . '.pdf')
                   ->withCookie(cookie('pdf_download_complete', '1', 1, null, null, false, false));
    }
}
