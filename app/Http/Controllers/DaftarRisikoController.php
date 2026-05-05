<?php

namespace App\Http\Controllers;

use App\Models\IdentifikasiRisiko;
use Illuminate\Http\Request;

class DaftarRisikoController extends Controller
{
    public function index()
    {
        $risikos = IdentifikasiRisiko::with([
            'kategori', 
            'ruangLingkup', 
            'analisis.probabilitas', 
            'analisis.dampak', 
            'analisisKecukupan'
        ])->get();

        return view('pages.daftar-risiko.index', compact('risikos'));
    }
}
