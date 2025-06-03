<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ini akan dijalankan saat `php artisan migrate`
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Mengubah tipe kolom 'waktu_pengiriman' dari TIME menjadi STRING (VARCHAR)
            // Dengan panjang 50 karakter dan tetap boleh null
            $table->string('waktu_pengiriman', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * Ini akan dijalankan saat `php artisan migrate:rollback`
     * Untuk mengembalikan perubahan.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Mengembalikan tipe kolom 'waktu_pengiriman' dari STRING menjadi TIME
            // Ini mungkin akan membuang data yang tidak valid untuk tipe TIME
            $table->time('waktu_pengiriman')->nullable()->change();
        });
    }
};