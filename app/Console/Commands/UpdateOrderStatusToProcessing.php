<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pesanan; // Import model Pesanan
use Carbon\Carbon;      // Import Carbon untuk manipulasi tanggal

class UpdateOrderStatusToProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Nama command yang akan kita panggil, misalnya: app:update-order-statuses
    protected $signature = 'app:update-order-statuses'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status pesanan menjadi "diproses" jika tanggal pengiriman adalah hari ini dan status masih "pending".';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai proses update status pesanan...');

        $today = Carbon::today(); // Dapatkan tanggal hari ini (tanpa jam, menit, detik)
                                  // Carbon::today() akan menggunakan timezone default aplikasi Anda (cek di config/app.php)

        // Cari pesanan yang:
        // 1. Tanggal pengirimannya adalah hari ini
        // 2. Statusnya masih 'pending' (atau status awal lain yang relevan)
        $pesanansToUpdate = Pesanan::whereDate('tanggal_pengiriman', $today)
                                   ->where('status_pesanan', 'pending') // Atau ['pending', 'menunggu pembayaran'], dll.
                                   ->get();

        if ($pesanansToUpdate->isEmpty()) {
            $this->info('Tidak ada pesanan yang perlu diupdate statusnya menjadi "diproses" untuk hari ini.');
            return Command::SUCCESS;
        }

        $updatedCount = 0;
        foreach ($pesanansToUpdate as $pesanan) {
            $pesanan->status_pesanan = 'diproses';
            $pesanan->save();
            $updatedCount++;
            $this->info("Pesanan ID: {$pesanan->id} telah diupdate statusnya menjadi 'diproses'.");
        }

        $this->info("Proses selesai. Sebanyak {$updatedCount} pesanan telah diupdate statusnya.");
        return Command::SUCCESS;
    }
}