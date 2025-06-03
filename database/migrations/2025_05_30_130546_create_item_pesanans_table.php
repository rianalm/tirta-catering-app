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
        Schema::create('item_pesanans', function (Blueprint $table) {
            $table->id(); // id_item_pesanan (primary key, auto-increment)

            // Foreign key untuk menghubungkan ke tabel 'pesanans'
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            // 'pesanan_id' akan otomatis menjadi kolom integer unsigned
            // 'constrained('pesanans')' akan membuat foreign key ke tabel 'pesanans'
            // 'onDelete('cascade')' berarti jika pesanan dihapus, item pesanan ini juga ikut terhapus

            // Foreign key untuk menghubungkan ke tabel 'produks'
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            // 'produk_id' akan otomatis menjadi kolom integer unsigned
            // 'constrained('produks')' akan membuat foreign key ke tabel 'produks'

            $table->integer('jumlah_porsi');
            $table->decimal('harga_satuan_saat_pesan', 10, 2); // Harga produk saat pesanan dibuat
            $table->decimal('subtotal_item', 10, 2); // jumlah_porsi * harga_satuan_saat_pesan

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_pesanans');
    }
};
