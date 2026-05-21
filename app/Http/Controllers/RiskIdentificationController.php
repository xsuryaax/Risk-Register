<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use App\Models\KategoriRisiko;
use App\Models\RuangLingkup;
use App\Models\Unit;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiskIdentificationController extends Controller
{
    public function getLibrary(Request $request)
    {
        $user = Auth::user();
        $activePeriode = \App\Models\Periode::getActive();
        
        if (!$activePeriode) {
            return response()->json([]);
        }

        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'periode'])
            ->where('periode_id', '!=', $activePeriode->id);

        // Security: Non-Admin/Mutu can only see their own unit
        if (!in_array($user->role_id, [1, 2])) {
            $query->where('unit_id', $user->unit_id);
        }

        $data = $query->get();
        return response()->json($data);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $activePeriode = \App\Models\Periode::getActive();
        
        $viewTriwulan = $request->triwulan ?? 'all';
        $query = IdentifikasiRisiko::with(['unit', 'kategori', 'ruangLingkup']);

        if ($activePeriode) {
            $query->where('periode_id', $activePeriode->id);
            
            // Identification is annual master data, only show one row per risk
            // Group by kode_risiko and pick the latest entry
            $ids = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                ->get()
                ->groupBy('kode_risiko')
                ->map(fn($group) => $group->sortByDesc('triwulan')->first()->id)
                ->values()->toArray();

            $query->whereIn('id', $ids);
        } else {
            $query->whereRaw('1 = 0'); // No active period, no data
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

        $data = $query->orderBy('kode_risiko', 'asc')->paginate(10)->withQueryString();
        $units = Unit::all();
            
        if ($request->ajax()) {
            return view('pages.identifikasi-risiko._table', compact('data', 'activePeriode'));
        }
            
        return view('pages.identifikasi-risiko.index', compact('data', 'units', 'activePeriode'));
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
            'triwulan' => 'required|integer|min:1|max:4',
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
            'periode_id' => \App\Models\Periode::getActive()->id ?? null,
            'triwulan' => $request->triwulan,
            'frekuensi_pelaporan' => $request->frekuensi_pelaporan ?? 'triwulan',
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

        return redirect()->route('identifikasi-risiko.index', ['triwulan' => $request->triwulan])
            ->with('success', 'Identifikasi risiko berhasil disimpan.');
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
            'frekuensi_pelaporan' => 'required|in:tahunan,semester,triwulan',
        ]);

        $risk->update($request->except('unit_id'));

        return redirect()->route('identifikasi-risiko.index', ['triwulan' => $request->triwulan])
            ->with('success', 'Identifikasi risiko berhasil diperbarui.');
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

    public function copyFromLibrary(Request $request)
    {
        $original = IdentifikasiRisiko::findOrFail($request->risk_id);
        $activePeriode = \App\Models\Periode::getActive();

        if (!$activePeriode) {
            return response()->json(['success' => false, 'message' => 'Tidak ada periode aktif'], 400);
        }

        $year = $activePeriode->tahun;
        $kat = KategoriRisiko::find($original->kategori_risiko_id);
        $prefix = $kat ? substr($kat->nama_kategori, 0, 1) : 'R';

        // Optimized Code Generation
        $lastCode = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
            ->where('kode_risiko', 'like', "$prefix-$year-%")
            ->orderBy('kode_risiko', 'desc')
            ->value('kode_risiko');

        $nextNum = 1;
        if ($lastCode) {
            $parts = explode('-', $lastCode);
            $nextNum = (int)end($parts) + 1;
        }
        $newCode = sprintf("%s-%s-%03d", $prefix, $year, $nextNum);

        // Determine target triwulan (default to current calendar if not specified)
        $targetTW = $request->triwulan;
        if (!$targetTW || in_array($targetTW, ['all', 's1', 's2'])) {
            $targetTW = ceil(date('n') / 3);
        }

        try {
            return \DB::transaction(function () use ($original, $activePeriode, $newCode, $targetTW) {
                $newRisk = new IdentifikasiRisiko();
                $newRisk->unit_id = $original->unit_id;
                $newRisk->periode_id = $activePeriode->id;
                $newRisk->triwulan = $targetTW;
                $newRisk->kode_risiko = $newCode;
                $newRisk->kegiatan = $original->kegiatan;
                $newRisk->tujuan_kegiatan = $original->tujuan_kegiatan;
                $newRisk->kategori_risiko_id = $original->kategori_risiko_id;
                $newRisk->ruang_lingkup_id = $original->ruang_lingkup_id;
                $newRisk->pernyataan_risiko = $original->pernyataan_risiko;
                $newRisk->sebab = $original->sebab;
                $newRisk->jenis_risiko = $original->jenis_risiko;
                $newRisk->dampak = $original->dampak;
                $newRisk->user_id = \Auth::id() ?? $original->user_id;
                $newRisk->save();

                // 1. Copy Analisis
                if ($original->analisis) {
                    $newAnalisis = $original->analisis->replicate();
                    $newAnalisis->identifikasi_risiko_id = $newRisk->id;
                    $newAnalisis->save();
                }

                // 2. Copy Analisis Kecukupan (Mitigasi)
                if ($original->analisisKecukupan) {
                    $newKecukupan = $original->analisisKecukupan->replicate();
                    $newKecukupan->identifikasi_risiko_id = $newRisk->id;
                    $newKecukupan->save();
                }

                // 3. Evaluation is NOT copied (Stay fresh)

                return response()->json([
                    'success' => true,
                    'message' => 'Risiko beserta Analisis & Mitigasi berhasil ditarik ke periode ' . $activePeriode->tahun . ' (TW ' . $targetTW . ')',
                    'redirect' => route('identifikasi-risiko.index')
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menarik data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkCopy(Request $request)
    {
        $user = Auth::user();
        $activePeriode = Periode::getActive();
        if (!$activePeriode) {
            return response()->json(['success' => false, 'message' => 'Tidak ada periode aktif'], 400);
        }

        $ids = [];
        if ($request->boolean('select_all')) {
            // Apply original filters to get all matching IDs
            $query = IdentifikasiRisiko::query();
            
            if ($request->filled('view_periode_id')) {
                $query->where('periode_id', $request->view_periode_id);
            }
            if (!in_array($user->role_id, [1, 2])) {
                $query->where('unit_id', $user->unit_id);
            } elseif ($request->filled('unit_id')) {
                $query->where('unit_id', $request->unit_id);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kegiatan', 'like', "%$search%")
                      ->orWhere('kode_risiko', 'like', "%$search%");
                });
            }
            $ids = $query->pluck('id')->toArray();
        } else {
            $ids = $request->ids;
        }

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data terpilih'], 400);
        }

        // Determine target triwulan
        $targetTW = $request->triwulan;
        if (!$targetTW || in_array($targetTW, ['all', 's1', 's2'])) {
            $targetTW = ceil(date('n') / 3);
        }

        $year = $activePeriode->tahun;
        $successCount = 0;

        try {
            \DB::transaction(function () use ($ids, $activePeriode, $year, $targetTW, &$successCount) {
                foreach ($ids as $id) {
                    $original = IdentifikasiRisiko::find($id);
                    if (!$original) continue;

                    // Skip if already exists in this period AND this triwulan
                    $exists = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                        ->where('triwulan', $targetTW)
                        ->where('kegiatan', $original->kegiatan)
                        ->where('unit_id', $original->unit_id)
                        ->exists();
                    if ($exists) continue;

                    $kat = KategoriRisiko::find($original->kategori_risiko_id);
                    $prefix = $kat ? substr($kat->nama_kategori, 0, 1) : 'R';

                    $lastCode = IdentifikasiRisiko::where('periode_id', $activePeriode->id)
                        ->where('kode_risiko', 'like', "$prefix-$year-%")
                        ->orderBy('kode_risiko', 'desc')
                        ->value('kode_risiko');

                    $nextNum = 1;
                    if ($lastCode) {
                        $parts = explode('-', $lastCode);
                        $nextNum = (int)end($parts) + 1;
                    }
                    $newCode = sprintf("%s-%s-%03d", $prefix, $year, $nextNum);

                    $newRisk = new IdentifikasiRisiko();
                    $newRisk->unit_id = $original->unit_id;
                    $newRisk->periode_id = $activePeriode->id;
                    $newRisk->triwulan = $targetTW;
                    $newRisk->kode_risiko = $newCode;
                    $newRisk->kegiatan = $original->kegiatan;
                    $newRisk->tujuan_kegiatan = $original->tujuan_kegiatan;
                    $newRisk->kategori_risiko_id = $original->kategori_risiko_id;
                    $newRisk->ruang_lingkup_id = $original->ruang_lingkup_id;
                    $newRisk->pernyataan_risiko = $original->pernyataan_risiko;
                    $newRisk->sebab = $original->sebab;
                    $newRisk->jenis_risiko = $original->jenis_risiko;
                    $newRisk->dampak = $original->dampak;
                    $newRisk->user_id = \Auth::id() ?? $original->user_id;
                    $newRisk->save();

                    if ($original->analisis) {
                        $newAnalisis = $original->analisis->replicate();
                        $newAnalisis->identifikasi_risiko_id = $newRisk->id;
                        $newAnalisis->save();
                    }

                    if ($original->analisisKecukupan) {
                        $newKecukupan = $original->analisisKecukupan->replicate();
                        $newKecukupan->identifikasi_risiko_id = $newRisk->id;
                        $newKecukupan->save();
                    }

                    // Evaluation is NOT copied (Stay fresh)
                    
                    $successCount++;
                }
            });

            return response()->json([
                'success' => true,
                'message' => "$successCount risiko berhasil ditarik ke periode " . $activePeriode->tahun . " (TW $targetTW)"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal bulk pull: ' . $e->getMessage()], 500);
        }
    }
}
