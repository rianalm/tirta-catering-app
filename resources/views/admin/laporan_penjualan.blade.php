<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Tirta Catering</title>
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
            max-width: 1000px;
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

        .report-summary {
            background-color: #e6f7ff;
            border: 1px solid #91d5ff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .report-summary h2 {
            color: #0050b3;
            margin-top: 0;
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        .report-summary p {
            font-size: 1.4em;
            font-weight: 600;
            color: #001529;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .filter-form .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-form label {
            font-weight: 600;
            color: #495057;
        }

        .filter-form input[type="date"] {
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.2s;
            min-width: 180px;
        }

        .filter-form input[type="date"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .filter-form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.2s;
            align-self: flex-end; /* Align to the bottom of the group */
        }

        .filter-form button:hover {
            background-color: #218838;
        }

        .filter-form a.btn-reset {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.2s;
            align-self: flex-end;
            display: flex; /* Make it a flex item to align */
            align-items: center; /* Center text vertically */
            justify-content: center; /* Center text horizontally */
        }

        .filter-form a.btn-reset:hover {
            background-color: #5a6268;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .back-link:hover {
            background-color: #5a6268;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-form .form-group {
                width: 100%;
            }
            .filter-form input[type="date"] {
                width: 100%;
                min-width: unset;
            }
            .filter-form button, .filter-form a.btn-reset {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Penjualan</h1>

        {{-- Form Filter Periode --}}
        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="form-group">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <button type="submit">Filter</button>
            @if ($startDate || $endDate)
                <a href="{{ route('admin.laporan.penjualan') }}" class="btn-reset">Reset Filter</a>
            @endif
        </form>

        {{-- Ringkasan Laporan --}}
        <div class="report-summary">
            <h2>Total Penjualan</h2>
            <p>
                Rp {{ number_format($totalSales, 0, ',', '.') }}
                @if ($startDate && $endDate)
                    <br>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
                @elseif ($startDate)
                    <br>Dari Tanggal: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
                @elseif ($endDate)
                    <br>Sampai Tanggal: {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
                @else
                    <br>Semua Waktu (Status "Selesai")
                @endif
            </p>
        </div>

        {{-- Anda bisa menambahkan tabel detail pesanan di sini nanti jika diperlukan --}}
        {{--
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesData as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d-m-Y') }}</td>
                            <td>{{ $pesanan->nama_pelanggan }}</td>
                            <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($pesanan->status_pesanan) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data penjualan untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        --}}

        <div style="text-align: center;">
            <a href="{{ route('admin.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>