<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dampak extends Model
{
    protected $table = 'tbl_dampak';
    protected $fillable = ['nama_dampak', 'nilai_dampak', 'keterangan', 'status_dampak'];
}
