{{-- resources/views/admin/produk/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@push('styles')
<style>
    /* Gaya CSS spesifik untuk form ini jika ada, atau biarkan kosong jika semua sudah di layout */
    .container-content { max-width: 700px; margin: 0 auto; } /* Sesuaikan lebar form */
    .content-header h1 { text-align: center; } /* Tengahkan judul form */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea {
        width: 100%; /* Dibuat full width relatif ke parent */
        padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
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
    .komponen-item label { margin-bottom: 0; } /* Reset margin bottom untuk label checkbox */
    .komponen-item input[type="number"] { width: 150px; margin-left: auto; } /* Jumlah di kanan */

    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; display: block; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Tambah Produk Baru</h1>
        </div>

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
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" step="0.01" min="0" required> {{-- step bisa 0.01 untuk Rupiah --}}
                @error('harga_jual') {{-- Nama error seharusnya 'harga_jual', bukan 'harga' --}}
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
                <div id="komponen-masakan-list" style="border: 1px solid #ddd; padding:15px; border-radius:8px;">
                    @forelse ($komponenMasakans as $komponen)
                        <div class="komponen-item">
                            <input type="checkbox"
                                id="komponen_{{ $komponen->id }}"
                                name="komponen_masakan[{{ $komponen->id }}][id]"
                                value="{{ $komponen->id }}"
                                onchange="toggleJumlahInput(this)"
                                {{ old('komponen_masakan.'.$komponen->id.'.id') ? 'checked' : '' }}>
                            <label for="komponen_{{ $komponen->id }}" style="flex-grow:1;">{{ $komponen->nama_komponen }} ({{ $komponen->satuan_dasar ?? 'unit' }})</label>
                            <input type="number"
                                id="jumlah_{{ $komponen->id }}"
                                name="komponen_masakan[{{ $komponen->id }}][jumlah]"
                                placeholder="Jumlah"
                                min="1" step="any" {{-- step any untuk desimal jika perlu --}}
                                value="{{ old('komponen_masakan.'.$komponen->id.'.jumlah', 1) }}"
                                style="display: none;">
                            @error('komponen_masakan.'.$komponen->id.'.jumlah')
                                <span class="error-message" style="display: block; width:100%; text-align:right;">{{ $message }}</span>
                            @enderror
                        </div>
                    @empty
                        <p>Belum ada komponen masakan yang terdaftar. Silakan <a href="{{ route('admin.komponen-masakan.create') }}">tambah di sini</a>.</p>
                    @endforelse
                </div>
                @error('komponen_masakan') {{-- Error umum untuk array komponen --}}
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Simpan</button>
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

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#komponen-masakan-list input[type="checkbox"]').forEach(checkbox => {
            const jumlahInput = document.getElementById('jumlah_' + checkbox.value);
            if (jumlahInput) {
                // Cek dari old() input
                const oldKomponenData = @json(old('komponen_masakan', []));
                const currentOldValueId = oldKomponenData[checkbox.value]?.id;
                const currentOldValueJumlah = oldKomponenData[checkbox.value]?.jumlah;

                if (currentOldValueId) { // Jika komponen ini ada di old input (artinya dicentang sebelumnya)
                    checkbox.checked = true;
                    jumlahInput.style.display = 'inline-block';
                    if(currentOldValueJumlah !== undefined && currentOldValueJumlah !== null) {
                        jumlahInput.value = currentOldValueJumlah;
                    } else {
                        jumlahInput.value = 1; // Default jika jumlah tidak ada di old input
                    }
                } else if (checkbox.checked) { // Jika sudah checked oleh browser, pastikan input jumlah tampil
                     jumlahInput.style.display = 'inline-block';
                     if (jumlahInput.value === '' || parseFloat(jumlahInput.value) <= 0) {
                         jumlahInput.value = 1;
                     }
                } else {
                    jumlahInput.style.display = 'none'; // Pastikan tersembunyi jika tidak checked
                }
            }
        });
    });
</script>
@endpush