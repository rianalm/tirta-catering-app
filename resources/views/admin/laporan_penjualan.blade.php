{{-- resources/views/admin/laporan_penjualan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@push('styles')
<style>
    /* Gaya CSS yang sebelumnya ada di <style> tag di file asli dipindahkan ke sini */
    /* ... (Salin semua CSS dari file asli Anda ke sini) ... */
    .container-content { max-width: 1000px; margin: 0 auto; }
    .content-header h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2.5em; font-weight: 700; }
    .report-summary {
        background-color: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px;
        padding: 20px; margin-bottom: 30px; text-align: center;
    }
    .report-summary h2 { color: #0050b3; margin-top: 0; font-size: 1.8em; margin-bottom: 15px; }
    .report-summary p { font-size: 1.4em; font-weight: 600; color: #001529; }

    .filter-form {
        display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-bottom: 30px;
        padding: 20px; background-color: #f8f9fa; border-radius: 10px; border: 1px solid #e9ecef;
    }
    .filter-form .form-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-form label { font-weight: 600; color: #495057; }
    .filter-form input[type="date"] {
        padding: 10px 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 1em;
        transition: border-color 0.2s; min-width: 180px;
    }
    .filter-form input[type="date"]:focus {
        border-color: #007bff; outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    .filter-form button { /* .btn .btn-success */
        align-self: flex-end; 
    }
    .filter-form a.btn-reset { /* .btn .btn-secondary */
        align-self: flex-end; display: flex; align-items: center; justify-content: center;
    }
    .back-link { /* .btn .btn-secondary */
        display: inline-block; margin-top: 30px; text-align: center;
    }
    
    /* Table Styling */
    .table-responsive { overflow-x: auto; }
    table {
        width: 100%; border-collapse: collapse; margin-top: 0; /* Disesuaikan */
        background-color: #fff; border-radius: 10px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eceeef; vertical-align: middle; }
    thead th {
        background-color: #f8f9fa; color: #495057; font-weight: 700;
        text-transform: uppercase; font-size: 0.9em;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f2f2f2; }
    .item-list { list-style: none; padding: 0; margin: 0; }
    .item-list li { margin-bottom: 5px; font-size: 0.95em; color: #555; }
    .status-badge {
        display: inline-block; padding: 5px 10px; border-radius: 5px;
        font-weight: 600; font-size: 0.85em; text-transform: capitalize;
    }
    /* Status badge colors (sama seperti di show_pesanan) */
    .status-badge.pending, .status-badge.baru { background-color: #ffe0b2; color: #e65100; }
    .status-badge.diproses { background-color: #bbdefb; color: #0d47a1; }
    .status-badge.selesai { background-color: #c8e6c9; color: #1b5e20; }
    .status-badge.dibatalkan { background-color: #ffcdd2; color: #b71c1c; }
    .status-badge.dikirim { background-color: #d1c4e9; color: #311b92; }


    @media (max-width: 768px) {
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-form .form-group { width: 100%; }
        .filter-form input[type="date"] { width: 100%; min-width: unset; }
        .filter-form button, .filter-form a.btn-reset { width: 100%; }
        
        /* Responsive Table (Copy dari index_pesanan jika sama) */
        table thead { display: none; }
        table tr { display: block; margin-bottom: .625em; border: 1px solid #ddd; border-radius: 4px; }
        table td { display: block; text-align: right; font-size: .8em; border-bottom: 1px dotted #ccc; }
        table td::before { content: attr(data-label); float: left; font-weight: bold; text-transform: uppercase; }
        table td:last-child { border-bottom: 0; }
    }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Laporan Penjualan</h1>
        </div>

        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="form-group">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <button type="submit" class="btn btn-success">Filter</button>
            @if ($startDate || $endDate)
                <a href="{{ route('admin.laporan.penjualan') }}" class="btn-reset btn btn-secondary">Reset Filter</a>
            @endif
        </form>

        <div class="report-summary">
            <h2>Total Penjualan</h2>
            <p>
                Rp {{ number_format($totalSales, 0, ',', '.') }}
                @if ($startDate && $endDate)
                    <br><small>Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</small>
                @elseif ($startDate)
                    <br><small>Dari Tanggal: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</small>
                @elseif ($endDate)
                    <br><small>Sampai Tanggal: {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</small>
                @else
                    <br><small>Semua Waktu (Untuk Pesanan Berstatus "Selesai")</small>
                @endif
            </p>
        </div>

        <h2 style="text-align: center; color: #2c3e50; margin-top: 40px; margin-bottom: 20px;">Detail Pesanan Selesai</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesData as $pesanan)
                        <tr>
                            <td data-label="ID Pesanan">{{ $pesanan->id }}</td>
                            <td data-label="Tanggal Pesanan">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->translatedFormat('d M Y') }}</td>
                            <td data-label="Pelanggan">{{ $pesanan->nama_pelanggan }}</td>
                            <td data-label="Produk">
                                @if($pesanan->itemPesanans->isNotEmpty())
                                    <ul class="item-list">
                                        @foreach($pesanan->itemPesanans as $item)
                                            <li>{{ $item->produk->nama_produk }} ({{ $item->jumlah_porsi }} porsi)</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Total Harga">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                <span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">
                                    {{ ucfirst($pesanan->status_pesanan) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data penjualan (pesanan 'Selesai') untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin-top:30px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection