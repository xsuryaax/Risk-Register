<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');



    // Master Data
    Route::prefix('master')->group(function () {
        Route::get('/roles', [App\Http\Controllers\MasterDataController::class, 'roles'])->name('roles.index');
        Route::get('/units', [App\Http\Controllers\MasterDataController::class, 'units'])->name('units.index');
        Route::get('/users', [App\Http\Controllers\MasterDataController::class, 'users'])->name('users.index');
        Route::get('/hak-akses', [App\Http\Controllers\MasterDataController::class, 'hakAkses'])->name('hak-akses.index');
        Route::post('/hak-akses/{roleId}', [App\Http\Controllers\MasterDataController::class, 'updateHakAkses'])->name('hak-akses.update');
        
        // New Master Data Routes
        Route::get('/kategori-risiko', [App\Http\Controllers\MasterDataController::class, 'kategoriRisiko'])->name('kategori-risiko.index');
        Route::get('/kategori-risiko/create', [App\Http\Controllers\MasterDataController::class, 'createKategoriRisiko'])->name('kategori-risiko.create');
        Route::post('/kategori-risiko', [App\Http\Controllers\MasterDataController::class, 'storeKategoriRisiko'])->name('kategori-risiko.store');
        Route::get('/kategori-risiko/{id}/edit', [App\Http\Controllers\MasterDataController::class, 'editKategoriRisiko'])->name('kategori-risiko.edit');
        Route::put('/kategori-risiko/{id}', [App\Http\Controllers\MasterDataController::class, 'updateKategoriRisiko'])->name('kategori-risiko.update');
        Route::delete('/kategori-risiko/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyKategoriRisiko'])->name('kategori-risiko.destroy');
        
        Route::get('/ruang-lingkup', [App\Http\Controllers\MasterDataController::class, 'ruangLingkup'])->name('ruang-lingkup.index');
        Route::get('/ruang-lingkup/create', [App\Http\Controllers\MasterDataController::class, 'createRuangLingkup'])->name('ruang-lingkup.create');
        Route::post('/ruang-lingkup', [App\Http\Controllers\MasterDataController::class, 'storeRuangLingkup'])->name('ruang-lingkup.store');
        Route::get('/ruang-lingkup/{id}/edit', [App\Http\Controllers\MasterDataController::class, 'editRuangLingkup'])->name('ruang-lingkup.edit');
        Route::put('/ruang-lingkup/{id}', [App\Http\Controllers\MasterDataController::class, 'updateRuangLingkup'])->name('ruang-lingkup.update');
        Route::delete('/ruang-lingkup/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyRuangLingkup'])->name('ruang-lingkup.destroy');
        
        Route::get('/probabilitas', [App\Http\Controllers\MasterDataController::class, 'probabilitas'])->name('probabilitas.index');
        Route::get('/probabilitas/create', [App\Http\Controllers\MasterDataController::class, 'createProbabilitas'])->name('probabilitas.create');
        Route::post('/probabilitas', [App\Http\Controllers\MasterDataController::class, 'storeProbabilitas'])->name('probabilitas.store');
        Route::get('/probabilitas/{id}/edit', [App\Http\Controllers\MasterDataController::class, 'editProbabilitas'])->name('probabilitas.edit');
        Route::put('/probabilitas/{id}', [App\Http\Controllers\MasterDataController::class, 'updateProbabilitas'])->name('probabilitas.update');
        Route::delete('/probabilitas/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyProbabilitas'])->name('probabilitas.destroy');
        
        Route::get('/dampak', [App\Http\Controllers\MasterDataController::class, 'dampak'])->name('dampak.index');
        Route::get('/dampak/create', [App\Http\Controllers\MasterDataController::class, 'createDampak'])->name('dampak.create');
        Route::post('/dampak', [App\Http\Controllers\MasterDataController::class, 'storeDampak'])->name('dampak.store');
        Route::get('/dampak/{id}/edit', [App\Http\Controllers\MasterDataController::class, 'editDampak'])->name('dampak.edit');
        Route::put('/dampak/{id}', [App\Http\Controllers\MasterDataController::class, 'updateDampak'])->name('dampak.update');
        Route::delete('/dampak/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyDampak'])->name('dampak.destroy');

        // Periode Management
        Route::resource('periode', App\Http\Controllers\PeriodeController::class);
        Route::post('periode/{id}/activate', [App\Http\Controllers\PeriodeController::class, 'activate'])->name('periode.activate');
    });
    // Risk Identification
    Route::get('/identifikasi-risiko', [App\Http\Controllers\RiskIdentificationController::class, 'index'])->name('identifikasi-risiko.index');
    Route::get('/identifikasi-risiko/create', [App\Http\Controllers\RiskIdentificationController::class, 'create'])->name('identifikasi-risiko.create');
    Route::post('/identifikasi-risiko', [App\Http\Controllers\RiskIdentificationController::class, 'store'])->name('identifikasi-risiko.store');
    Route::get('/identifikasi-risiko/{id}/edit', [App\Http\Controllers\RiskIdentificationController::class, 'edit'])->name('identifikasi-risiko.edit');
    Route::put('/identifikasi-risiko/{id}', [App\Http\Controllers\RiskIdentificationController::class, 'update'])->name('identifikasi-risiko.update');
    Route::delete('/identifikasi-risiko/{id}', [App\Http\Controllers\RiskIdentificationController::class, 'destroy'])->name('identifikasi-risiko.destroy');
    Route::get('/identifikasi-risiko/library', [App\Http\Controllers\RiskIdentificationController::class, 'getLibrary'])->name('identifikasi-risiko.library');
    Route::post('/identifikasi-risiko/copy', [App\Http\Controllers\RiskIdentificationController::class, 'copyFromLibrary'])->name('identifikasi-risiko.copy');
    Route::post('/identifikasi-risiko/bulk-copy', [App\Http\Controllers\RiskIdentificationController::class, 'bulkCopy'])->name('identifikasi-risiko.bulk-copy');

    // Risk Analysis
    Route::get('/analisis-risiko', [App\Http\Controllers\RiskAnalysisController::class, 'index'])->name('analisis-risiko.index');
    Route::get('/analisis-risiko/{id}/edit', [App\Http\Controllers\RiskAnalysisController::class, 'edit'])->name('analisis-risiko.edit');
    Route::post('/analisis-risiko/{id}', [App\Http\Controllers\RiskAnalysisController::class, 'store'])->name('analisis-risiko.store');

    // Analisis Kecukupan
    Route::get('/analisis-kecukupan', [App\Http\Controllers\AnalisisKecukupanController::class, 'index'])->name('analisis-kecukupan.index');
    Route::get('/analisis-kecukupan/{id}/edit', [App\Http\Controllers\AnalisisKecukupanController::class, 'edit'])->name('analisis-kecukupan.edit');
    Route::post('/analisis-kecukupan/{id}', [App\Http\Controllers\AnalisisKecukupanController::class, 'update'])->name('analisis-kecukupan.update');
    // Daftar Lengkap
    Route::get('/daftar-risiko', [App\Http\Controllers\DaftarRisikoController::class, 'index'])->name('daftar-risiko.index');

    // Evaluasi Resiko (Residual Risk)
    Route::get('/evaluasi-risiko', [App\Http\Controllers\EvaluasiRisikoController::class, 'index'])->name('evaluasi-risiko.index');
    Route::get('/evaluasi-risiko/{id}/edit', [App\Http\Controllers\EvaluasiRisikoController::class, 'edit'])->name('evaluasi-risiko.edit');
    Route::post('/evaluasi-risiko/{id}', [App\Http\Controllers\EvaluasiRisikoController::class, 'store'])->name('evaluasi-risiko.store');

    // PDF Routes
    Route::prefix('pdf')->group(function () {
        Route::get('/identifikasi-risiko', [App\Http\Controllers\PdfController::class, 'identifikasiRisikoAll'])->name('pdf.identifikasi-risiko.all');
        Route::get('/analisis-risiko', [App\Http\Controllers\PdfController::class, 'analisisRisikoAll'])->name('pdf.analisis-risiko.all');
        Route::get('/analisis-kecukupan', [App\Http\Controllers\PdfController::class, 'analisisKecukupanAll'])->name('pdf.analisis-kecukupan.all');
        Route::get('/evaluasi-risiko', [App\Http\Controllers\PdfController::class, 'evaluasiRisikoAll'])->name('pdf.evaluasi-risiko.all');
        Route::get('/daftar-risiko', [App\Http\Controllers\PdfController::class, 'daftarRisikoAll'])->name('pdf.daftar-risiko.all');
        
        // Single Risk Profile
        Route::get('/profile/{id}', [App\Http\Controllers\PdfController::class, 'singleProfile'])->name('pdf.profile');
    });
});

Route::get('/fix-data', function() {
    App\Models\EvaluasiRisiko::all()->each(function($e){
        $i = App\Models\IdentifikasiRisiko::with('analisis')->find($e->identifikasi_risiko_id);
        if($i && $i->analisis) {
            $initial = $i->analisis->skor_risiko;
            $residu = $e->skor_residu;
            $val = $initial > 0 ? (($initial - $residu)/$initial)*100 : 0;
            $e->penurunan_persen = $val;
            $e->save();
        }
    });
    return "Data berhasil diperbaiki! Silakan kembali ke halaman sebelumnya dan refresh.";
});
