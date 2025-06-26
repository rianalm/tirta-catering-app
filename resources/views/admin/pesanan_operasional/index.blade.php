@extends('layouts.admin')
@section('title', 'Daftar Pesanan Harian')

@push('styles')
<style>
    .operational-table-container {
        font-size: 0.9em; /* Font lebih kecil */
    }
    .operational-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Agar lebar kolom lebih terprediksi */
    }
    .operational-table th, .operational-table td {
        border: 1px solid #ddd;
        padding: 8px;
        vertical-align: top;
        word-wrap: break-word; /* Agar teks panjang bisa turun baris */
    }
    .operational-table th {
        background-color: #f2f2f2;
        text-align: left;
    }
    .item-list-cell ul {
        margin: 0;
        padding-left: 18px;
    }
    .item-list-cell li {
        margin-bottom: 4px;
    }
    .action-icons {
        text-align: center;
        width: 120px; /* Lebar disesuaikan untuk 3 tombol */
    }
    .action-icons .status-action-btn, .action-icons .detail-btn { /* Menambahkan .detail-btn */
        color: #333;
        text-decoration: none;
        margin: 0 8px;
        font-size: 1.4em;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        transition: transform 0.2s ease;
    }
    .action-icons .status-action-btn:hover, .action-icons .detail-btn:hover {
        transform: scale(1.2); /* Efek hover pada ikon */
    }
    .filter-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    .filter-container input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Daftar Pesanan Operasional</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div id="ajax-message-container" style="display: none; margin-bottom: 20px;">
            <div id="ajax-message" class="alert"></div>
        </div>

        <div class="filter-container">
            <form action="{{ route('admin.pesanan.operasional') }}" method="GET" id="filterOperasionalForm">
                <label for="filter_tanggal_pengiriman"><strong>Tampilkan Pesanan untuk Tanggal:</strong></label>
                <input type="date" name="filter_tanggal_pengiriman" value="{{ $tanggalFilter ?? '' }}" onchange="this.form.submit()">
            </form>
        </div>

        <div class="table-responsive operational-table-container">
            <table class="operational-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Pelanggan & Info Kirim</th>
                        <th style="width: 35%;">Item Pesanan & Harga</th>
                        <th style="width: 35%;">Alamat & Penyajian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $pesanan)
                    <tr>
                        <td>
                            <strong>{{ $pesanan->nama_pelanggan }}</strong><br>
                            <small>
                                Telp: {{ $pesanan->telepon_pelanggan }}<br>
                                Kirim: {{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d/m/Y') }}
                                @if($pesanan->waktu_pengiriman)
                                    <strong>jam {{ $pesanan->waktu_pengiriman }}</strong>
                                @endif
                            </small>
                        </td>
                        <td class="item-list-cell">
                            <ul>
                                @foreach($pesanan->itemPesanans as $item)
                                    <li>{{ $item->jumlah_porsi }}x {{ $item->produk->nama_produk }} (Rp {{ number_format($item->subtotal_item, 0, ',', '.') }})</li>
                                @endforeach
                            </ul>
                            <hr style="margin: 5px 0;">
                            <strong>Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <strong>Alamat:</strong> {{ $pesanan->alamat_pengiriman }}<br>
                            <strong>Penyajian:</strong> {{ $pesanan->jenis_penyajian ?? '-' }}<br>
                            <strong>Catatan:</strong> {{ $pesanan->catatan_khusus ?? '-' }}
                        </td>
                        <td class="action-icons">
                            {{-- MODIFIKASI: Link ke route detail operasional --}}
                            <a href="{{ route('admin.pesanan.operasional.show', $pesanan->id) }}" title="Lihat Detail" class="detail-btn">
                                <i class="fas fa-eye" style="color: #007bff;"></i>
                            </a>

                            @if($pesanan->status_pesanan == 'diproses')
                                <button title="Tandai Sudah Dikirim" class="status-action-btn" data-id="{{ $pesanan->id }}" data-status="dikirim"><i class="fas fa-truck" style="color: #17a2b8;"></i></button>
                            @endif
                            @if($pesanan->status_pesanan == 'dikirim')
                                <button title="Tandai Sudah Selesai" class="status-action-btn" data-id="{{ $pesanan->id }}" data-status="selesai"><i class="fas fa-check-circle" style="color: #28a745;"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Tidak ada pesanan aktif untuk tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    function showAjaxMessage(message, type = 'success') {
        const container = document.getElementById('ajax-message-container');
        const msgDiv = document.getElementById('ajax-message');
        if (container && msgDiv) {
            msgDiv.className = 'alert';
            msgDiv.textContent = '';
            msgDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-error');
            msgDiv.textContent = message;
            container.style.display = 'block';
            setTimeout(() => { container.style.display = 'none'; }, 5000);
        }
    }

    document.querySelectorAll('.status-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pesananId = this.dataset.id;
            const newStatus = this.dataset.status;
            let confirmationMessage = `Ubah status pesanan #${pesananId} menjadi "${newStatus}"?`;

            if (!confirm(confirmationMessage)) { return; }

            fetch(`/admin/pesanan/${pesananId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Gagal memperbarui status.');
                    });
                }
                return response.json();
            })
            .then(data => {
                showAjaxMessage(data.message, 'success');
                setTimeout(() => { window.location.reload(); }, 1500);
            })
            .catch(error => {
                showAjaxMessage(error.message || 'Terjadi kesalahan.', 'error');
            });
        });
    });
</script>
@endpush