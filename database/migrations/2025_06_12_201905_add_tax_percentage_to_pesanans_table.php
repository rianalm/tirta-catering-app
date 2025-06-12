<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan persentase pajak setelah kolom nominal pajak
            $table->decimal('pajak_persen', 5, 2)->default(0)->after('pajak');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('pajak_persen');
        });
    }
};