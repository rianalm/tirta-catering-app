<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk_komponen_masakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('komponen_masakan_id')->constrained('komponen_masakans')->onDelete('cascade');
            $table->unsignedInteger('jumlah_per_porsi'); // Berapa unit komponen ini per 1 porsi produk

            // Membuat kombinasi produk_id dan komponen_masakan_id menjadi unik
            $table->unique(['produk_id', 'komponen_masakan_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_komponen_masakan');
    }
};