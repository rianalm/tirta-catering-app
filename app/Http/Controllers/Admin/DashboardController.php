<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN IMPORT DB FACADE

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Kartu Statistik (tetap sama) ---
        $hariIni = Carbon::today();
        $besok = Carbon::tomorrow();
        $statusYangDikecualikan = ['selesai', 'dibatalkan'];
        $totalPesananHariIni_SemuaStatus = Pesanan::whereDate('tanggal_pengiriman', $hariIni)->count();
        $pesananHariIniRingkas_Aktif = Pesanan::whereDate('tanggal_pengiriman', $hariIni)->whereNotIn('status_pesanan', $statusYangDikecualikan)->orderBy('waktu_pengiriman', 'asc')->take(5)->get();
        $totalPesananBesok_SemuaStatus = Pesanan::whereDate('tanggal_pengiriman', $besok)->count();
        $pesananBesokRingkas_Aktif = Pesanan::whereDate('tanggal_pengiriman', $besok)->whereNotIn('status_pesanan', $statusYangDikecualikan)->orderBy('waktu_pengiriman', 'asc')->take(5)->get();
        $jumlahPesananSelesaiHariIni = Pesanan::whereDate('tanggal_pengiriman', $hariIni)->where('status_pesanan', 'selesai')->count();
        $pesananSelesaiHariIniRingkas = Pesanan::whereDate('tanggal_pengiriman', $hariIni)->where('status_pesanan', 'selesai')->orderBy('updated_at', 'desc')->take(5)->get();
        $totalProduk = Produk::count();


        // --- LOGIKA BARU UNTUK DATA GRAFIK ---
        $orders = Pesanan::select(DB::raw('DATE(tanggal_pengiriman) as date'), DB::raw('count(*) as count'))
            ->whereBetween('tanggal_pengiriman', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        
        $chartData = [];
        $chartLabels = [];
        // Inisialisasi data untuk 7 hari terakhir dengan nilai 0
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->translatedFormat('d M'); // Format tanggal (e.g., "16 Jun")
            $chartData[$date] = 0;
        }

        // Isi data dari database
        foreach ($orders as $order) {
            $chartData[$order->date] = $order->count;
        }
        
        // Ubah dari array asosiatif ke array numerik
        $chartData = array_values($chartData); 
        // --- AKHIR LOGIKA BARU ---

        return view('admin.dashboard', compact(
            'totalPesananHariIni_SemuaStatus', 'pesananHariIniRingkas_Aktif',
            'totalPesananBesok_SemuaStatus', 'pesananBesokRingkas_Aktif',
            'jumlahPesananSelesaiHariIni', 'pesananSelesaiHariIniRingkas',
            'totalProduk',
            'chartLabels', // Kirim data label ke view
            'chartData'    // Kirim data jumlah pesanan ke view
        ));
    }
}