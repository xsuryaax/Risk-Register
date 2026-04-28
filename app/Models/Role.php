<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'tbl_roles';

    protected $fillable = [
        'nama_role',
        'deskripsi_role',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
