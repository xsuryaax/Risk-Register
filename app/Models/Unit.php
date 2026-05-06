<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'tbl_units';

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'deskripsi_unit',
        'status_unit',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function identifikasi()
    {
        return $this->hasMany(IdentifikasiRisiko::class, 'unit_id');
    }
}
