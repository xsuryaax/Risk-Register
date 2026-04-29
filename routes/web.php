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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/tables', function () {
        return view('tables');
    })->name('tables');

    Route::get('/billing', function () {
        return view('billing');
    })->name('billing');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Master Data
    Route::prefix('master')->group(function () {
        Route::get('/roles', [App\Http\Controllers\MasterDataController::class, 'roles'])->name('roles.index');
        Route::get('/units', [App\Http\Controllers\MasterDataController::class, 'units'])->name('units.index');
        Route::get('/users', [App\Http\Controllers\MasterDataController::class, 'users'])->name('users.index');
        Route::get('/hak-akses', [App\Http\Controllers\MasterDataController::class, 'hakAkses'])->name('hak-akses.index');
        Route::post('/hak-akses/{roleId}', [App\Http\Controllers\MasterDataController::class, 'updateHakAkses'])->name('hak-akses.update');
        
        // New Master Data Routes
        Route::get('/kategori-risiko', [App\Http\Controllers\MasterDataController::class, 'kategoriRisiko'])->name('kategori-risiko.index');
        Route::post('/kategori-risiko', [App\Http\Controllers\MasterDataController::class, 'storeKategoriRisiko'])->name('kategori-risiko.store');
        Route::put('/kategori-risiko/{id}', [App\Http\Controllers\MasterDataController::class, 'updateKategoriRisiko'])->name('kategori-risiko.update');
        Route::delete('/kategori-risiko/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyKategoriRisiko'])->name('kategori-risiko.destroy');
        
        Route::get('/ruang-lingkup', [App\Http\Controllers\MasterDataController::class, 'ruangLingkup'])->name('ruang-lingkup.index');
        Route::post('/ruang-lingkup', [App\Http\Controllers\MasterDataController::class, 'storeRuangLingkup'])->name('ruang-lingkup.store');
        Route::put('/ruang-lingkup/{id}', [App\Http\Controllers\MasterDataController::class, 'updateRuangLingkup'])->name('ruang-lingkup.update');
        Route::delete('/ruang-lingkup/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyRuangLingkup'])->name('ruang-lingkup.destroy');
        
        Route::get('/probabilitas', [App\Http\Controllers\MasterDataController::class, 'probabilitas'])->name('probabilitas.index');
        Route::post('/probabilitas', [App\Http\Controllers\MasterDataController::class, 'storeProbabilitas'])->name('probabilitas.store');
        Route::put('/probabilitas/{id}', [App\Http\Controllers\MasterDataController::class, 'updateProbabilitas'])->name('probabilitas.update');
        Route::delete('/probabilitas/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyProbabilitas'])->name('probabilitas.destroy');
        
        Route::get('/dampak', [App\Http\Controllers\MasterDataController::class, 'dampak'])->name('dampak.index');
        Route::post('/dampak', [App\Http\Controllers\MasterDataController::class, 'storeDampak'])->name('dampak.store');
        Route::put('/dampak/{id}', [App\Http\Controllers\MasterDataController::class, 'updateDampak'])->name('dampak.update');
        Route::delete('/dampak/{id}', [App\Http\Controllers\MasterDataController::class, 'destroyDampak'])->name('dampak.destroy');
    });
    // Risk Identification
    Route::get('/identifikasi-risiko', [App\Http\Controllers\RiskIdentificationController::class, 'index'])->name('identifikasi-risiko.index');
    Route::get('/identifikasi-risiko/create', [App\Http\Controllers\RiskIdentificationController::class, 'create'])->name('identifikasi-risiko.create');
    Route::post('/identifikasi-risiko', [App\Http\Controllers\RiskIdentificationController::class, 'store'])->name('identifikasi-risiko.store');
    Route::get('/identifikasi-risiko/{id}/edit', [App\Http\Controllers\RiskIdentificationController::class, 'edit'])->name('identifikasi-risiko.edit');
    Route::put('/identifikasi-risiko/{id}', [App\Http\Controllers\RiskIdentificationController::class, 'update'])->name('identifikasi-risiko.update');
    Route::delete('/identifikasi-risiko/{id}', [App\Http\Controllers\RiskIdentificationController::class, 'destroy'])->name('identifikasi-risiko.destroy');
});





