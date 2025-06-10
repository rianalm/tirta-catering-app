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
            // Tambahkan kolom baru setelah 'catatan_khusus'
            $table->string('jenis_penyajian', 50)->nullable()->after('catatan_khusus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn('jenis_penyajian');
        });
    }
};
