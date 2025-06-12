<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_pesanan',
        'tanggal_pengiriman',
        'waktu_pengiriman',
        'nama_pelanggan',
        'telepon_pelanggan',
        'alamat_pengiriman',
        'catatan_khusus',
        'jenis_penyajian',
        'status_pesanan',
        'total_harga',     
        
        // --- KOLOM INI TETAP ADA DI MODEL UNTUK FITUR INVOICE NANTI ---
        'ongkir',
        'biaya_lain',
        'pajak',
        'grand_total',
        'pajak_persen', 
       
    ];


    /**
     * Get the items for the order.
     */
    public function itemPesanans()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}