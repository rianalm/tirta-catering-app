<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
    public function kebutuhanDapur(Request $request)
    {
        // Secara default, laporan menampilkan kebutuhan untuk HARI ESOK.
        $targetDate = $request->input('tanggal', Carbon::tomorrow()->toDateString());

        // 1. Ambil semua pesanan untuk tanggal target yang statusnya aktif
        $pesanans = Pesanan::with('itemPesanans.produk.komponenMasakans')
                           ->whereDate('tanggal_pengiriman', $targetDate)
                           ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                           ->get();
        
        // 2. Agregasi (jumlahkan) semua kebutuhan komponen
        $kebutuhanKomponen = new Collection();

        foreach ($pesanans as $pesanan) {
            foreach ($pesanan->itemPesanans as $item) {
                // Jumlah porsi untuk item ini
                $jumlahPorsiItem = $item->jumlah_porsi;
                
                // Loop melalui setiap komponen yang dibutuhkan oleh produk ini
                foreach ($item->produk->komponenMasakans as $komponen) {
                    $id = $komponen->id;
                    $jumlahDibutuhkan = $jumlahPorsiItem * $komponen->pivot->jumlah_per_porsi;

                    if ($kebutuhanKomponen->has($id)) {
                        // Jika komponen sudah ada di daftar, tambahkan jumlahnya
                        $kebutuhanKomponen[$id]['total_kebutuhan'] += $jumlahDibutuhkan;
                    } else {
                        // Jika belum ada, buat entri baru
                        $kebutuhanKomponen[$id] = [
                            'nama_komponen'   => $komponen->nama_komponen,
                            'total_kebutuhan' => $jumlahDibutuhkan,
                            'satuan_dasar'    => $komponen->satuan_dasar,
                        ];
                    }
                }
            }
        }
        
        // Urutkan berdasarkan nama komponen
        $kebutuhanKomponen = $kebutuhanKomponen->sortBy('nama_komponen');

        return view('admin.laporan.kebutuhan_dapur', compact('kebutuhanKomponen', 'targetDate'));
    }
}