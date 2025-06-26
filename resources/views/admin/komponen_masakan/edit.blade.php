@extends('layouts.admin')
@section('title', 'Edit Komponen Masakan')

@push('styles')
<style>
    .container-content { max-width: 600px; margin: 0 auto; }
    .content-header h1 { text-align: center; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
    .form-group input[type="text"] {
        width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input[type="text"]:focus {
        border-color: #007bff; outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; display: block; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
             <h1>Edit Komponen: {{ $komponenMasakan->nama_komponen }}</h1>
        </div>

        <form action="{{ route('admin.komponen-masakan.update', $komponenMasakan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_komponen">Nama Komponen Masakan:</label>
                <input type="text" id="nama_komponen" name="nama_komponen" value="{{ old('nama_komponen', $komponenMasakan->nama_komponen) }}" required>
                @error('nama_komponen')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="satuan_dasar">Satuan Dasar (Opsional, cth: kg, gram, pcs, buah):</label>
                <input type="text" id="satuan_dasar" name="satuan_dasar" value="{{ old('satuan_dasar', $komponenMasakan->satuan_dasar) }}" placeholder="Contoh: kg, gram, liter, pcs">
                @error('satuan_dasar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.komponen-masakan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection