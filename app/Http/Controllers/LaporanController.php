<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan; // Import model Pesanan
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan penjualan.
     * Memungkinkan filter berdasarkan rentang tanggal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function penjualan(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pesanan::where('status_pesanan', 'selesai'); // Hanya hitung pesanan yang "selesai"

        // Terapkan filter tanggal jika ada
        if ($startDate) {
            $query->whereDate('tanggal_pesanan', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_pesanan', '<=', $endDate);
        }

        // Ambil semua pesanan yang memenuhi kriteria
        $salesData = $query->get();

        // Hitung total penjualan
        $totalSales = $salesData->sum('total_harga');

        // Kembalikan view laporan dengan data yang sudah dihitung dan filter aktif
        return view('admin.laporan_penjualan', compact('totalSales', 'salesData', 'startDate', 'endDate'));
    }
}