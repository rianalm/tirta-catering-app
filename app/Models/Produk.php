<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_jual',
        'satuan',
    ];

    // Relasi ke ItemPesanan (jika sudah ada, biarkan)
    // public function itemPesanans()
    // {
    //     return $this->hasMany(ItemPesanan::class, 'produk_id');
    // }

    public function komponenMasakans()
    {
        return $this->belongsToMany(KomponenMasakan::class, 'produk_komponen_masakan', 'produk_id', 'komponen_masakan_id')
                    ->withPivot('jumlah_per_porsi')
                    ->withTimestamps();
    }
}