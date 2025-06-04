{{-- resources/views/admin/create_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Pesanan Baru')

@push('styles')
<style>
    /* Gaya CSS yang sebelumnya ada di <style> tag di file asli dipindahkan ke sini */
    /* Gaya CSS Dasar */
    /* ... (Salin semua CSS dari file asli Anda ke sini) ... */
    /* Contoh: */
    .container-content { /* Jika Anda menggunakan ini sebagai wrapper utama konten */
        /* background-color: #ffffff; (sudah ada di layout jika pakai .container-content) */
        /* padding: 30px; (sudah ada di layout jika pakai .container-content) */
        /* border-radius: 12px; (sudah ada di layout jika pakai .container-content) */
        /* box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); (sudah ada di layout jika pakai .container-content) */
        max-width: 800px; /* Spesifik untuk halaman ini */
        margin: 0 auto; /* Tengahkan jika max-width lebih kecil dari layout */
    }
    /* Gaya untuk Judul Utama di dalam konten */
    .content-header h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
        font-size: 2.2em;
        font-weight: 700;
    }
    label {
        display: block; margin-top: 15px; margin-bottom: 5px;
        font-weight: 600; color: #34495e; font-size: 0.95em;
    }
    input[type="text"], input[type="date"], input[type="tel"],
    input[type="number"], textarea, select {
        width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ced4da;
        border-radius: 8px; box-sizing: border-box; font-size: 1em; color: #495057;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    input[type="text"]:focus, input[type="date"]:focus, input[type="tel"]:focus,
    input[type="number"]:focus, textarea:focus, select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
    textarea { resize: vertical; min-height: 100px; }
    .form-section {
        border: 1px solid #e9ecef; padding: 20px; margin-bottom: 25px;
        border-radius: 10px; background-color: #fdfdfd;
    }
    .form-section h2 {
        color: #34495e; margin-top: 0; margin-bottom: 15px; font-size: 1.5em;
        border-bottom: 2px solid #e9ecef; padding-bottom: 10px;
    }
    .item-row {
        display: flex; gap: 15px; margin-bottom: 15px; align-items: flex-end;
        background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0;
    }
    .item-row .col { flex: 1; }
    .item-row .col.product-select { flex: 3; }
    .item-row .col.quantity-input { flex: 1; }
    .item-row .col.action-button { flex: 0 0 auto; align-self: flex-end; }
    
    .btn { /* Tombol umum sudah ada di layout, ini bisa disesuaikan/dihapus jika sama */
        /* background-color: #28a745; color: white; padding: 12px 25px; */
        /* ... (ambil dari layout atau sesuaikan) */
    }
    .btn-danger { /* Tombol hapus item */
        background-color: #dc3545; color: white; padding: 10px 15px;
        font-size: 0.9em; margin-top: 0;
    }
    .btn-danger:hover { background-color: #c82333; }

    .alert { /* Sudah ada di layout, tidak perlu duplikasi jika sama */ }
    .alert-success { /* Sudah ada di layout */ }
    .alert-danger { /* Sudah ada di layout */ }
    .alert-danger ul { margin: 0; padding-left: 20px; }

    @media (max-width: 768px) {
        .container-content { padding: 20px; margin: 10px auto; }
        .content-header h1 { font-size: 1.8em; }
        /* .btn { width: 100%; margin-left: 0; margin-bottom: 10px; }  Sudah ditangani layout jika global */
        .item-row { flex-direction: column; align-items: stretch; }
        .item-row .col { flex: none; width: 100%; margin-bottom: 10px; }
        .item-row .col:last-child { margin-bottom: 0; }
        .item-row .col.action-button { text-align: right; }
    }
</style>
@endpush

@section('content')
    <div class="container-content"> {{-- Menggunakan class ini untuk styling spesifik form jika perlu --}}
        <div class="content-header">
            <h1>Tambah Pesanan Baru</h1>
        </div>

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
                <button type="button" class="btn btn-primary" id="add-item-btn" style="margin-bottom: 15px;">Tambah Item Menu</button>
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

            <button type="submit" class="btn btn-success">Simpan Pesanan</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('admin.pesanan.index') }}'">Batal</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success" style="margin-top: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any() && !$errors->hasAny(['nama_pelanggan', 'telepon_pelanggan', 'items.*', 'tanggal_pengiriman', 'alamat_pengiriman', 'waktu_pengiriman', 'catatan_khusus']))
            <div class="alert alert-danger" style="margin-top: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Log untuk melacak eksekusi skrip
    console.log("Skrip JavaScript dimuat untuk create_pesanan.");

    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM Content Loaded event fired untuk create_pesanan.");

        const addItemBtn = document.getElementById('add-item-btn');
        const itemContainer = document.getElementById('item-list-container');
        const totalPriceDisplay = document.getElementById('total-price-display');

        console.log("Element add-item-btn:", addItemBtn);
        console.log("Element item-list-container:", itemContainer);
        console.log("Element total-price-display:", totalPriceDisplay);

        let itemIndex = 0;
        if (itemContainer) {
            const initialItems = itemContainer.querySelectorAll('.item-row');
            itemIndex = initialItems.length;
        } else {
            console.error("item-list-container tidak ditemukan!");
            return; 
        }

        function calculateTotalPrice() {
            let currentTotal = 0;
            const currentItems = itemContainer.querySelectorAll('.item-row');
            currentItems.forEach(row => {
                const produkSelect = row.querySelector('select[name$="[produk_id]"]');
                const jumlahInput = row.querySelector('input[name$="[jumlah]"]');
                if (produkSelect && jumlahInput && produkSelect.value) {
                    const selectedOption = produkSelect.options[produkSelect.selectedIndex];
                    const hargaJual = parseFloat(selectedOption.dataset.price || 0);
                    const jumlah = parseInt(jumlahInput.value || 0);
                    if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                        currentTotal += (hargaJual * jumlah);
                    }
                }
            });
            totalPriceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(currentTotal)}`;
            console.log("Total harga diupdate:", currentTotal);
        }

        function createItemRow(index, selectedProductId = null, quantity = 1) {
            console.log(`Membuat baris item baru dengan index: ${index}`);
            const produksData = @json($produks);
            let productOptions = '<option value="">Pilih Produk</option>'; // Selalu tambahkan opsi default
            produksData.forEach(produk => {
                const selected = (selectedProductId == produk.id) ? 'selected' : '';
                productOptions += `<option value="${produk.id}" ${selected} data-price="${produk.harga_jual}">${produk.nama_produk} (Rp ${new Intl.NumberFormat('id-ID').format(produk.harga_jual)})</option>`;
            });

            const itemRow = document.createElement('div');
            itemRow.classList.add('item-row');
            itemRow.setAttribute('data-id', index);
            itemRow.innerHTML = `
                <div class="col product-select">
                    <label for="items_${index}_produk_id">Produk:</label>
                    <select name="items[${index}][produk_id]" id="items_${index}_produk_id" required>
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

            const removeBtn = itemRow.querySelector('.remove-item-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    console.log(`Tombol hapus di baris ${index} diklik.`);
                    itemRow.remove(); 
                    updateRemoveButtonsVisibility();
                    calculateTotalPrice();
                });
            }

            const newProdukSelect = itemRow.querySelector(`#items_${index}_produk_id`);
            const newJumlahInput = itemRow.querySelector(`#items_${index}_jumlah`);

            if (newProdukSelect) newProdukSelect.addEventListener('change', calculateTotalPrice);
            if (newJumlahInput) newJumlahInput.addEventListener('input', calculateTotalPrice);

            return itemRow;
        }

        function updateRemoveButtonsVisibility() {
            const currentItems = itemContainer.querySelectorAll('.item-row');
            console.log(`Jumlah item saat ini: ${currentItems.length}`);
            currentItems.forEach((row) => {
                const removeBtn = row.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.style.display = (currentItems.length > 1) ? 'inline-block' : 'none';
                }
            });
        }

        if (addItemBtn) {
            addItemBtn.addEventListener('click', function() {
                console.log("Tombol 'Tambah Item Menu' diklik! Menambah baris baru.");
                const newItemRow = createItemRow(itemIndex);
                itemContainer.appendChild(newItemRow);
                itemIndex++;
                updateRemoveButtonsVisibility();
                calculateTotalPrice(); 
            });
        } else {
            console.warn("Tombol 'Tambah Item Menu' (ID: add-item-btn) tidak ditemukan.");
        }

        updateRemoveButtonsVisibility();

        itemContainer.querySelectorAll('.item-row').forEach(row => {
            const removeBtn = row.querySelector('.remove-item-btn');
            if(removeBtn && !removeBtn.dataset.listenerAdded){
                removeBtn.addEventListener('click', function() {
                    this.closest('.item-row').remove();
                    updateRemoveButtonsVisibility();
                    calculateTotalPrice();
                });
                removeBtn.dataset.listenerAdded = true;
            }

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

        @if(old('items'))
            itemIndex = {{ count(old('items')) }};
            console.log(`itemIndex diinisialisasi ulang berdasarkan old input: ${itemIndex}`);
        @elseif(itemContainer.querySelectorAll('.item-row').length === 0 && itemIndex === 0)
            // Jika tidak ada old item dan tidak ada item awal dari Blade (misalnya, jika @else tidak ada),
            // maka kita bisa tambahkan satu baris awal. Tapi karena @else sudah ada, ini mungkin tidak perlu.
            // const firstItemRow = createItemRow(itemIndex);
            // itemContainer.appendChild(firstItemRow);
            // itemIndex++;
            // updateRemoveButtonsVisibility();
        @endif

        calculateTotalPrice();
    });
</script>
@endpush