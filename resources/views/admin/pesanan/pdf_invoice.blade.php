<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $pesanan->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; }
        .header { text-align: right; }
        .header h1 { font-size: 36px; color: #333; margin: 0; }
        .header .company-name { font-size: 16px; font-weight: bold; }
        .company-details { font-size: 11px; color: #555; }
        .customer-details { margin-top: 40px; margin-bottom: 40px; }
        .customer-details table { width: 100%; }
        .customer-details .billed-to { font-size: 14px; font-weight: bold; margin-bottom: 5px; }
        .items-table { width: 100%; border-collapse: collapse; line-height: 1.5; }
        .items-table thead th { background-color: #f2f2f2; border-bottom: 2px solid #ddd; padding: 8px; text-align: left; }
        .items-table tbody td { border-bottom: 1px solid #eee; padding: 8px; }
        .items-table .total-row td { border-bottom: none; font-weight: bold; }
        .summary-table { width: 40%; float: right; margin-top: 20px; }
        .summary-table td { padding: 5px 8px; }
        .summary-table .label { text-align: right; }
        .summary-table .grand-total { font-size: 1.2em; font-weight: bold; border-top: 2px solid #333; padding-top: 8px; margin-top: 5px; }
        .footer { margin-top: 50px; text-align: center; color: #777; font-size: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: top;">
                    <div class="company-name">{{ $companyInfo['name'] }}</div>
                    <div class="company-details">
                        {{ $companyInfo['address'] }}<br>
                        {{ $companyInfo['phone'] }}<br>
                        {{ $companyInfo['email'] }}
                    </div>
                </td>
                <td style="vertical-align: top;" class="header">
                    <h1>INVOICE</h1>
                    <div><strong>No. Invoice:</strong> INV-{{ $pesanan->id }}-{{ $pesanan->created_at->format('Ym') }}</div>
                    <div><strong>Tanggal Invoice:</strong> {{ now()->translatedFormat('d F Y') }}</div>
                    <div><strong>Tanggal Pengiriman:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->translatedFormat('d F Y') }}</div>
                </td>
            </tr>
        </table>

        <div class="customer-details">
            <div class="billed-to">Ditagihkan Kepada:</div>
            <strong>{{ $pesanan->nama_pelanggan }}</strong><br>
            {{ $pesanan->telepon_pelanggan }}<br>
            {{ $pesanan->alamat_pengiriman }}
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 55%;">Deskripsi</th>
                    <th style="width: 10%; text-align:center;">Jumlah</th>
                    <th style="width: 15%; text-align:right;">Harga Satuan</th>
                    <th style="width: 20%; text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->itemPesanans as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td style="text-align: center;">{{ $item->jumlah_porsi }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->harga_satuan_saat_pesan, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="clear:both; height: 1px;"></div>

        <table class="summary-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td style="text-align: right;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @if($pesanan->pajak > 0)
            <tr>
                <td class="label">Pajak ({{ $pesanan->pajak_persen > 0 ? $pesanan->pajak_persen.'%' : '' }}):</td>
                <td style="text-align: right;">Rp {{ number_format($pesanan->pajak, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($pesanan->ongkir > 0)
            <tr>
                <td class="label">Ongkos Kirim:</td>
                <td style="text-align: right;">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($pesanan->biaya_lain > 0)
            <tr>
                <td class="label">Biaya Lain:</td>
                <td style="text-align: right;">Rp {{ number_format($pesanan->biaya_lain, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td class="label">GRAND TOTAL:</td>
                <td style="text-align: right;">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div style="clear:both;"></div>

        <div class="footer">
            <p>Terima kasih atas pesanan Anda!</p>
            <p>Mohon lakukan pembayaran ke rekening berikut: <strong>{{ $companyInfo['rekening'] }}</strong></p>
        </div>
    </div>
</body>
</html>