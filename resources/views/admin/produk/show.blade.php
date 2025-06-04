{{-- resources/views/admin/produk/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@push('styles')
<style>
    /* Gaya CSS spesifik untuk halaman ini jika ada */
    .container-content { max-width: 700px; margin: 0 auto; }
    .content-header h1 { text-align: center; }
    .detail-group {
        margin-bottom: 15px; padding: 10px 0;
        border-bottom: 1px dashed #e0e0e0;
    }
    .detail-group:last-of-type { border-bottom: none; } /* Menggunakan last-of-type untuk elemen terakhir dengan class ini */
    .detail-group label {
        font-weight: 600; color: #495057; display: block; margin-bottom: 5px;
    }
    .detail-group p, .detail-group ul { margin: 0; color: #555; line-height: 1.6; }
    .komponen-list { list-style: disc; padding-left: 20px; margin-top: 5px;}
    .komponen-list li {}
    .actions-bottom { margin-top: 30px; text-align: center; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Detail Produk: {{ $produk->nama_produk }}</h1>
        </div>

        <div class="detail-group">
            <label>ID Produk:</label>
            <p>{{ $produk->id }}</p>
        </div>
        <div class="detail-group">
            <label>Nama Produk:</label>
            <p>{{ $produk->nama_produk }}</p>
        </div>
        <div class="detail-group">
            <label>Deskripsi Produk:</label>
            <p>{{ $produk->deskripsi_produk ?? '-' }}</p>
        </div>
        <div class="detail-group">
            <label>Harga Jual:</label>
            <p>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
        </div>
        <div class="detail-group">
            <label>Satuan:</label>
            <p>{{ $produk->satuan ?? '-' }}</p>
        </div>

        {{-- Menampilkan Komponen Masakan --}}
        <div class="detail-group">
            <label>Komponen Masakan (Resep):</label>
            @if($produk->komponenMasakans && $produk->komponenMasakans->isNotEmpty())
                <ul class="komponen-list">
                    @foreach($produk->komponenMasakans as $komponen)
                        <li>
                            {{ $komponen->nama_komponen }} - {{ $komponen->pivot->jumlah_per_porsi }} {{ $komponen->satuan_dasar ?? 'unit' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Produk ini belum memiliki komponen masakan (resep).</p>
            @endif
        </div>

        <div class="detail-group">
            <label>Dibuat Pada:</label>
            <p>{{ $produk->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
        </div>
        <div class="detail-group">
            <label>Terakhir Diperbarui:</label>
            <p>{{ $produk->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
        </div>

        <div class="actions-bottom">
            <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn btn-warning">Edit Produk</a>
            <a href="{{ route('admin.produks.index') }}" class="btn btn-secondary">Kembali ke Daftar Produk</a>
        </div>
    </div>
@endsection