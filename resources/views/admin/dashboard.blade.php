<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tirta Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 2.8em;
            font-weight: 700;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #555;
        }

        .dashboard-links a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            margin: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
        }

        .dashboard-links a:hover {
            background-color: #0056b3;
        }

        .dashboard-links a.btn-secondary {
            background-color: #6c757d;
        }

        .dashboard-links a.btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Admin Dashboard!</h1>
        <p>Gunakan navigasi di bawah untuk mengelola aplikasi KP Tirta Catering.</p>
        <div class="dashboard-links">
            <a href="{{ route('admin.produks.index') }}">Kelola Produk</a>
            <a href="{{ route('admin.pesanan.index') }}">Kelola Pesanan</a>
            <a href="{{ route('admin.laporan.penjualan') }}" class="btn-secondary">Lihat Laporan Penjualan</a>
        </div>
    </div>
</body>
</html>