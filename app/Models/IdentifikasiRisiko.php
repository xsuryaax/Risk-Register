<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentifikasiRisiko extends Model
{
    protected $table = 'tbl_identifikasi_risiko';

    protected $fillable = [
        'unit_id',
        'kegiatan',
        'tujuan_kegiatan',
        'kode_risiko',
        'kategori_risiko_id',
        'ruang_lingkup_id',
        'pernyataan_risiko',
        'sebab',
        'jenis_risiko',
        'dampak',
        'user_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriRisiko::class, 'kategori_risiko_id');
    }

    public function ruangLingkup()
    {
        return $this->belongsTo(RuangLingkup::class, 'ruang_lingkup_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function analisis()
    {
        return $this->hasOne(AnalisisRisiko::class, 'identifikasi_risiko_id');
    }

    public function analisisKecukupan()
    {
        return $this->hasOne(AnalisisKecukupan::class, 'identifikasi_risiko_id');
    }
}
