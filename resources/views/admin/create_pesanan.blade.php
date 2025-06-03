<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan Baru - Tirta Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Gaya CSS Dasar */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Gaya untuk Container Utama Form */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
            box-sizing: border-box;
        }

        /* Gaya untuk Judul Utama */
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.2em;
            font-weight: 700;
        }

        /* Gaya untuk Label Form */
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #34495e;
            font-size: 0.95em;
        }

        /* Gaya untuk Input Teks, Tanggal, Telepon, Number, Textarea, dan Select */
        input[type="text"],
        input[type="date"],
        input[type="tel"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            color: #495057;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Gaya untuk Setiap Bagian Form (Informasi Pelanggan, Detail Pesanan, dll.) */
        .form-section {
            border: 1px solid #e9ecef;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            background-color: #fdfdfd;
        }

        .form-section h2 {
            color: #34495e;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.5em;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        /* Gaya untuk Daftar Item Menu Dinamis */
        .item-row {
            display: flex;
            gap: 15px; /* Jarak antar kolom */
            margin-bottom: 15px;
            align-items: flex-end; /* Menyelaraskan item ke bawah */
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .item-row .col {
            flex: 1; /* Setiap kolom mengambil ruang yang sama */
        }

        .item-row .col.product-select {
            flex: 3; /* Kolom produk sedikit lebih besar */
        }

        .item-row .col.quantity-input {
            flex: 1; /* Kolom kuantitas standar */
        }

        .item-row .col.action-button {
            flex: 0 0 auto; /* Kolom tombol mengambil ruang sesuai kontennya */
            align-self: flex-end; /* Menyelaraskan tombol ke bawah */
        }

        /* Gaya untuk Tombol-tombol */
        .btn {
            background-color: #28a745; /* Warna hijau untuk tombol utama */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            margin-top: 25px;
            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
        }

        .btn:hover {
            background-color: #218838; /* Warna hijau lebih gelap saat hover */
            transform: translateY(-2px); /* Efek angkat saat hover */
        }

        .btn-secondary {
            background-color: #6c757d; /* Warna abu-abu untuk tombol sekunder */
            margin-left: 15px;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545; /* Merah untuk tombol hapus */
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
            padding: 10px 15px; /* Sedikit lebih kecil dari tombol utama */
            font-size: 0.9em;
            margin-top: 0; /* Sesuaikan margin-top jika di dalam item-row */
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Gaya untuk Pesan Sukses/Error */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
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

        /* Responsif untuk Layar Kecil */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 1.8em;
            }
            .btn {
                width: 100%;
                margin-left: 0;
                margin-bottom: 10px;
            }
            .btn-secondary {
                margin-left: 0;
            }
            .item-row {
                flex-direction: column; /* Ubah ke kolom untuk layar kecil */
                align-items: stretch; /* Rentangkan item */
            }
            .item-row .col {
                flex: none; /* Nonaktifkan flex untuk kolom */
                width: 100%; /* Lebar penuh */
                margin-bottom: 10px; /* Tambahkan margin antar kolom */
            }
            .item-row .col:last-child {
                margin-bottom: 0;
            }
            .item-row .col.action-button {
                text-align: right; /* Tombol hapus di kanan bawah */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Pesanan Baru</h1>

        <form action="{{ route('admin.pesanan.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h2>Informasi Pelanggan</h2>
                <label for="nama_pelanggan">Nama Pelanggan:</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required>
                @error('nama_pelanggan') <div class="alert alert-danger">{{ $message }}</div> @enderror

                <label for="telepon_pelanggan">Nomor Telepon:</label>
                <input type="tel" id="telepon_pelanggan" name="telepon_pelanggan" value="{{ old('telepon_pelanggan') }}" required>
                @error('telepon_pelanggan') <div class="alert alert-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-section">
                <h2>Detail Pesanan Menu</h2>
                <button type="button" class="btn" id="add-item-btn">Tambah Item Menu</button>
                <div id="item-list-container" class="item-list">
                    @if(old('items'))
                        @foreach(old('items') as $index => $oldItem)
                            <div class="item-row" data-id="{{ $index }}">
                                <div class="col product-select">
                                    <label for="items_{{ $index }}_produk_id">Produk:</label>
                                    <select name="items[{{ $index }}][produk_id]" id="items_{{ $index }}_produk_id" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($produks as $produk)
                                            <option value="{{ $produk->id }}" {{ (isset($oldItem['produk_id']) && $oldItem['produk_id'] == $produk->id) ? 'selected' : '' }} data-price="{{ $produk->harga_jual }}">
                                                {{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('items.'.$index.'.produk_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col quantity-input">
                                    <label for="items_{{ $index }}_jumlah">Jumlah Porsi:</label>
                                    <input type="number" id="items_{{ $index }}_jumlah" name="items[{{ $index }}][jumlah]" value="{{ isset($oldItem['jumlah']) ? $oldItem['jumlah'] : 1 }}" min="1" required>
                                    @error('items.'.$index.'.jumlah') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col action-button">
                                    <button type="button" class="btn btn-danger remove-item-btn">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="item-row" data-id="0">
                            <div class="col product-select">
                                <label for="items_0_produk_id">Produk:</label>
                                <select name="items[0][produk_id]" id="items_0_produk_id" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $produk)
                                        <option value="{{ $produk->id }}" data-price="{{ $produk->harga_jual }}">
                                            {{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col quantity-input">
                                <label for="items_0_jumlah">Jumlah Porsi:</label>
                                <input type="number" id="items_0_jumlah" name="items[0][jumlah]" value="1" min="1" required>
                            </div>
                            <div class="col action-button">
                                {{-- Tombol hapus tidak muncul untuk baris pertama jika itu satu-satunya --}}
                                <button type="button" class="btn btn-danger remove-item-btn" style="display: none;">Hapus</button>
                            </div>
                        </div>
                    @endif
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <h3 style="color: #2c3e50; font-size: 1.5em;">Total Harga Pesanan: <span id="total-price-display" style="color: #28a745; font-weight: 700;">Rp 0</span></h3>
                </div>
            </div>

            <div class="form-section">
                <h2>Detail Pengiriman</h2>
                <label for="tanggal_pengiriman">Tanggal Pengiriman:</label>
                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman') }}" required>
                @error('tanggal_pengiriman') <div class="alert alert-danger">{{ $message }}</div> @enderror

                <label for="alamat_pengiriman">Alamat Pengiriman:</label>
                <textarea id="alamat_pengiriman" name="alamat_pengiriman" required>{{ old('alamat_pengiriman') }}</textarea>
                @error('alamat_pengiriman') <div class="alert alert-danger">{{ $message }}</div> @enderror

                <label for="waktu_pengiriman">Waktu Pengiriman (Opsional):</label>
                <input type="text" id="waktu_pengiriman" name="waktu_pengiriman" value="{{ old('waktu_pengiriman') }}" placeholder="Contoh: 10:00 WIB">
                @error('waktu_pengiriman') <div class="alert alert-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-section">
                <h2>Catatan Khusus</h2>
                <label for="catatan_khusus">Catatan Khusus:</label>
                <textarea id="catatan_khusus" name="catatan_khusus">{{ old('catatan_khusus') }}</textarea>
                @error('catatan_khusus') <div class="alert alert-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn">Simpan Pesanan</button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
        </form>

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
    </div>

    <script>
        // Log untuk melacak eksekusi skrip
        console.log("Skrip JavaScript dimuat.");

        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM Content Loaded event fired.");

            // Ambil referensi elemen-elemen kunci dari DOM
            const addItemBtn = document.getElementById('add-item-btn');
            const itemContainer = document.getElementById('item-list-container');
            const totalPriceDisplay = document.getElementById('total-price-display');

            // Log untuk memverifikasi apakah elemen-elemen ditemukan
            console.log("Element add-item-btn:", addItemBtn);
            console.log("Element item-list-container:", itemContainer);
            console.log("Element total-price-display:", totalPriceDisplay);

            // Inisialisasi itemIndex. Ini penting untuk memberikan nama unik pada input baru (items[0], items[1], dst.)
            let itemIndex = 0;
            if (itemContainer) {
                // Jika ada item yang sudah dirender oleh Blade (misal dari old input atau item default pertama),
                // kita mulai itemIndex dari jumlah tersebut.
                const initialItems = itemContainer.querySelectorAll('.item-row');
                itemIndex = initialItems.length;
            } else {
                console.error("item-list-container tidak ditemukan! Fungsionalitas tambah item tidak akan bekerja.");
                return; // Hentikan eksekusi jika container utama tidak ditemukan
            }

            // Fungsi untuk menghitung dan memperbarui total harga pesanan di frontend
            function calculateTotalPrice() {
                let currentTotal = 0;
                // Ambil semua baris item pesanan yang ada saat ini
                const currentItems = itemContainer.querySelectorAll('.item-row');

                currentItems.forEach(row => {
                    // Ambil elemen select produk dan input jumlah dari setiap baris
                    const produkSelect = row.querySelector('select[name$="[produk_id]"]');
                    const jumlahInput = row.querySelector('input[name$="[jumlah]"]');

                    // Pastikan elemen ditemukan dan produk sudah dipilih
                    if (produkSelect && jumlahInput && produkSelect.value) {
                        // Ambil opsi yang saat ini terpilih dari dropdown produk
                        const selectedOption = produkSelect.options[produkSelect.selectedIndex];
                        // Dapatkan harga jual dari atribut data-price pada opsi yang terpilih
                        const hargaJual = parseFloat(selectedOption.dataset.price || 0);
                        // Dapatkan jumlah porsi dari input jumlah
                        const jumlah = parseInt(jumlahInput.value || 0);

                        // Lakukan perhitungan hanya jika harga dan jumlah adalah angka valid
                        if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                            currentTotal += (hargaJual * jumlah);
                        }
                    }
                });

                // Update tampilan total harga dengan format mata uang Rupiah
                totalPriceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(currentTotal)}`;
                console.log("Total harga diupdate:", currentTotal);
            }

            // Fungsi untuk membuat elemen HTML untuk satu baris item pesanan baru
            function createItemRow(index, selectedProductId = null, quantity = 1) {
                console.log(`Membuat baris item baru dengan index: ${index}`);
                // Konversi data produk dari PHP (yang sudah dilempar via Blade) menjadi objek JavaScript
                const produksData = @json($produks);
                let productOptions = '';
                produksData.forEach(produk => {
                    // Tentukan apakah opsi ini harus dipilih secara default (untuk old input)
                    const selected = (selectedProductId == produk.id) ? 'selected' : '';
                    productOptions += `<option value="${produk.id}" ${selected} data-price="${produk.harga_jual}">${produk.nama_produk} (Rp ${new Intl.NumberFormat('id-ID').format(produk.harga_jual)})</option>`;
                });

                // Buat elemen div untuk baris item baru
                const itemRow = document.createElement('div');
                itemRow.classList.add('item-row');
                itemRow.setAttribute('data-id', index);
                itemRow.innerHTML = `
                    <div class="col product-select">
                        <label for="items_${index}_produk_id">Produk:</label>
                        <select name="items[${index}][produk_id]" id="items_${index}_produk_id" required>
                            <option value="">Pilih Produk</option>
                            ${productOptions}
                        </select>
                    </div>
                    <div class="col quantity-input">
                        <label for="items_${index}_jumlah">Jumlah Porsi:</label>
                        <input type="number" id="items_${index}_jumlah" name="items[${index}][jumlah]" value="${quantity}" min="1" required>
                    </div>
                    <div class="col action-button">
                        <button type="button" class="btn btn-danger remove-item-btn">Hapus</button>
                    </div>
                `;

                // Tambahkan event listener untuk tombol hapus pada baris yang baru dibuat
                const removeBtn = itemRow.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        console.log(`Tombol hapus di baris ${index} diklik.`);
                        itemRow.remove(); // Hapus baris dari DOM
                        updateRemoveButtonsVisibility(); // Perbarui visibilitas tombol hapus
                        calculateTotalPrice(); // Hitung ulang total harga setelah item dihapus
                    });
                }

                // Tambahkan event listener untuk perubahan pada dropdown produk dan input jumlah di baris baru
                const newProdukSelect = itemRow.querySelector(`#items_${index}_produk_id`);
                const newJumlahInput = itemRow.querySelector(`#items_${index}_jumlah`);

                if (newProdukSelect) {
                    newProdukSelect.addEventListener('change', calculateTotalPrice);
                }
                if (newJumlahInput) {
                    newJumlahInput.addEventListener('input', calculateTotalPrice); // 'input' event mendeteksi setiap perubahan
                }

                return itemRow;
            }

            // Fungsi untuk memperbarui visibilitas tombol "Hapus" pada semua baris item
            // Tombol hapus hanya akan ditampilkan jika ada lebih dari satu item menu.
            function updateRemoveButtonsVisibility() {
                const currentItems = itemContainer.querySelectorAll('.item-row');
                console.log(`Jumlah item saat ini: ${currentItems.length}`);
                currentItems.forEach((row) => {
                    const removeBtn = row.querySelector('.remove-item-btn');
                    if (removeBtn) {
                        if (currentItems.length > 1) {
                            removeBtn.style.display = 'inline-block'; // Tampilkan tombol hapus
                        } else {
                            removeBtn.style.display = 'none'; // Sembunyikan tombol hapus
                        }
                    }
                });
            }

            // Event listener utama untuk tombol "Tambah Item Menu"
            if (addItemBtn) {
                addItemBtn.addEventListener('click', function() {
                    console.log("Tombol 'Tambah Item Menu' diklik! Menambah baris baru.");
                    const newItemRow = createItemRow(itemIndex); // Buat baris baru
                    itemContainer.appendChild(newItemRow); // Tambahkan ke container
                    itemIndex++; // Tingkatkan indeks untuk item berikutnya
                    updateRemoveButtonsVisibility(); // Perbarui visibilitas tombol hapus
                    calculateTotalPrice(); // Hitung ulang total harga setelah item baru ditambahkan
                });
            } else {
                console.warn("Tombol 'Tambah Item Menu' (ID: add-item-btn) tidak ditemukan pada DOMContentLoaded.");
            }

            // Inisialisasi awal: pastikan visibilitas tombol hapus benar saat halaman dimuat
            updateRemoveButtonsVisibility();

            // Tambahkan event listener untuk tombol hapus pada item yang SUDAH ADA saat loading (dari Blade, misal karena old input)
            itemContainer.querySelectorAll('.remove-item-btn').forEach(button => {
                // Pastikan event listener hanya ditambahkan sekali untuk elemen yang sudah ada
                if (!button.dataset.listenerAdded) {
                    button.addEventListener('click', function() {
                        console.log("Tombol hapus dari item yang sudah ada diklik.");
                        this.closest('.item-row').remove(); // Hapus baris dari DOM
                        updateRemoveButtonsVisibility(); // Perbarui visibilitas tombol hapus
                        calculateTotalPrice(); // Hitung ulang total harga
                    });
                    button.dataset.listenerAdded = true; // Tandai bahwa listener sudah ditambahkan
                }
            });

            // Tambahkan event listener untuk perubahan produk atau jumlah pada item yang SUDAH ADA saat loading (dari Blade)
            itemContainer.querySelectorAll('.item-row').forEach(row => {
                const produkSelect = row.querySelector('select[name$="[produk_id]"]');
                const jumlahInput = row.querySelector('input[name$="[jumlah]"]');

                if (produkSelect && !produkSelect.dataset.listenerAdded) {
                    produkSelect.addEventListener('change', calculateTotalPrice);
                    produkSelect.dataset.listenerAdded = true;
                }
                if (jumlahInput && !jumlahInput.dataset.listenerAdded) {
                    jumlahInput.addEventListener('input', calculateTotalPrice);
                    jumlahInput.dataset.listenerAdded = true;
                }
            });

            // Jika ada old input yang dirender oleh Blade (setelah validasi gagal),
            // pastikan itemIndex diinisialisasi ulang dengan benar.
            @if(old('items'))
                itemIndex = {{ count(old('items')) }};
                console.log(`itemIndex diinisialisasi ulang berdasarkan old input: ${itemIndex}`);
            @endif

            // Panggil calculateTotalPrice() pertama kali saat DOMContentLoaded untuk menghitung total awal
            calculateTotalPrice();
        });
    </script>
</body>
</html>