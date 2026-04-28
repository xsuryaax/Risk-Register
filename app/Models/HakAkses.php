<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    protected $table = 'hak_akses';
    protected $fillable = ['role_id', 'menu_key'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
