<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriRisiko extends Model
{
    protected $table = 'tbl_kategori_risiko';
    protected $fillable = ['nama_kategori', 'keterangan', 'status_kategori'];
}
