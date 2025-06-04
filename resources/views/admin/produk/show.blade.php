<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Tirta Catering</title>
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
            max-width: 700px;
            margin: 20px auto;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.2em;
            font-weight: 700;
        }

        .detail-group {
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px dashed #e0e0e0;
        }

        .detail-group:last-child {
            border-bottom: none;
        }

        .detail-group label {
            font-weight: 600;
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }

        .detail-group p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }

        .actions-bottom {
            margin-top: 30px;
            text-align: center;
        }

        .btn-back, .btn-edit {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Produk</h1>

        <div class="detail-group">
            <label>ID Produk:</label>
            <p>{{ $produk->id }}</p>
        </div>
        <div class="detail-group">
            <label>Nama Produk:</label>
            <p>{{ $produk->nama_produk }}</p>
        </div>
        <div class="detail-group">
            <label>Deskripsi Produk:</label>
            <p>{{ $produk->deskripsi_produk ?? '-' }}</p>
        </div>
        <div class="detail-group">
            <label>Harga Jual:</label>
            <p>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
        </div>
        <div class="detail-group">
            <label>Satuan:</label>
            <p>{{ $produk->satuan ?? '-' }}</p>
        </div>
        <div class="detail-group">
            <label>Dibuat Pada:</label>
            <p>{{ $produk->created_at?->format('d M Y H:i') ?? '-' }}</p>
        </div>
        <div class="detail-group">
            <label>Terakhir Diperbarui:</label>
            <p>{{ $produk->updated_at?->format('d M Y H:i') ?? '-' }}</p>
        </div>

        <div class="actions-bottom">
            <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn-edit">Edit Produk</a>
            <a href="{{ route('admin.produks.index') }}" class="btn-back">Kembali ke Daftar Produk</a>
        </div>
    </div>
</body>
</html>