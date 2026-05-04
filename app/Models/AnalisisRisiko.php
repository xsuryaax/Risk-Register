<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisRisiko extends Model
{
    protected $table = 'tbl_analisis_risiko';

    protected $fillable = [
        'identifikasi_risiko_id',
        'uraian_pengendalian',
        'desain_pengendalian',
        'efektifitas_pengendalian',
        'probabilitas_id',
        'dampak_id',
        'skor_risiko',
        'peringkat_risiko',
        'pemilik_risiko',
    ];

    public function identifikasi()
    {
        return $this->belongsTo(IdentifikasiRisiko::class, 'identifikasi_risiko_id');
    }

    public function probabilitas()
    {
        return $this->belongsTo(Probabilitas::class, 'probabilitas_id');
    }

    public function dampak()
    {
        return $this->belongsTo(Dampak::class, 'dampak_id');
    }
}
