<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiRisiko extends Model
{
    protected $table = 'tbl_evaluasi_risiko';

    protected $fillable = [
        'identifikasi_risiko_id',
        'frekuensi_kejadian',
        'status_kejadian',
        'uraian_kejadian',
        'probabilitas_residu_id',
        'dampak_residu_id',
        'skor_residu',
        'peringkat_residu',
        'penurunan_persen',
        'rekomendasi_tindak_lanjut',
    ];

    public function identifikasi()
    {
        return $this->belongsTo(IdentifikasiRisiko::class, 'identifikasi_risiko_id');
    }

    public function probabilitas()
    {
        return $this->belongsTo(Probabilitas::class, 'probabilitas_residu_id');
    }

    public function dampak()
    {
        return $this->belongsTo(Dampak::class, 'dampak_residu_id');
    }
}
