<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KomponenMasakanController;
use App\Http\Controllers\Admin\DashboardController; // TAMBAHKAN IMPORT INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard Route - DIMODIFIKASI
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

// Group routes for admin section
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('produks', ProdukController::class);
    Route::resource('pesanan', PesananController::class);
    Route::post('pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::resource('komponen-masakan', KomponenMasakanController::class);
});