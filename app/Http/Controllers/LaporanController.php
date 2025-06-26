<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $targetDate = $request->input('tanggal', Carbon::tomorrow()->toDateString());

        $pesanans = Pesanan::with('itemPesanans.produk.komponenMasakans')
                           ->whereDate('tanggal_pengiriman', $targetDate)
                           ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                           ->get();
        
        // --- PERBAIKAN LOGIKA AGREGRASI ---
        
        // Gunakan array PHP biasa, bukan Collection
        $kebutuhanKomponen = []; 

        foreach ($pesanans as $pesanan) {
            foreach ($pesanan->itemPesanans as $item) {
                $jumlahPorsiItem = $item->jumlah_porsi;
                
                foreach ($item->produk->komponenMasakans as $komponen) {
                    $id = $komponen->id;
                    $jumlahDibutuhkan = $jumlahPorsiItem * $komponen->pivot->jumlah_per_porsi;

                    // Gunakan isset() untuk memeriksa array biasa
                    if (isset($kebutuhanKomponen[$id])) {
                        // Baris ini sekarang akan bekerja dengan benar pada array biasa
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
        
        // Urutkan array berdasarkan nama komponen
        uasort($kebutuhanKomponen, function ($a, $b) {
            return $a['nama_komponen'] <=> $b['nama_komponen'];
        });
        
        // --- AKHIR PERBAIKAN ---

        return view('admin.laporan.kebutuhan_dapur', compact('kebutuhanKomponen', 'targetDate'));
    }

    public function generateKebutuhanDapurPdf(Request $request)
    {
        $targetDate = $request->input('tanggal', Carbon::tomorrow()->toDateString());

        $pesanans = Pesanan::with('itemPesanans.produk.komponenMasakans')
                           ->whereDate('tanggal_pengiriman', $targetDate)
                           ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                           ->get();
        
        $kebutuhanKomponen = []; 

        foreach ($pesanans as $pesanan) {
            foreach ($pesanan->itemPesanans as $item) {
                $jumlahPorsiItem = $item->jumlah_porsi;
                
                foreach ($item->produk->komponenMasakans as $komponen) {
                    $id = $komponen->id;
                    $jumlahDibutuhkan = $jumlahPorsiItem * $komponen->pivot->jumlah_per_porsi;

                    if (isset($kebutuhanKomponen[$id])) {
                        $kebutuhanKomponen[$id]['total_kebutuhan'] += $jumlahDibutuhkan;
                    } else {
                        $kebutuhanKomponen[$id] = [
                            'nama_komponen'   => $komponen->nama_komponen,
                            'total_kebutuhan' => $jumlahDibutuhkan,
                            'satuan_dasar'    => $komponen->satuan_dasar,
                        ];
                    }
                }
            }
        }
        
        uasort($kebutuhanKomponen, function ($a, $b) {
            return $a['nama_komponen'] <=> $b['nama_komponen'];
        });

        // Render view khusus PDF dengan data yang sudah diolah
        $pdf = PDF::loadView('admin.laporan.pdf_kebutuhan_dapur', compact('kebutuhanKomponen', 'targetDate'));
        
        // Tampilkan PDF di browser
        return $pdf->stream('laporan-kebutuhan-dapur-'.$targetDate.'.pdf');
    }
}