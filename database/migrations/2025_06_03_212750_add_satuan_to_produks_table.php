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
        Schema::table('produks', function (Blueprint $table) {
            // Tambahkan kolom 'satuan'
            $table->string('satuan', 50)->nullable()->after('harga_jual'); // Sesuaikan setelah kolom mana
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Hapus kolom 'satuan' jika rollback
            $table->dropColumn('satuan');
        });
    }
};