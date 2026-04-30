<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Probabilitas extends Model
{
    protected $table = 'tbl_probabilitas';
    protected $fillable = ['nama_probabilitas', 'nilai_probabilitas', 'warna', 'keterangan', 'status_probabilitas'];
}
