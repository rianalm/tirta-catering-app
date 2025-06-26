@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .recent-orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 0.9em; 
    }
    .recent-orders-table th, .recent-orders-table td {
        padding: 12px 15px; /* Padding disesuaikan */
        border: 1px solid #e9ecef; 
        text-align: left;
    }
    .recent-orders-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    h3.section-title { 
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
                <a href="{{ route('admin.pesanan.operasional', ['filter_tanggal_pengiriman' => \Carbon\Carbon::today()->toDateString()]) }}" class="stat-link">
                    Lihat Semua Pesanan Hari Ini
                </a>
            </div>

            <div class="stat-card orders-tomorrow"> 
                <h3>Total Pesanan Besok ({{ \Carbon\Carbon::tomorrow()->translatedFormat('d M Y') }})</h3>
                <span class="stat-number">{{ $totalPesananBesok_SemuaStatus ?? 0 }}</span>
                <a href="{{ route('admin.pesanan.operasional', ['filter_tanggal_pengiriman' => \Carbon\Carbon::tomorrow()->toDateString()]) }}" class="stat-link">
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

        <div class="chart-container">
            <h3 class="section-title" style="margin-top:0;">Grafik Pesanan (7 Hari Terakhir)</h3>
            <canvas id="weeklyOrdersChart"></canvas>
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
                            <td><a href="{{ route('admin.pesanan.operasional.show', $pesanan->id) }}" class="btn btn-sm btn-info">Detail</a></td>
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
    const chartLabels = @json($chartLabels ?? []);
    const chartData = @json($chartData ?? []);
    const ctx = document.getElementById('weeklyOrdersChart');

    if (ctx && chartLabels.length > 0) {
        new Chart(ctx, {
            type: 'bar', // Tipe grafik: bar, line, pie, dll.
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: chartData,
                    backgroundColor: 'rgba(23, 162, 184, 0.6)', // Warna bar (biru info)
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Pastikan sumbu Y hanya menampilkan angka bulat
                            stepSize: 1, 
                            callback: function(value) {if (value % 1 === 0) {return value;}}
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda di atas
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Jumlah Pesanan: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush