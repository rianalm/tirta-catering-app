<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        $besok = Carbon::tomorrow();
        $statusYangDikecualikanUntukRingkasanAktif = ['selesai', 'dibatalkan'];

        // 1. Data untuk Pesanan Aktif Hari Ini
        $totalPesananHariIni_SemuaStatus = Pesanan::whereDate('tanggal_pengiriman', $hariIni)
                                                 ->count();
        $pesananHariIniRingkas_Aktif = Pesanan::whereDate('tanggal_pengiriman', $hariIni)
                                          ->whereNotIn('status_pesanan', $statusYangDikecualikanUntukRingkasanAktif)
                                          ->orderBy('waktu_pengiriman', 'asc')
                                          ->take(10)
                                          ->get();
        
        // 2. Data untuk Pesanan Aktif Hari Esok
        $totalPesananBesok_SemuaStatus = Pesanan::whereDate('tanggal_pengiriman', $besok)
                                               ->count();
        $pesananBesokRingkas_Aktif = Pesanan::whereDate('tanggal_pengiriman', $besok)
                                        ->whereNotIn('status_pesanan', $statusYangDikecualikanUntukRingkasanAktif)
                                        ->orderBy('waktu_pengiriman', 'asc')
                                        ->take(10)
                                        ->get();

        // 3. Data untuk Pesanan Selesai Hari Ini (BARU)
        //    Asumsi: "Selesai Hari Ini" berarti pesanan yang TANGGAL PENGIRIMANNYA hari ini dan statusnya SUDAH 'selesai'.
        $jumlahPesananSelesaiHariIni = Pesanan::whereDate('tanggal_pengiriman', $hariIni)
                                            ->where('status_pesanan', 'selesai')
                                            ->count();
        $pesananSelesaiHariIniRingkas = Pesanan::whereDate('tanggal_pengiriman', $hariIni)
                                             ->where('status_pesanan', 'selesai')
                                             ->orderBy('updated_at', 'desc') // Urutkan berdasarkan kapan terakhir diupdate (kapan diselesaikan)
                                             ->take(5) // Ambil 5 pesanan selesai teratas untuk ringkasan
                                             ->get();

        // 4. Total produk yang terdaftar
        $totalProduk = Produk::count();

        return view('admin.dashboard', compact(
            'totalPesananHariIni_SemuaStatus',
            'pesananHariIniRingkas_Aktif',
            'totalPesananBesok_SemuaStatus',
            'pesananBesokRingkas_Aktif',
            'jumlahPesananSelesaiHariIni',   // Kirim data baru ke view
            'pesananSelesaiHariIniRingkas', // Kirim data baru ke view
            'totalProduk'
        ));
    }
}