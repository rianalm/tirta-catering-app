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
        Schema::create('komponen_masakans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen')->unique(); // Nama komponen masakan, harus unik
            $table->string('satuan_dasar')->nullable(); // Contoh: 'potong', 'porsi', 'pcs', 'buah'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_masakans');
    }
};