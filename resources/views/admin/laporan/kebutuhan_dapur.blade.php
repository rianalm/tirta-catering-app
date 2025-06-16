@extends('layouts.admin')

@section('title', 'Laporan Kebutuhan Dapur')

@push('styles')
<style>
    /* Styling dasar untuk laporan */
    .report-header { margin-bottom: 20px; }
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table th, .report-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    .report-table th { background-color: #f2f2f2; }
    .filter-container { margin-bottom: 20px; display: flex; align-items: center; gap: 10px; background: #f8f9fa; padding: 15px; border-radius: 8px; }
    @media print {
        /* Styling untuk print */
        .admin-sidebar, .content-header, .filter-container, .btn-secondary { display: none !important; }
        .container-content { box-shadow: none; border: none; }
    }
</style>
@endpush

@section('content')
<div class="container-content">
    <div class="content-header">
        <h1>Laporan Kebutuhan Dapur</h1>
    </div>

    <div class="filter-container">
        <form action="{{ route('admin.laporan.dapur') }}" method="GET">
            <label for="tanggal">Tampilkan Kebutuhan untuk Tanggal:</label>
            <input type="date" name="tanggal" value="{{ $targetDate }}" style="padding: 5px;">
            <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
            <a href="{{ route('admin.laporan.dapur') }}" class="btn btn-sm btn-secondary" style="margin-left: 5px;">Kebutuhan Besok</a>
        </form>
    </div>

    <div class="report-header">
        <h3>Total Kebutuhan Komponen untuk Pengiriman Tanggal: {{ \Carbon\Carbon::parse($targetDate)->translatedFormat('l, d F Y') }}</h3>
    </div>

    <div class="table-responsive">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Komponen</th>
                    <th style="width: 20%;">Total Dibutuhkan</th>
                    <th style="width: 15%;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kebutuhanKomponen as $index => $komponen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $komponen['nama_komponen'] }}</td>
                        <td><strong>{{ $komponen['total_kebutuhan'] }}</strong></td>
                        <td>{{ $komponen['satuan_dasar'] ?? 'unit' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">
                            Tidak ada pesanan aktif untuk tanggal yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

     <div style="margin-top: 20px; text-align: right;">
        <button onclick="window.print()" class="btn btn-dark"><i class="fas fa-print" style="margin-right: 8px;"></i> Cetak Laporan</button>
    </div>

</div>
@endsection