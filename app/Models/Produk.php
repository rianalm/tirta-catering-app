<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Tentukan kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_jual',
        'is_active',
    ];

    /**
     * Get the itemPesanans that use this produk.
     * Satu Produk bisa ada di banyak ItemPesanan.
     */
    public function itemPesanans()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}