<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;

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

        // Mulai query dengan eager loading item pesanan dan produk terkait
        $query = Pesanan::with('itemPesanans.produk')
                        ->where('status_pesanan', 'selesai'); // Hanya hitung pesanan yang "selesai"

        // Terapkan filter tanggal jika ada
        if ($startDate) {
            $query->whereDate('tanggal_pesanan', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('tanggal_pesanan', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // Urutkan pesanan berdasarkan tanggal untuk tampilan yang lebih rapi
        $query->orderBy('tanggal_pesanan', 'asc');

        // Ambil semua pesanan yang memenuhi kriteria
        $salesData = $query->get();

        // Hitung total penjualan dari salesData yang sudah difilter
        $totalSales = $salesData->sum('total_harga');

        // Kembalikan view laporan dengan data yang sudah dihitung dan filter aktif
        return view('admin.laporan_penjualan', compact('totalSales', 'salesData', 'startDate', 'endDate'));
    }
}