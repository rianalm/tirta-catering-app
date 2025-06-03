<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan #{{ $pesanan->id }} - Tirta Catering</title>
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
            max-width: 800px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            display: block;
            width: 100%;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .item-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: flex-end;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background-color: #fcfcfc;
        }

        .item-row select,
        .item-row input[type="number"] {
            flex-grow: 1;
        }

        .btn-remove-item {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }

        .btn-remove-item:hover {
            background-color: #c82333;
        }

        .btn-add-item {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-add-item:hover {
            background-color: #0056b3;
        }

        .item-fields-container {
            border: 1px dashed #ced4da;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .item-field-label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #34495e;
            font-size: 0.9em;
        }

        /* Styling untuk flex container untuk label dan input */
        .item-col {
            display: flex;
            flex-direction: column;
            flex: 1; /* Agar kolom mengisi ruang yang tersedia */
        }

        .item-col.qty {
            flex: 0 0 100px; /* Lebar tetap untuk kuantitas */
        }
        .item-col.remove {
             flex: 0 0 60px; /* Lebar tetap untuk tombol hapus */
             display: flex;
             align-items: flex-end;
             justify-content: flex-end;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Pesanan #{{ $pesanan->id }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form untuk mengedit pesanan --}}
        <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Penting: Menggunakan metode PUT untuk update --}}

            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan:</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}" required>
            </div>

            <div class="form-group">
                <label for="telepon_pelanggan">Nomor Telepon Pelanggan:</label>
                <input type="text" id="telepon_pelanggan" name="telepon_pelanggan" value="{{ old('telepon_pelanggan', $pesanan->telepon_pelanggan) }}" required>
            </div>

            <div class="form-group">
                <label for="tanggal_pengiriman">Tanggal Pengiriman:</label>
                {{-- Menggunakan format YYYY-MM-DD untuk input type="date" --}}
                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman', \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="waktu_pengiriman">Waktu Pengiriman: <small>(Contoh: 10:00 WIB / Pagi)</small></label>
                <input type="text" id="waktu_pengiriman" name="waktu_pengiriman" value="{{ old('waktu_pengiriman', $pesanan->waktu_pengiriman) }}" placeholder="Contoh: 10:00 WIB / Pagi">
            </div>

            <div class="form-group">
                <label for="alamat_pengiriman">Alamat Pengiriman:</label>
                <textarea id="alamat_pengiriman" name="alamat_pengiriman" required>{{ old('alamat_pengiriman', $pesanan->alamat_pengiriman) }}</textarea>
            </div>

            <div class="form-group">
                <label for="catatan_khusus">Catatan Khusus:</label>
                <textarea id="catatan_khusus" name="catatan_khusus">{{ old('catatan_khusus', $pesanan->catatan_khusus) }}</textarea>
            </div>

            {{-- Bagian Item Pesanan --}}
            <div class="form-group">
                <label>Item Pesanan:</label>
                <div id="item-fields-container" class="item-fields-container">
                    @forelse ($pesanan->itemPesanans as $index => $item)
                        <div class="item-row">
                            <div class="item-col">
                                <label for="produk_id_{{ $index }}" class="item-field-label">Produk:</label>
                                <select name="items[{{ $index }}][produk_id]" id="produk_id_{{ $index }}" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" {{ old('items.' . $index . '.produk_id', $item->produk_id) == $produk->id ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}/porsi)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="item-col qty">
                                <label for="jumlah_{{ $index }}" class="item-field-label">Jumlah Porsi:</label>
                                <input type="number" name="items[{{ $index }}][jumlah]" id="jumlah_{{ $index }}" value="{{ old('items.' . $index . '.jumlah', $item->jumlah_porsi) }}" min="1" required>
                            </div>
                            <div class="item-col remove">
                                <button type="button" class="btn-remove-item">Hapus</button>
                            </div>
                        </div>
                    @empty
                        {{-- Jika tidak ada item pesanan, tampilkan satu baris kosong --}}
                        <div class="item-row">
                            <div class="item-col">
                                <label for="produk_id_0" class="item-field-label">Produk:</label>
                                <select name="items[0][produk_id]" id="produk_id_0" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}/porsi)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="item-col qty">
                                <label for="jumlah_0" class="item-field-label">Jumlah Porsi:</label>
                                <input type="number" name="items[0][jumlah]" id="jumlah_0" value="1" min="1" required>
                            </div>
                            <div class="item-col remove">
                                <button type="button" class="btn-remove-item">Hapus</button>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button type="button" id="add-item-btn" class="btn-add-item">Tambah Item Lain</button>
            </div>

            <button type="submit" class="btn-submit">Update Pesanan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemFieldsContainer = document.getElementById('item-fields-container');
            const addItemBtn = document.getElementById('add-item-btn');
            let itemIndex = {{ $pesanan->itemPesanans->count() > 0 ? $pesanan->itemPesanans->count() : 0 }}; // Start index from existing items count

            // Function to add a new item row
            function addItemRow() {
                const newItemRow = document.createElement('div');
                newItemRow.classList.add('item-row');
                newItemRow.innerHTML = `
                    <div class="item-col">
                        <label for="produk_id_${itemIndex}" class="item-field-label">Produk:</label>
                        <select name="items[${itemIndex}][produk_id]" id="produk_id_${itemIndex}" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}/porsi)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="item-col qty">
                        <label for="jumlah_${itemIndex}" class="item-field-label">Jumlah Porsi:</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" id="jumlah_${itemIndex}" value="1" min="1" required>
                    </div>
                    <div class="item-col remove">
                        <button type="button" class="btn-remove-item">Hapus</button>
                    </div>
                `;
                itemFieldsContainer.appendChild(newItemRow);
                itemIndex++;
            }

            // Add event listener for "Add Item" button
            addItemBtn.addEventListener('click', addItemRow);

            // Add event listener for "Remove" buttons using event delegation
            itemFieldsContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-item')) {
                    // Prevent removing all items
                    if (itemFieldsContainer.children.length > 1) { // Ensure at least one item remains
                        event.target.closest('.item-row').remove();
                    } else {
                        alert('Pesanan harus memiliki setidaknya satu item.');
                    }
                }
            });

            // If there are no existing items and it's the first load, ensure one empty row
            if (itemFieldsContainer.children.length === 0) {
                 addItemRow(); // This will add the first empty row
            }
        });
    </script>
</body>
</html>