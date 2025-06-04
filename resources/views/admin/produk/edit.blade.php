{{-- resources/views/admin/produk/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $produk->nama_produk)

@push('styles')
<style>
    /* Gaya CSS spesifik untuk form ini jika ada */
    .container-content { max-width: 700px; margin: 0 auto; }
    .content-header h1 { text-align: center; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea {
        width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group textarea:focus {
        border-color: #007bff; outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    textarea { resize: vertical; min-height: 80px; }
    .komponen-item {
        display: flex; align-items: center; gap:10px; margin-bottom: 10px;
        padding: 10px; border: 1px solid #eee; border-radius: 6px;
    }
    .komponen-item label { margin-bottom: 0; }
    .komponen-item input[type="number"] { width: 150px; margin-left: auto; }
    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; display: block; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Edit Produk: {{ $produk->nama_produk }}</h1>
        </div>

        <form action="{{ route('admin.produks.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_produk">Nama Produk:</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                @error('nama_produk')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="deskripsi_produk">Deskripsi Produk:</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk">{{ old('deskripsi_produk', $produk->deskripsi_produk) }}</textarea>
                @error('deskripsi_produk')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual:</label>
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" step="0.01" min="0" required>
                @error('harga_jual')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="satuan">Satuan (cth: porsi, paket, buah):</label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan', $produk->satuan) }}">
                @error('satuan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Komponen Masakan:</label>
                <div id="komponen-masakan-list" style="border: 1px solid #ddd; padding:15px; border-radius:8px;">
                    @forelse ($komponenMasakans as $komponen)
                        @php
                            // Cek apakah komponen ini ada di old input
                            $isOldChecked = old('komponen_masakan.'.$komponen->id.'.id') !== null;
                            // Jika tidak ada di old input, cek apakah terhubung dengan produk
                            $isChecked = $isOldChecked || (old('komponen_masakan') === null && isset($produkKomponen[$komponen->id]));
                            // Ambil nilai jumlah: prioritas old -> database -> default 1
                            $jumlahValue = old('komponen_masakan.'.$komponen->id.'.jumlah', $produkKomponen[$komponen->id] ?? 1);
                        @endphp
                        <div class="komponen-item">
                            <input type="checkbox"
                                id="komponen_{{ $komponen->id }}"
                                name="komponen_masakan[{{ $komponen->id }}][id]"
                                value="{{ $komponen->id }}"
                                onchange="toggleJumlahInput(this)"
                                {{ $isChecked ? 'checked' : '' }}>
                            <label for="komponen_{{ $komponen->id }}" style="flex-grow:1;">{{ $komponen->nama_komponen }} ({{ $komponen->satuan_dasar ?? 'unit' }})</label>
                            <input type="number"
                                id="jumlah_{{ $komponen->id }}"
                                name="komponen_masakan[{{ $komponen->id }}][jumlah]"
                                placeholder="Jumlah"
                                min="1" step="any"
                                value="{{ $jumlahValue }}"
                                style="display: {{ $isChecked ? 'inline-block' : 'none' }};">
                             @error('komponen_masakan.'.$komponen->id.'.jumlah')
                                <span class="error-message" style="display: block; width:100%; text-align:right;">{{ $message }}</span>
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

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.produks.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function toggleJumlahInput(checkbox) {
        const jumlahInput = document.getElementById('jumlah_' + checkbox.value);
        if (jumlahInput) {
            jumlahInput.style.display = checkbox.checked ? 'inline-block' : 'none';
            if (!checkbox.checked) {
                jumlahInput.value = '';
            } else {
                 if (jumlahInput.value === '' || parseFloat(jumlahInput.value) <= 0) {
                    jumlahInput.value = 1;
                }
            }
        }
    }

    // Tidak perlu inisialisasi DOMContentLoaded yang kompleks seperti di create,
    // karena Blade sudah menangani state awal (checked dan value) berdasarkan old() dan data produk.
    // Kita hanya perlu memastikan input jumlah yang sudah 'checked' oleh Blade menjadi terlihat.
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#komponen-masakan-list input[type="checkbox"]').forEach(checkbox => {
            if (checkbox.checked) {
                const jumlahInput = document.getElementById('jumlah_' + checkbox.value);
                if (jumlahInput) {
                    jumlahInput.style.display = 'inline-block';
                     if (jumlahInput.value === '' || parseFloat(jumlahInput.value) <= 0) { // Pastikan ada nilai jika dicentang
                         jumlahInput.value = 1;
                     }
                }
            }
        });
    });
</script>
@endpush