<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangLingkup extends Model
{
    protected $table = 'tbl_ruang_lingkup';
    protected $fillable = ['nama_ruang_lingkup', 'keterangan', 'status_ruang_lingkup'];
}
