<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kebutuhan Dapur - {{ $targetDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Masakan Harian</h1>
        <p>Untuk Pesanan {{ \Carbon\Carbon::parse($targetDate)->translatedFormat('l, d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th>Nama Komponen</th>
                <th style="width: 20%;">Total Dibutuhkan</th>
                <th style="width: 20%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kebutuhanKomponen as $komponen)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $komponen['nama_komponen'] }}</td>
                    <td class="total">{{ $komponen['total_kebutuhan'] }}</td>
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
</body>
</html>