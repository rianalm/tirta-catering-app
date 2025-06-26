<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KomponenMasakanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk halaman utama, akan mengarahkan ke login atau admin
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Route otentikasi (login, logout, dll.) yang dibuat oleh Breeze
require __DIR__.'/auth.php';


// === GROUP UNTUK SEMUA ROUTE ADMIN & OPERASIONAL ===
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // --- Route yang bisa diakses oleh SEMUA peran terdaftar ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- Route untuk TIM OPERASIONAL (dan Admin) ---
    Route::get('/pesanan-operasional', [PesananController::class, 'operasionalIndex'])
        ->name('pesanan.operasional')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    Route::get('/pesanan-operasional/{pesanan}', [PesananController::class, 'operasionalShow'])
        ->name('pesanan.operasional.show')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    Route::get('/pesanan-operasional/{pesanan}/pdf', [PesananController::class, 'generateWorksheetPdf'])
        ->name('pesanan.operasional.pdf')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);
    
    Route::get('laporan/kebutuhan-dapur', [LaporanController::class, 'kebutuhanDapur'])
        ->name('laporan.dapur')
        ->middleware(['role:admin|tim_dapur|tim_packing']);

    Route::get('laporan/kebutuhan-dapur/pdf', [LaporanController::class, 'generateKebutuhanDapurPdf'])
        ->name('laporan.dapur.pdf')
        ->middleware(['role:admin|tim_dapur|tim_packing']);

    Route::post('/pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])
        ->name('pesanan.updateStatus')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    
    // --- GROUP UNTUK ROUTE YANG HANYA BISA DIAKSES OLEH ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
        
        // Resource Controllers untuk fitur-fitur utama Admin
        Route::resource('pesanan', PesananController::class); 
        Route::resource('produks', ProdukController::class);
        Route::resource('komponen-masakan', KomponenMasakanController::class);
        Route::resource('users', UserController::class);

        // Laporan Penjualan khusus Admin
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        
        // Route untuk Invoice khusus Admin
        Route::get('pesanan/{pesanan}/invoice', [PesananController::class, 'editInvoice'])->name('pesanan.invoice.edit');
        Route::put('pesanan/{pesanan}/invoice', [PesananController::class, 'updateInvoice'])->name('pesanan.invoice.update');
        Route::get('pesanan/{pesanan}/invoice/pdf', [PesananController::class, 'generateInvoicePdf'])->name('pesanan.invoice.pdf');
        
    });
});