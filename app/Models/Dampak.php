<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dampak extends Model
{
    protected $table = 'tbl_dampak';
    protected $fillable = [
        'nama_dampak',
        'nilai_dampak',
        'warna',
        'area_dampak',
        'cidera_pasien',
        'pelayanan_operasional',
        'biaya_keuangan',
        'publikasi',
        'reputasi',
    ];
}
