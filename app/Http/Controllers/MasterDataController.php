<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\KategoriRisiko;
use App\Models\RuangLingkup;
use App\Models\Probabilitas;
use App\Models\Dampak;

class MasterDataController extends Controller
{
    public function roles()
    {
        $roles = Role::paginate(10)->onEachSide(1);
        $stats = [
            'total' => Role::count(),
            'aktif' => Role::where('status_role', 'aktif')->count(),
            'non_aktif' => Role::where('status_role', 'non-aktif')->count(),
        ];
        return view('pages.roles', compact('roles', 'stats'));
    }

    public function units()
    {
        $units = Unit::paginate(10)->onEachSide(1);
        
        // Generate Next Code Logic
        $lastUnit = Unit::orderBy('kode_unit', 'desc')->first();
        if ($lastUnit) {
            $lastNumber = (int) str_replace('UNIT', '', $lastUnit->kode_unit);
            $nextCode = 'UNIT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextCode = 'UNIT001';
        }

        $stats = [
            'total' => Unit::count(),
            'aktif' => Unit::where('status_unit', 'aktif')->count(),
            'non_aktif' => Unit::where('status_unit', 'non-aktif')->count(),
        ];
        return view('pages.units', compact('units', 'stats', 'nextCode'));
    }

    public function users(Request $request)
    {
        $query = User::with(['role', 'unit']);
        
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $users = $query->paginate(10)->onEachSide(1)->withQueryString();
        $roles = Role::where('status_role', 'aktif')->get();
        $units = Unit::where('status_unit', 'aktif')->get();
        $stats = [
            'total' => User::count(),
            'aktif' => User::where('status_user', 'aktif')->count(),
            'non_aktif' => User::where('status_user', 'non-aktif')->count(),
        ];
        return view('pages.users', compact('users', 'roles', 'units', 'stats'));
    }

    public function hakAkses(Request $request)
    {
        $selectedRole = $request->role_id ?? Role::first()->id;
        $menuStructure = config('menu');
        
        // Use the model's logic to check which menus are accessible by default/DB
        $mockUser = new User();
        $mockUser->role_id = $selectedRole;

        $currentAkses = [];
        foreach ($menuStructure as $group) {
            foreach ($group['menus'] as $menu) {
                if ($mockUser->hasAkses($menu['key'])) {
                    $currentAkses[] = $menu['key'];
                }
            }
        }

        $roles = Role::where('status_role', 'aktif')->get();
        $stats = [
            'total' => Role::count(),
            'aktif' => Role::where('status_role', 'aktif')->count(),
            'non_aktif' => Role::where('status_role', 'non-aktif')->count(),
        ];

        return view('pages.hak-akses', compact('roles', 'selectedRole', 'menuStructure', 'currentAkses', 'stats'));
    }

    public function updateHakAkses(Request $request, $roleId)
    {
        $menus = $request->menu_key ?? [];
        
        \App\Models\HakAkses::where('role_id', $roleId)->delete();
        
        foreach ($menus as $key) {
            \App\Models\HakAkses::create([
                'role_id' => $roleId,
                'menu_key' => $key
            ]);
        }

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui!');
    }

    // Kategori Risiko
    public function kategoriRisiko()
    {
        $data = KategoriRisiko::paginate(10)->onEachSide(1);
        $stats = [
            'total' => KategoriRisiko::count(),
            'aktif' => KategoriRisiko::where('status_kategori', 'aktif')->count(),
            'non_aktif' => KategoriRisiko::where('status_kategori', 'non-aktif')->count(),
        ];
        return view('pages.kategori-risiko', compact('data', 'stats'));
    }

    public function createKategoriRisiko()
    {
        return view('pages.master.kategori-form');
    }

    public function storeKategoriRisiko(Request $request)
    {
        KategoriRisiko::create($request->all());
        return redirect()->route('kategori-risiko.index')->with('success', 'Kategori Risiko berhasil ditambahkan!');
    }

    public function editKategoriRisiko($id)
    {
        $item = KategoriRisiko::findOrFail($id);
        return view('pages.master.kategori-form', compact('item'));
    }

    public function updateKategoriRisiko(Request $request, $id)
    {
        $item = KategoriRisiko::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('kategori-risiko.index')->with('success', 'Kategori Risiko berhasil diperbarui!');
    }

    public function destroyKategoriRisiko($id)
    {
        KategoriRisiko::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori Risiko berhasil dihapus!');
    }

    // Ruang Lingkup
    public function ruangLingkup()
    {
        $data = RuangLingkup::paginate(10)->onEachSide(1);
        $stats = [
            'total' => RuangLingkup::count(),
            'aktif' => RuangLingkup::where('status_ruang_lingkup', 'aktif')->count(),
            'non_aktif' => RuangLingkup::where('status_ruang_lingkup', 'non-aktif')->count(),
        ];
        return view('pages.ruang-lingkup', compact('data', 'stats'));
    }

    public function createRuangLingkup()
    {
        return view('pages.master.ruang-lingkup-form');
    }

    public function storeRuangLingkup(Request $request)
    {
        RuangLingkup::create($request->all());
        return redirect()->route('ruang-lingkup.index')->with('success', 'Ruang Lingkup berhasil ditambahkan!');
    }

    public function editRuangLingkup($id)
    {
        $item = RuangLingkup::findOrFail($id);
        return view('pages.master.ruang-lingkup-form', compact('item'));
    }

    public function updateRuangLingkup(Request $request, $id)
    {
        $item = RuangLingkup::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('ruang-lingkup.index')->with('success', 'Ruang Lingkup berhasil diperbarui!');
    }

    public function destroyRuangLingkup($id)
    {
        RuangLingkup::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Ruang Lingkup berhasil dihapus!');
    }

    // Probabilitas
    public function probabilitas()
    {
        $data = Probabilitas::orderBy('nilai_probabilitas', 'asc')->paginate(10)->onEachSide(1);
        $stats = [
            'total' => Probabilitas::count(),
            'aktif' => Probabilitas::where('status_probabilitas', 'aktif')->count(),
            'non_aktif' => Probabilitas::where('status_probabilitas', 'non-aktif')->count(),
        ];
        return view('pages.probabilitas', compact('data', 'stats'));
    }

    public function createProbabilitas()
    {
        return view('pages.master.probabilitas-form');
    }

    public function storeProbabilitas(Request $request)
    {
        Probabilitas::create($request->all());
        return redirect()->route('probabilitas.index')->with('success', 'Skala Probabilitas berhasil ditambahkan!');
    }

    public function editProbabilitas($id)
    {
        $item = Probabilitas::findOrFail($id);
        return view('pages.master.probabilitas-form', compact('item'));
    }

    public function updateProbabilitas(Request $request, $id)
    {
        $item = Probabilitas::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('probabilitas.index')->with('success', 'Skala Probabilitas berhasil diperbarui!');
    }

    public function destroyProbabilitas($id)
    {
        Probabilitas::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Skala Probabilitas berhasil dihapus!');
    }

    // Dampak
    public function dampak()
    {
        $data = Dampak::orderBy('nilai_dampak', 'asc')->paginate(10)->onEachSide(1);
        $stats = [
            'total' => Dampak::count(),
            'aktif' => Dampak::where('status_dampak', 'aktif')->count(),
            'non_aktif' => Dampak::where('status_dampak', 'non-aktif')->count(),
        ];
        return view('pages.dampak', compact('data', 'stats'));
    }

    public function createDampak()
    {
        return view('pages.master.dampak-form');
    }

    public function storeDampak(Request $request)
    {
        Dampak::create($request->all());
        return redirect()->route('dampak.index')->with('success', 'Skala Dampak berhasil ditambahkan!');
    }

    public function editDampak($id)
    {
        $item = Dampak::findOrFail($id);
        return view('pages.master.dampak-form', compact('item'));
    }

    public function updateDampak(Request $request, $id)
    {
        $item = Dampak::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('dampak.index')->with('success', 'Skala Dampak berhasil diperbarui!');
    }

    public function destroyDampak($id)
    {
        Dampak::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Skala Dampak berhasil dihapus!');
    }
}
