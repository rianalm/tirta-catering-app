<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $pesanan->id }} - Tirta Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 20px auto;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.5em;
            font-weight: 700;
        }

        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-section h2 {
            color: #34495e;
            font-size: 1.6em;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .detail-item {
            display: flex;
            margin-bottom: 10px;
        }

        .detail-item strong {
            flex: 0 0 180px; /* Lebar label */
            color: #555;
            font-weight: 600;
        }

        .detail-item span {
            flex-grow: 1;
            color: #333;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .item-list li {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-list li span {
            font-weight: 500;
            color: #444;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.9em;
            margin-left: 10px;
        }

        .status-badge.baru {
            background-color: #ffe0b2;
            color: #e65100;
        }

        .status-badge.diproses {
            background-color: #bbdefb;
            color: #0d47a1;
        }

        .status-badge.selesai {
            background-color: #c8e6c9;
            color: #1b5e20;
        }

        .status-badge.dibatalkan {
            background-color: #ffcdd2;
            color: #b71c1c;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            margin: 0 5px;
            transition: background-color 0.2s ease-in-out;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-info {
            background-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
        }

        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .detail-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-item strong {
                flex: none;
                width: 100%;
                margin-bottom: 3px;
            }
            .detail-item span {
                width: 100%;
            }
            .actions .btn {
                width: 100%;
                margin-bottom: 10px;
                margin-left: 0;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Pesanan #{{ $pesanan->id }}</h1>

        <div class="detail-section">
            <h2>Informasi Umum</h2>
            <div class="detail-item">
                <strong>ID Pesanan:</strong>
                <span>{{ $pesanan->id }}</span>
            </div>
            <div class="detail-item">
                <strong>Tanggal Pesanan:</strong>
                <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d F Y H:i') }}</span>
            </div>
            <div class="detail-item">
                <strong>Status Pesanan:</strong>
                <span>
                    <span class="status-badge {{ strtolower($pesanan->status_pesanan) }}">
                        {{ $pesanan->status_pesanan }}
                    </span>
                </span>
            </div>
            <div class="detail-item">
                <strong>Total Harga:</strong>
                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Detail Pelanggan</h2>
            <div class="detail-item">
                <strong>Nama Pelanggan:</strong>
                <span>{{ $pesanan->nama_pelanggan }}</span>
            </div>
            <div class="detail-item">
                <strong>Nomor Telepon:</strong>
                <span>{{ $pesanan->telepon_pelanggan }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Item Pesanan</h2>
            @if ($pesanan->itemPesanans->isEmpty())
                <p>Tidak ada item pesanan.</p>
            @else
                <ul class="item-list">
                    @foreach ($pesanan->itemPesanans as $item)
                        <li>
                            <span>{{ $item->produk->nama_produk }}</span>
                            <span>{{ $item->jumlah_porsi }} porsi @ Rp {{ number_format($item->harga_satuan_saat_pesan, 0, ',', '.') }} = Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="detail-section">
            <h2>Detail Pengiriman</h2>
            <div class="detail-item">
                <strong>Tanggal Pengiriman:</strong>
                <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d F Y') }}</span>
            </div>
            <div class="detail-item">
                <strong>Waktu Pengiriman:</strong>
                <span>{{ $pesanan->waktu_pengiriman ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <strong>Alamat Pengiriman:</strong>
                <span>{{ $pesanan->alamat_pengiriman }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Catatan Khusus</h2>
            <div class="detail-item">
                <strong>Catatan:</strong>
                <span>{{ $pesanan->catatan_khusus ?? '-' }}</span>
            </div>
        </div>

        <div class="actions">
            {{-- Tombol untuk kembali ke daftar pesanan --}}
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>

            {{-- Placeholder untuk tombol Edit dan Hapus --}}
            <a href="#" class="btn btn-info">Edit Pesanan</a>
            <form action="#" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">Hapus Pesanan</button>
            </form>
        </div>
    </div>
</body>
</html>