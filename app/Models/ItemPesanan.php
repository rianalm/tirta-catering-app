<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah_porsi',
        'harga_satuan_saat_pesan',
        'subtotal_item',
    ];

    /**
     * Get the Pesanan that owns the ItemPesanan.
     * Hubungan: Satu ItemPesanan dimiliki oleh satu Pesanan.
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Get the Produk that owns the ItemPesanan.
     * Hubungan: Satu ItemPesanan terkait dengan satu Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}