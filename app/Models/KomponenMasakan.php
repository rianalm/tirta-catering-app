<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenMasakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_komponen',
        'satuan_dasar',
    ];

    // RELASI BARU: KomponenMasakan digunakan oleh banyak Produk
    // Menggunakan withPivot untuk mengambil kolom 'jumlah_per_porsi' dari tabel pivot
    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'produk_komponen_masakan', 'komponen_masakan_id', 'produk_id')
                    ->withPivot('jumlah_per_porsi') // Ini penting! Untuk mendapatkan jumlah per porsi
                    ->withTimestamps(); // Jika Anda ingin kolom created_at/updated_at di tabel pivot
    }
}