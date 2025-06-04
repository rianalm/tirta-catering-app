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
            $table->integer('jumlah_per_porsi'); // Quantity of this component for one serving of the product
            $table->timestamps();

            // Make sure a product cannot have the same component multiple times
            $table->unique(['produk_id', 'komponen_masakan_id']);
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