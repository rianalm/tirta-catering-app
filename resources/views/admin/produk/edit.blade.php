{{-- resources/views/admin/produk/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $produk->nama_produk)

@push('styles')
<style>
    /* ... (Gaya CSS sama seperti di create.blade.php) ... */
    .container-content { max-width: 700px; margin: 0 auto; }
    .content-header h1 { text-align: center; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea {
        width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
    }
    .komponen-list-wrapper { border: 1px solid #ddd; padding:15px; border-radius:8px; }
    .komponen-list-container { max-height: 300px; overflow-y: auto; padding-right: 10px; }
    .komponen-item {
        display: flex; align-items: center; gap:10px; margin-bottom: 10px;
        padding: 10px; border: 1px solid #eee; border-radius: 6px;
    }
    .komponen-item label { margin-bottom: 0; font-weight: 500; flex-grow: 1; cursor: pointer; }
    .komponen-item input[type="number"] { width: 150px; margin-left: auto; }
    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; display: block; }
    .alert-danger ul { margin: 0; padding-left: 20px; list-style: disc; }
    #komponen-search { margin-bottom: 15px; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Edit Produk: {{ $produk->nama_produk }}</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <strong>Oops! Ada beberapa masalah dengan input Anda:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.produks.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ... (Input Nama, Deskripsi, Harga, Satuan tetap sama) ... --}}
            <div class="form-group">
                <label for="nama_produk">Nama Produk:</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                @error('nama_produk') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            {{-- ... sisa input lainnya ... --}}
            <div class="form-group">
                <label for="deskripsi_produk">Deskripsi Produk:</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk">{{ old('deskripsi_produk', $produk->deskripsi_produk) }}</textarea>
                @error('deskripsi_produk') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual:</label>
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" step="1" min="0" required>
                @error('harga_jual') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="satuan">Satuan (cth: porsi, paket, buah):</label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan', $produk->satuan) }}">
                @error('satuan') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Komponen Masakan:</label>
                <div class="komponen-list-wrapper">
                    {{-- INPUT PENCARIAN BARU --}}
                    <input type="text" id="komponen-search" placeholder="Cari komponen masakan..." class="form-control">

                    <div id="komponen-masakan-list" class="komponen-list-container">
                        @forelse ($komponenMasakans as $komponen)
                            @php
                                $isChecked = old('komponen_masakan.'.$komponen->id.'.id') !== null || (old('komponen_masakan') === null && isset($produkKomponen[$komponen->id]));
                                $jumlahValue = old('komponen_masakan.'.$komponen->id.'.jumlah', $produkKomponen[$komponen->id] ?? 1);
                            @endphp
                            <div class="komponen-item">
                                <input type="checkbox"
                                    id="komponen_{{ $komponen->id }}"
                                    name="komponen_masakan[{{ $komponen->id }}][id]"
                                    value="{{ $komponen->id }}"
                                    onchange="toggleJumlahInput(this)"
                                    {{ $isChecked ? 'checked' : '' }}>
                                <label for="komponen_{{ $komponen->id }}">{{ $komponen->nama_komponen }} ({{ $komponen->satuan_dasar ?? 'unit' }})</label>
                                <input type="number"
                                    id="jumlah_{{ $komponen->id }}"
                                    name="komponen_masakan[{{ $komponen->id }}][jumlah]"
                                    placeholder="Jumlah"
                                    min="1" step="any"
                                    value="{{ $jumlahValue }}"
                                    style="display: none;" disabled>
                                @error('komponen_masakan.'.$komponen->id.'.jumlah')
                                    <span class="error-message" style="display: block; width:100%; text-align:right;">{{ $message }}</span>
                                @enderror
                            </div>
                        @empty
                            <p>Belum ada komponen masakan yang terdaftar.</p>
                        @endforelse
                    </div>
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
            if (checkbox.checked) {
                jumlahInput.disabled = false;
                jumlahInput.style.display = 'inline-block';
                if (jumlahInput.value === '' || parseFloat(jumlahInput.value) <= 0) { 
                    jumlahInput.value = 1;
                }
            } else {
                jumlahInput.disabled = true;
                jumlahInput.style.display = 'none';
                jumlahInput.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk toggle input jumlah
        document.querySelectorAll('#komponen-masakan-list input[type="checkbox"]').forEach(checkbox => {
            toggleJumlahInput(checkbox);
        });

        // SCRIPT BARU UNTUK FUNGSI PENCARIAN
        const searchInput = document.getElementById('komponen-search');
        const komponenItems = document.querySelectorAll('.komponen-item');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();

            komponenItems.forEach(function(item) {
                const label = item.querySelector('label');
                const labelText = label.textContent.toLowerCase();

                if (labelText.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush