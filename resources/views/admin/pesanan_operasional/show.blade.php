@extends('layouts.admin')

@section('title', 'Detail Pesanan Operasional #' . $pesanan->id)

@push('styles')
<style>
    .work-order-container {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        max-width: 800px; /* Lebar maksimal konten */
        margin: 0 auto; /* Posisi di tengah */
    }
    .work-order-header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .work-order-header h1 {
        margin: 0;
        font-size: 2em;
        color: #2c3e50;
    }
    .work-order-header p {
        margin: 5px 0 0 0;
        color: #555;
        font-size: 1.1em;
    }
    .work-order-section {
        margin-bottom: 25px;
    }
    .work-order-section h2 {
        font-size: 1.4em;
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* 2 kolom */
        gap: 20px;
    }
    .detail-block {
        line-height: 1.6;
    }
    .detail-block strong {
        display: block;
        font-weight: 600;
        color: #555;
        font-size: 0.9em;
        margin-bottom: 4px;
    }
    .detail-block span, .detail-block p {
        font-size: 1em;
    }
    .items-table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }
    .items-table th, .items-table td {
        border: 1px solid #ddd;
        padding: 12px; /* Padding lebih besar agar lega */
        text-align: left;
    }
    .items-table th { background-color: #f8f9fa; }
    .items-table td.qty { text-align: center; font-weight: bold; }

    .catatan-penting {
        background-color: #fff3cd;
        padding: 15px;
        border-radius: 5px;
        border-left: 5px solid #ffeeba;
        color: #856404;
    }

    .action-buttons {
        text-align: right;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    .btn-print { background-color: #5a6268; color: white; }
    .btn-print:hover { background-color: #474d52; }

    /* Print styles */
    @media print {
        body, .admin-main-content, .container-content {
            background: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }
        .admin-sidebar, .content-header, .action-buttons, .back-link, #filterOperasionalForm {
            display: none !important; /* Sembunyikan elemen yang tidak perlu saat cetak */
        }
        .work-order-container {
            box-shadow: none;
            border: none;
            padding: 0;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="work-order-container">
    <div class="work-order-header">
        <h1>Lembar Kerja Dapur & Packing</h1>
        <p>Pesanan #{{ $pesanan->id }} - <strong>{{ $pesanan->nama_pelanggan }}</strong></p>
    </div>

    <div class="work-order-section">
        <h2>Informasi Pengiriman</h2>
        <div class="detail-grid">
            <div class="detail-block">
                <strong>Tanggal & Waktu Kirim</strong>
                <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->translatedFormat('l, d F Y') }} @ <strong>{{ $pesanan->waktu_pengiriman ?? 'N/A' }}</strong></span>
            </div>
            <div class="detail-block">
                <strong>Jenis Penyajian</strong>
                <span>{{ $pesanan->jenis_penyajian ?? '-' }}</span>
            </div>
            <div class="detail-block" style="grid-column: span 2;">
                <strong>Alamat Pengiriman</strong>
                <span>{{ $pesanan->alamat_pengiriman }}</span>
            </div>
        </div>
    </div>

    <div class="work-order-section">
        <h2>Daftar Item yang Harus Disiapkan</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th>Nama Produk</th>
                    <th style="width: 120px; text-align:center;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->itemPesanans as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="qty">{{ $item->jumlah_porsi }} {{ $item->produk->satuan ?? 'Porsi' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pesanan->catatan_khusus)
    <div class="work-order-section">
        <h2>Catatan Khusus Penting!</h2>
        <div class="detail-block">
            <p class="catatan-penting">
                {{ $pesanan->catatan_khusus }}
            </p>
        </div>
    </div>
    @endif

    <div class="action-buttons">
        <a href="{{ route('admin.pesanan.operasional.pdf', $pesanan->id) }}" target="_blank" class="btn btn-print">
            <i class="fas fa-print" style="margin-right: 8px;"></i> Cetak Lembar Kerja (PDF)
        </a>
        <a href="{{ route('admin.pesanan.operasional') }}" class="btn btn-secondary back-link">Kembali ke Daftar</a>
    </div>
</div>
@endsection

