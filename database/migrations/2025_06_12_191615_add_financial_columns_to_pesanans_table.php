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
        Schema::table('pesanans', function (Blueprint $table) {
            // Kita tambahkan kolom-kolom baru setelah kolom 'total_harga'
            // Menggunakan tipe data decimal untuk nilai uang, dengan default 0
            
            $table->decimal('ongkir', 10, 2)->default(0)->after('total_harga');
            $table->decimal('biaya_lain', 10, 2)->default(0)->after('ongkir');
            $table->decimal('pajak', 10, 2)->default(0)->after('biaya_lain');
            $table->decimal('grand_total', 10, 2)->default(0)->after('pajak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn(['ongkir', 'biaya_lain', 'pajak', 'grand_total']);
        });
    }
};