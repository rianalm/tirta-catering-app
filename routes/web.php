<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard Route
// Pastikan ini sudah didefinisikan dan namanya 'admin.dashboard'
Route::get('/admin', function () {
    return view('admin.dashboard'); // Ini akan mencari resources/views/admin/dashboard.blade.php
})->name('admin.dashboard');

// Group routes for admin section
Route::prefix('admin')->name('admin.')->group(function () {
    // Produk routes
    Route::resource('produks', ProdukController::class);

    // Pesanan routes
    Route::resource('pesanan', PesananController::class);

    // Route khusus untuk update status pesanan (menggunakan AJAX)
    Route::post('pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

    // Laporan routes
    Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
});