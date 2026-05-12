<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'tbl_periode';
    protected $fillable = ['tahun', 'status', 'keterangan'];
    public $timestamps = true;

    protected static function booted()
    {
        static::saving(function ($periode) {
            // Jika periode ini diatur menjadi AKTIF (status = 1 atau true)
            if ($periode->status) {
                // Matikan status semua periode lainnya
                static::where('id', '!=', $periode->id)
                    ->where('status', true)
                    ->update(['status' => false]);
            }
        });
    }

    public static function getActive()
    {
        return self::where('status', true)->first();
    }
}
