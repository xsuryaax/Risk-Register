<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'tbl_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'nama_lengkap',
        'username',
        'nip',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'unit_id',
        'profesi',
        'atasan_langsung',
        'status_user',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Check if user has access to a specific menu key
     */
    public function hasAkses($menuKey)
    {
        // 1. Admin always has full access
        if ($this->role_id == 1) {
            return true;
        }

        // 2. Check Database for custom settings
        $hasCustom = \App\Models\HakAkses::where('role_id', $this->role_id)
            ->where('menu_key', $menuKey)
            ->exists();
        
        if ($hasCustom) {
            return true;
        }

        // 3. Default Logic if no DB record exists
        $menuConfig = config('menu');
        
        // Find which group this key belongs to
        $groupKey = null;
        foreach ($menuConfig as $gk => $group) {
            foreach ($group['menus'] as $menu) {
                if ($menu['key'] === $menuKey) {
                    $groupKey = $gk;
                    break 2;
                }
            }
        }

        if (!$groupKey) return false;

        // Mutu (ID 2): Everything except Pengaturan
        if ($this->role_id == 2) {
            return $groupKey !== 'pengaturan';
        }

        // Others: Only Menu Utama
        return $groupKey === 'menu_utama';
    }
}
