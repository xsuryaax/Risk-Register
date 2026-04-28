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
    });
});





