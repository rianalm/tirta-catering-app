{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    /* Gaya Baru untuk Stat Card */
    .stat-card-container {
        display: flex;
        gap: 20px; /* Jarak antar kartu */
        flex-wrap: wrap; /* Kartu akan turun baris jika tidak muat */
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: #fff; /* Warna dasar putih untuk kartu */
        border-radius: 10px;    /* Sudut lebih melengkung */
        box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Efek bayangan yang lebih halus */
        padding: 25px;
        flex: 1; /* Agar kartu mengisi ruang yang sama */
        min-width: 230px; /* Lebar minimum kartu agar tidak terlalu kecil */
        transition: box-shadow 0.3s ease, transform 0.3s ease; /* Transisi untuk efek hover */
        border-left: 5px solid transparent; /* Placeholder untuk border aksen */
    }

    .stat-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.12); /* Bayangan lebih kuat saat hover */
        transform: translateY(-3px); /* Sedikit terangkat saat hover */
    }

    .stat-card h3 {
        margin-top: 0;
        margin-bottom: 12px; /* Sedikit dikurangi */
        font-size: 1.1em;    /* Sedikit lebih kecil agar tidak terlalu dominan */
        color: #555;      /* Warna teks judul kartu */
        font-weight: 600; /* Sedikit lebih tebal */
    }

    .stat-card .stat-number {
        font-size: 2.2em; /* Ukuran angka statistik disesuaikan */
        font-weight: 700; /* Lebih tebal */
        margin-bottom: 12px;
        display: block;
    }

    .stat-card .stat-link {
        font-size: 0.9em;
        text-decoration: none;
        font-weight: 500;
        /* Warna link akan di-override per jenis kartu */
    }
    .stat-card .stat-link:hover {
        text-decoration: underline;
    }

    /* Warna Spesifik dan Aksen untuk Setiap Kartu */
    .stat-card.orders-today {
        border-left-color: #28a745; /* Aksen hijau */
    }
    .stat-card.orders-today .stat-number { color: #28a745; }
    .stat-card.orders-today .stat-link { color: #28a745; }

    .stat-card.orders-tomorrow {
        border-left-color: #ffc107; /* Aksen kuning/oranye */
    }
    .stat-card.orders-tomorrow .stat-number { color: #ff9800; } /* Warna angka lebih pekat */
    .stat-card.orders-tomorrow .stat-link { color: #ff9800; }

    .stat-card.orders-completed {
        border-left-color: #17a2b8; /* Aksen biru info */
    }
    .stat-card.orders-completed .stat-number { color: #17a2b8; }
    .stat-card.orders-completed .stat-link { color: #17a2b8; }

    .stat-card.products-total {
        border-left-color: #6f42c1; /* Aksen ungu */
    }
    .stat-card.products-total .stat-number { color: #6f42c1; }
    .stat-card.products-total .stat-link { color: #6f42c1; }
    /* Akhir Gaya Baru Stat Card */

    /* Styling untuk tabel ringkasan pesanan (tetap sama atau sesuaikan jika perlu) */
    .recent-orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 0.9em; /* Sedikit perkecil font tabel */
    }
    .recent-orders-table th, .recent-orders-table td {
        padding: 12px 15px; /* Padding disesuaikan */
        border: 1px solid #e9ecef; /* Border lebih halus */
        text-align: left;
    }
    .recent-orders-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 500; font-size: 0.8em; text-transform: capitalize; }
    .status-badge.pending { background-color: #ffe0b2; color: #e65100; }
    .status-badge.diproses { background-color: #bbdefb; color: #0d47a1; }
    .status-badge.dikirim { background-color: #d1c4e9; color: #311b92; }
    .status-badge.selesai { background-color: #c8e6c9; color: #1b5e20; }
    .status-badge.dibatalkan { background-color: #ffcdd2; color: #b71c1c; }

    h3.section-title { /* Untuk judul tabel ringkasan */
        margin-top: 40px; 
        margin-bottom: 15px; 
        color: #2c3e50;
        font-size: 1.4em;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
</style>
@endpush

@section('content')
    <div class="container-content"> 
        <div class="content-header">
            <h1>Selamat Datang di Admin Dashboard!</h1>
        </div>
        
        <p style="margin-bottom: 25px; font-size: 1.05em; color: #555;">Berikut adalah ringkasan singkat dari aktivitas Tirta Catering:</p>
        
        <div class="stat-card-container">
            {{-- Tambahkan class spesifik untuk styling warna --}}
            <div class="stat-card orders-today"> 
                <h3>Total Pesanan Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})</h3>
                <span class="stat-number">{{ $totalPesananHariIni_SemuaStatus ?? 0 }}</span>
                <a href="{{ route('admin.pesanan.index', ['filter_tanggal_pengiriman' => \Carbon\Carbon::today()->toDateString()]) }}" class="stat-link">
                    Lihat Semua Pesanan Hari Ini
                </a>
            </div>

            <div class="stat-card orders-tomorrow"> 
                <h3>Total Pesanan Besok ({{ \Carbon\Carbon::tomorrow()->translatedFormat('d M Y') }})</h3>
                <span class="stat-number">{{ $totalPesananBesok_SemuaStatus ?? 0 }}</span>
                <a href="{{ route('admin.pesanan.index', ['filter_tanggal_pengiriman' => \Carbon\Carbon::tomorrow()->toDateString()]) }}" class="stat-link">
                    Lihat Semua Pesanan Besok
                </a>
            </div>
            
            <div class="stat-card orders-completed"> 
                <h3>Pesanan Selesai Hari Ini</h3>
                <span class="stat-number">{{ $jumlahPesananSelesaiHariIni ?? 0 }}</span>
                @if(isset($jumlahPesananSelesaiHariIni) && $jumlahPesananSelesaiHariIni > 0)
                <a href="{{ route('admin.pesanan.index', ['filter_tanggal_pengiriman' => \Carbon\Carbon::today()->toDateString(), 'status' => 'selesai']) }}" class="stat-link">
                    Lihat Detail Pesanan Selesai
                </a>
                @else
                <span style="font-size:0.9em; color: #777;">Belum ada pesanan selesai hari ini.</span>
                @endif
            </div>

            <div class="stat-card products-total"> 
                <h3>Total Produk Terdaftar</h3>
                <span class="stat-number">{{ $totalProduk ?? 0 }}</span>
                <a href="{{ route('admin.produks.index') }}" class="stat-link">Kelola Produk</a>
            </div>
        </div>

        @if(isset($pesananHariIniRingkas_Aktif) && $pesananHariIniRingkas_Aktif->isNotEmpty())
            <h3 class="section-title">Ringkasan Pesanan Aktif untuk Hari Ini ({{ $pesananHariIniRingkas_Aktif->count() }} dari maks. 10 ditampilkan):</h3>
            <div class="table-responsive">
                <table class="recent-orders-table">
                    <thead><tr><th>ID</th><th>Pelanggan</th><th>Waktu Kirim</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($pesananHariIniRingkas_Aktif as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td><td>{{ $pesanan->nama_pelanggan }}</td><td>{{ $pesanan->waktu_pengiriman ?? '-' }}</td>
                            <td><span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">{{ ucfirst($pesanan->status_pesanan) }}</span></td>
                            <td><a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-info">Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(isset($totalPesananHariIni_SemuaStatus) && $totalPesananHariIni_SemuaStatus > 0 && (!isset($pesananHariIniRingkas_Aktif) || $pesananHariIniRingkas_Aktif->isEmpty()))
            <p style="margin-top:30px; text-align:center; color:#777;">Tidak ada pesanan aktif yang perlu ditampilkan untuk hari ini.</p>
        @elseif(!isset($totalPesananHariIni_SemuaStatus) || $totalPesananHariIni_SemuaStatus == 0)
             {{-- Tidak menampilkan apa-apa jika memang tidak ada pesanan sama sekali untuk hari ini --}}
        @endif

        @if(isset($pesananBesokRingkas_Aktif) && $pesananBesokRingkas_Aktif->isNotEmpty())
            <h3 class="section-title">Ringkasan Pesanan Aktif untuk Besok ({{ $pesananBesokRingkas_Aktif->count() }} dari maks. 10 ditampilkan):</h3>
            <div class="table-responsive">
                <table class="recent-orders-table">
                     <thead><tr><th>ID</th><th>Pelanggan</th><th>Waktu Kirim</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($pesananBesokRingkas_Aktif as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td><td>{{ $pesanan->nama_pelanggan }}</td><td>{{ $pesanan->waktu_pengiriman ?? '-' }}</td>
                            <td><span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">{{ ucfirst($pesanan->status_pesanan) }}</span></td>
                            <td><a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-info">Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(isset($totalPesananBesok_SemuaStatus) && $totalPesananBesok_SemuaStatus > 0 && (!isset($pesananBesokRingkas_Aktif) || $pesananBesokRingkas_Aktif->isEmpty()))
            <p style="margin-top:30px; text-align:center; color:#777;">Tidak ada pesanan aktif yang perlu ditampilkan untuk besok.</p>
        @elseif(!isset($totalPesananBesok_SemuaStatus) || $totalPesananBesok_SemuaStatus == 0)
            <p style="margin-top:30px; text-align:center; color:#777;">Tidak ada pesanan yang dijadwalkan untuk besok.</p>
        @endif

        @if(isset($pesananSelesaiHariIniRingkas) && $pesananSelesaiHariIniRingkas->isNotEmpty())
            <h3 class="section-title">Ringkasan Pesanan Selesai Hari Ini ({{ $pesananSelesaiHariIniRingkas->count() }} ditampilkan):</h3>
            <div class="table-responsive">
                <table class="recent-orders-table">
                    <thead><tr><th>ID</th><th>Pelanggan</th><th>Waktu Kirim</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($pesananSelesaiHariIniRingkas as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td><td>{{ $pesanan->nama_pelanggan }}</td><td>{{ $pesanan->waktu_pengiriman ?? '-' }}</td>
                            <td><span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">{{ ucfirst($pesanan->status_pesanan) }}</span></td>
                            <td><a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-info">Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(isset($jumlahPesananSelesaiHariIni) && $jumlahPesananSelesaiHariIni == 0 && isset($totalPesananHariIni_SemuaStatus) && $totalPesananHariIni_SemuaStatus > 0)
            <p style="margin-top:30px; text-align:center; color:#777;">Belum ada pesanan yang diselesaikan untuk hari ini.</p>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // console.log('Dashboard page specific JS loaded');
</script>
@endpush