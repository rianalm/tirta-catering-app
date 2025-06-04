{{-- resources/views/admin/edit_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Pesanan #' . $pesanan->id)

@push('styles')
<style>
    /* Gaya CSS yang sebelumnya ada di <style> tag di file asli dipindahkan ke sini */
    /* ... (Salin semua CSS dari file asli Anda ke sini) ... */
    .container-content { max-width: 800px; margin: 0 auto; }
    .content-header h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2.2em; font-weight: 700; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #34495e; }
    .form-group input[type="text"], .form-group input[type="date"],
    .form-group input[type="number"], .form-group textarea, .form-group select {
        width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box; transition: border-color 0.2s;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: #007bff; outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    textarea { resize: vertical; min-height: 80px; }
    
    .btn-submit { /* Sudah ada di layout, bisa disesuaikan */ }
    .alert { /* Sudah ada di layout */ }
    .item-row {
        display: flex; gap: 15px; margin-bottom: 15px; align-items: flex-end;
        border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; background-color: #fcfcfc;
    }
    .item-row select, .item-row input[type="number"] { flex-grow: 1; }
    .btn-remove-item {
        background-color: #dc3545; color: white; padding: 10px 15px; border: none;
        border-radius: 8px; cursor: pointer; font-size: 0.9em; transition: background-color 0.2s;
    }
    .btn-remove-item:hover { background-color: #c82333; }
    .btn-add-item {
        background-color: #007bff; color: white; padding: 10px 15px; border: none;
        border-radius: 8px; cursor: pointer; font-size: 0.9em;
        transition: background-color 0.2s; margin-top: 10px; margin-bottom: 15px;
    }
    .btn-add-item:hover { background-color: #0056b3; }
    .item-fields-container {
        border: 1px dashed #ced4da; padding: 20px; border-radius: 8px; margin-bottom: 20px;
    }
    .item-field-label { font-weight: 600; margin-bottom: 5px; display: block; color: #34495e; font-size: 0.9em; }
    .item-col { display: flex; flex-direction: column; flex: 1; }
    .item-col.qty { flex: 0 0 100px; }
    .item-col.remove { flex: 0 0 auto; display: flex; align-items: flex-end; justify-content: flex-end; } /* Disesuaikan agar tombol pas */
    #total-price-display-edit { color: #28a745; font-weight: 700; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Edit Pesanan #{{ $pesanan->id }}</h1>
        </div>

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

        <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT')

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

            <div class="form-group">
                <label>Item Pesanan:</label>
                <div id="item-fields-container" class="item-fields-container">
                    @php $itemPesanans = old('items', $pesanan->itemPesanans->toArray()); @endphp
                    @if (!empty($itemPesanans))
                        @foreach ($itemPesanans as $index => $item)
                            <div class="item-row">
                                <div class="item-col">
                                    <label for="produk_id_{{ $index }}" class="item-field-label">Produk:</label>
                                    <select name="items[{{ $index }}][produk_id]" id="produk_id_{{ $index }}" required class="produk-select-edit">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($produks as $produk)
                                            <option value="{{ $produk->id }}" 
                                                    data-price="{{ $produk->harga_jual }}"
                                                    {{ (isset($item['produk_id']) && $item['produk_id'] == $produk->id) ? 'selected' : '' }}>
                                                {{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                     @error('items.'.$index.'.produk_id') <div class="alert alert-danger" style="font-size: 0.8em; padding: 5px; margin-top:2px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="item-col qty">
                                    <label for="jumlah_{{ $index }}" class="item-field-label">Jumlah Porsi:</label>
                                    <input type="number" name="items[{{ $index }}][jumlah]" id="jumlah_{{ $index }}" 
                                           value="{{ $item['jumlah_porsi'] ?? ($item['jumlah'] ?? 1) }}" 
                                           min="1" required class="jumlah-input-edit">
                                    @error('items.'.$index.'.jumlah') <div class="alert alert-danger" style="font-size: 0.8em; padding: 5px; margin-top:2px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="item-col remove">
                                    <button type="button" class="btn-remove-item btn btn-danger btn-sm">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                         {{-- Render satu baris kosong jika tidak ada old item dan tidak ada item pesanan --}}
                        <div class="item-row">
                            <div class="item-col">
                                <label for="produk_id_0" class="item-field-label">Produk:</label>
                                <select name="items[0][produk_id]" id="produk_id_0" required class="produk-select-edit">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" data-price="{{ $produk->harga_jual }}">{{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_jual, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="item-col qty">
                                <label for="jumlah_0" class="item-field-label">Jumlah Porsi:</label>
                                <input type="number" name="items[0][jumlah]" id="jumlah_0" value="1" min="1" required class="jumlah-input-edit">
                            </div>
                            <div class="item-col remove">
                                <button type="button" class="btn-remove-item btn btn-danger btn-sm" style="display: none;">Hapus</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" id="add-item-btn" class="btn-add-item btn btn-primary">Tambah Item Lain</button>
                
                <div style="margin-top: 20px; text-align: right;">
                    <h3 style="color: #2c3e50; font-size: 1.5em;">Total Estimasi Harga: <span id="total-price-display-edit">Rp {{ number_format(old('total_harga', $pesanan->total_harga),0,',','.') }}</span></h3>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update Pesanan</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemFieldsContainer = document.getElementById('item-fields-container');
    const addItemBtn = document.getElementById('add-item-btn');
    const totalPriceDisplayEdit = document.getElementById('total-price-display-edit');
    
    // Ambil data produk untuk dropdown
    const produksData = @json($produks);

    // Inisialisasi itemIndex berdasarkan jumlah item yang sudah ada (dari old input atau database)
    let itemIndex = itemFieldsContainer.querySelectorAll('.item-row').length;

    function calculateTotalPriceEdit() {
        let currentTotal = 0;
        itemFieldsContainer.querySelectorAll('.item-row').forEach(row => {
            const produkSelect = row.querySelector('.produk-select-edit');
            const jumlahInput = row.querySelector('.jumlah-input-edit');
            if (produkSelect && jumlahInput && produkSelect.value) {
                const selectedOption = produkSelect.options[produkSelect.selectedIndex];
                const hargaJual = parseFloat(selectedOption.dataset.price || 0);
                const jumlah = parseInt(jumlahInput.value || 0);
                if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                    currentTotal += (hargaJual * jumlah);
                }
            }
        });
        if(totalPriceDisplayEdit) {
            totalPriceDisplayEdit.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(currentTotal)}`;
        }
    }
    
    function createNewItemRow(idx) {
        const newItemRow = document.createElement('div');
        newItemRow.classList.add('item-row');
        
        let productOptions = '<option value="">Pilih Produk</option>';
        produksData.forEach(produk => {
            productOptions += `<option value="${produk.id}" data-price="${produk.harga_jual}">${produk.nama_produk} (Rp ${new Intl.NumberFormat('id-ID').format(produk.harga_jual)})</option>`;
        });

        newItemRow.innerHTML = `
            <div class="item-col">
                <label for="produk_id_${idx}" class="item-field-label">Produk:</label>
                <select name="items[${idx}][produk_id]" id="produk_id_${idx}" required class="produk-select-edit">
                    ${productOptions}
                </select>
            </div>
            <div class="item-col qty">
                <label for="jumlah_${idx}" class="item-field-label">Jumlah Porsi:</label>
                <input type="number" name="items[${idx}][jumlah]" id="jumlah_${idx}" value="1" min="1" required class="jumlah-input-edit">
            </div>
            <div class="item-col remove">
                <button type="button" class="btn-remove-item btn btn-danger btn-sm">Hapus</button>
            </div>
        `;
        attachEventListenersToRow(newItemRow);
        return newItemRow;
    }

    function attachEventListenersToRow(rowElement) {
        const removeBtn = rowElement.querySelector('.btn-remove-item');
        const produkSelect = rowElement.querySelector('.produk-select-edit');
        const jumlahInput = rowElement.querySelector('.jumlah-input-edit');

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (itemFieldsContainer.children.length > 1) {
                    rowElement.remove();
                    calculateTotalPriceEdit();
                    updateRemoveButtonsVisibilityEdit();
                } else {
                    alert('Pesanan harus memiliki setidaknya satu item.');
                }
            });
        }
        if (produkSelect) produkSelect.addEventListener('change', calculateTotalPriceEdit);
        if (jumlahInput) jumlahInput.addEventListener('input', calculateTotalPriceEdit);
    }
    
    function updateRemoveButtonsVisibilityEdit() {
        const currentItems = itemFieldsContainer.querySelectorAll('.item-row');
        currentItems.forEach((row, index) => {
            const removeBtn = row.querySelector('.btn-remove-item');
            if (removeBtn) {
                removeBtn.style.display = (currentItems.length > 1) ? 'inline-block' : 'none';
            }
        });
    }

    if (addItemBtn) {
        addItemBtn.addEventListener('click', function() {
            const newItem = createNewItemRow(itemIndex);
            itemFieldsContainer.appendChild(newItem);
            itemIndex++;
            updateRemoveButtonsVisibilityEdit();
            calculateTotalPriceEdit(); // Hitung setelah tambah item baru
        });
    }

    // Attach event listeners to existing rows
    itemFieldsContainer.querySelectorAll('.item-row').forEach(row => {
        attachEventListenersToRow(row);
    });

    // Initial calculation and button visibility
    if(itemFieldsContainer.children.length > 0){
        calculateTotalPriceEdit();
        updateRemoveButtonsVisibilityEdit();
    } else if (itemFieldsContainer.children.length === 0 && itemIndex === 0){ // Jika tidak ada item sama sekali, tambahkan satu baris
        const firstItem = createNewItemRow(itemIndex);
        itemFieldsContainer.appendChild(firstItem);
        itemIndex++;
        updateRemoveButtonsVisibilityEdit();
        calculateTotalPriceEdit();
    }
});
</script>
@endpush