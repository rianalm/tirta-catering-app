<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// Import command Anda jika namespace berbeda atau untuk kejelasan
// use App\Console\Commands\UpdateOrderStatusToProcessing; 

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Biasanya command yang dibuat dengan make:command sudah otomatis terdeteksi.
        // Jika tidak, Anda bisa daftarkan di sini:
        // \App\Console\Commands\UpdateOrderStatusToProcessing::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Jadwalkan command kita untuk berjalan setiap hari pada jam 01:00
        // Ganti 'app:update-order-statuses' sesuai dengan $signature di command Anda
        $schedule->command('app:update-order-statuses')->dailyAt('01:00'); 
        
        // Opsi lain: ->daily(); // Berjalan setiap hari jam 00:00 server time
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}