<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Pastikan ini ada dan benar
    protected $table = 'pesanans';

    // ... (sisanya sama seperti sebelumnya)
    protected $fillable = [
        'tanggal_pesanan',
        'tanggal_pengiriman',
        'waktu_pengiriman',
        'nama_pelanggan',
        'telepon_pelanggan',
        'alamat_pengiriman',
        'catatan_khusus',
        'status_pesanan',
        'total_harga',
    ];

    // Definisi relasi
    public function itemPesanans()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}