<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Worksheet Pesanan #{{ $pesanan->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 16px; margin: 0 0 10px 0; padding-bottom: 5px; border-bottom: 1px solid #ccc; }
        .detail-grid { width: 100%; }
        .detail-grid td { padding: 5px 0; vertical-align: top; }
        .detail-grid .label { font-weight: bold; width: 150px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .items-table .qty { text-align: center; }
        .catatan { background-color: #fefcea; border: 1px solid #fcebb6; padding: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LEMBAR KERJA DAPUR & PACKING</h1>
            <p>Pesanan #{{ $pesanan->id }} - <strong>{{ $pesanan->nama_pelanggan }}</strong></p>
        </div>

        <div class="section">
            <h2>Informasi Pengiriman</h2>
            <table class="detail-grid">
                <tr>
                    <td class="label">Tanggal & Waktu Kirim</td>
                    <td>: {{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->translatedFormat('l, d F Y') }} @ {{ $pesanan->waktu_pengiriman ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Pengiriman</td>
                    <td>: {{ $pesanan->alamat_pengiriman }}</td>
                </tr>
                 <tr>
                    <td class="label">Jenis Penyajian</td>
                    <td>: {{ $pesanan->jenis_penyajian ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Daftar Item</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No.</th>
                        <th>Nama Produk</th>
                        <th style="width: 20%; text-align:center;">Jumlah</th>
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
        <div class="section">
            <h2>Catatan Khusus</h2>
            <div class="catatan">
                {{ $pesanan->catatan_khusus }}
            </div>
        </div>
        @endif
    </div>
</body>
</html>