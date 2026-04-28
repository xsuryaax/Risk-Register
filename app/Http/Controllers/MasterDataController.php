<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;

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

    public function users()
    {
        $users = User::with(['role', 'unit'])->paginate(10)->onEachSide(1);
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
        
        if ($selectedRole == 1) { // Assuming 1 is Admin/Superadmin
            $allKeys = [];
            foreach ($menuStructure as $group) {
                foreach ($group['menus'] as $menu) {
                    $allKeys[] = $menu['key'];
                }
            }
            $currentAkses = $allKeys;
        } else {
            $currentAkses = \App\Models\HakAkses::where('role_id', $selectedRole)
                ->pluck('menu_key')
                ->toArray();
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
}
