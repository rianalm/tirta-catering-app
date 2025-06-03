<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); // Ini akan membuat id_pesanan (primary key, auto-increment)
            $table->date('tanggal_pesanan');
            $table->date('tanggal_pengiriman');
            $table->time('waktu_pengiriman')->nullable(); // nullable berarti boleh kosong
            $table->string('nama_pelanggan');
            $table->string('telepon_pelanggan');
            $table->string('alamat_pengiriman');
            $table->text('catatan_khusus')->nullable();
            $table->string('status_pesanan')->default('Baru'); // Default status 'Baru'
            $table->decimal('total_harga', 10, 2); // 10 digit total, 2 di belakang koma
            $table->timestamps(); // Ini akan membuat kolom created_at dan updated_at secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesanans');
    }
};
