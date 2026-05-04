<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisKecukupan extends Model
{
    use HasFactory;

    protected $table = 'tbl_analisis_kecukupan';

    protected $fillable = [
        'identifikasi_risiko_id',
        'uraian_rencana',
        'jadwal',
        'pj_tindak_lanjut',
    ];

    public function identifikasi()
    {
        return $this->belongsTo(IdentifikasiRisiko::class, 'identifikasi_risiko_id');
    }
}
