<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Tirta Catering</title>
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
            max-width: 600px;
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
            color: #495057;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: calc(100% - 22px); /* Adjust for padding and border */
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit, .btn-back {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            margin-right: 10px;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Produk Baru</h1>

        <form action="{{ route('admin.produks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_produk">Nama Produk:</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                @error('nama_produk')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="deskripsi_produk">Deskripsi Produk:</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk">{{ old('deskripsi_produk') }}</textarea>
                @error('deskripsi_produk')
                    <span class="error-message">{{ $message }}</span>
                @enderror 
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual:</label>
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" step="1" min="0" required>
                @error('harga')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="satuan">Satuan (cth: porsi, paket, buah):</label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}">
                @error('satuan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
            <label>Komponen Masakan:</label>
            <div id="komponen-masakan-list">
                @forelse ($komponenMasakans as $komponen)
                    <div class="komponen-item" style="margin-bottom: 10px;">
                        <input type="checkbox"
                            id="komponen_{{ $komponen->id }}"
                            name="komponen_masakan[{{ $komponen->id }}][id]"
                            value="{{ $komponen->id }}"
                            onchange="toggleJumlahInput(this)">
                        <label for="komponen_{{ $komponen->id }}">{{ $komponen->nama_komponen }} ({{ $komponen->satuan_dasar ?? 'unit' }})</label>
                        <input type="number"
                            id="jumlah_{{ $komponen->id }}"
                            name="komponen_masakan[{{ $komponen->id }}][jumlah]"
                            placeholder="Jumlah per porsi produk"
                            min="1"
                            value="{{ old('komponen_masakan.'.$komponen->id.'.jumlah', 1) }}"
                            style="width: 150px; margin-left: 10px; display: none;">
                        @error('komponen_masakan.'.$komponen->id.'.jumlah')
                            <span class="error-message" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                @empty
                    <p>Belum ada komponen masakan yang terdaftar. Silakan <a href="{{ route('admin.komponen-masakan.create') }}">tambah di sini</a>.</p>
                @endforelse
            </div>
            @error('komponen_masakan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Script untuk toggle input jumlah (di letakkan sebelum tag penutup </body> atau di bawah script utama jika ada) --}}
        <script>
            function toggleJumlahInput(checkbox) {
                const jumlahInput = document.getElementById('jumlah_' + checkbox.value);
                if (jumlahInput) {
                    jumlahInput.style.display = checkbox.checked ? 'inline-block' : 'none';
                    if (!checkbox.checked) {
                        jumlahInput.value = ''; // Clear value if unchecked
                    } else {
                        if (jumlahInput.value === '') { // Set default to 1 if checked and empty
                            jumlahInput.value = 1;
                        }
                    }
                }
            }

            // Jalankan saat halaman dimuat untuk memeriksa old() values
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('#komponen-masakan-list input[type="checkbox"]').forEach(checkbox => {
                    const jumlahInput = document.getElementById('jumlah_' + checkbox.value);
                    if (jumlahInput) {
                        // Check if old value exists and is not empty for this component
                        const inputName = `komponen_masakan[${checkbox.value}][jumlah]`;
                        // Check old values from validation errors
                        const oldKomponenData = @json(old('komponen_masakan', []));
                        const currentOldValue = oldKomponenData[checkbox.value]?.jumlah;

                        if (currentOldValue !== undefined && currentOldValue !== null) {
                            checkbox.checked = true;
                            jumlahInput.style.display = 'inline-block';
                            jumlahInput.value = currentOldValue;
                        } else if (checkbox.checked) { // If somehow pre-checked (e.g. by browser autocomplete)
                            jumlahInput.style.display = 'inline-block';
                        }
                    }
                });
            });
        </script>
            <div>
                <button type="submit" class="btn-submit">Simpan</button>
                <a href="{{ route('admin.produks.index') }}" class="btn-back">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>