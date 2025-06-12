<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KomponenMasakanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController; // <-- Pastikan ini ditambahkan

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Route otentikasi yang dibuat oleh Breeze
require __DIR__.'/auth.php';


// === GROUP UNTUK SEMUA ROUTE ADMIN ===
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // --- Route yang bisa diakses oleh SEMUA peran terdaftar ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route untuk daftar pesanan operasional
    Route::get('/pesanan-operasional', [PesananController::class, 'operasionalIndex'])
        ->name('pesanan.operasional')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    // Aksi update status
    Route::post('/pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])
        ->name('pesanan.updateStatus')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    Route::get('/pesanan-operasional', [PesananController::class, 'operasionalIndex'])
        ->name('pesanan.operasional')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    // ROUTE BARU UNTUK DETAIL OPERASIONAL
    Route::get('/pesanan-operasional/{pesanan}', [PesananController::class, 'operasionalShow'])
        ->name('pesanan.operasional.show')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    Route::get('/pesanan-operasional/{pesanan}/pdf', [PesananController::class, 'generateWorksheetPdf'])
        ->name('pesanan.operasional.pdf')
        ->middleware(['role:admin|tim_dapur|tim_packing|driver']);

    
    // --- GROUP UNTUK ROUTE YANG HANYA BISA DIAKSES OLEH ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
    
        // Daftar semua resource controller untuk admin
        Route::resource('pesanan', PesananController::class); 
        Route::resource('produks', ProdukController::class);
        Route::resource('komponen-masakan', KomponenMasakanController::class);
        Route::resource('users', UserController::class); // <-- Route untuk Kelola User
        Route::resource('users', UserController::class);

        // Route untuk menampilkan halaman form edit invoice
        Route::get('pesanan/{pesanan}/invoice', [PesananController::class, 'editInvoice'])->name('pesanan.invoice.edit');
        // Route untuk menyimpan data dari form edit invoice
        Route::put('pesanan/{pesanan}/invoice', [PesananController::class, 'updateInvoice'])->name('pesanan.invoice.update');
        // ROUTE BARU UNTUK GENERATE PDF INVOICE
        Route::get('pesanan/{pesanan}/invoice/pdf', [PesananController::class, 'generateInvoicePdf'])->name('pesanan.invoice.pdf');
        
        // Route tunggal untuk laporan
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        
    });
    // --- AKHIR GROUP KHUSUS ADMIN ---

});