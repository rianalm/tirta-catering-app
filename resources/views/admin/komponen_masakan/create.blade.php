<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Komponen Masakan - Tirta Catering</title>
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

        .form-group input[type="text"] {
            width: calc(100% - 22px); /* Adjust for padding and border */
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
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
        <h1>Tambah Komponen Masakan Baru</h1>

        <form action="{{ route('admin.komponen-masakan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_komponen">Nama Komponen Masakan:</label>
                <input type="text" id="nama_komponen" name="nama_komponen" value="{{ old('nama_komponen') }}" required>
                @error('nama_komponen')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="satuan_dasar">Satuan Dasar (Opsional, cth: porsi, potong, buah):</label>
                <input type="text" id="satuan_dasar" name="satuan_dasar" value="{{ old('satuan_dasar') }}">
                @error('satuan_dasar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <button type="submit" class="btn-submit">Simpan</button>
                <a href="{{ route('admin.komponen-masakan.index') }}" class="btn-back">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>a{{-- resources/views/admin/komponen_masakan/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Komponen Masakan')

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
            <h1>Tambah Komponen Masakan Baru</h1>
        </div>

        <form action="{{ route('admin.komponen-masakan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_komponen">Nama Komponen Masakan:</label>
                <input type="text" id="nama_komponen" name="nama_komponen" value="{{ old('nama_komponen') }}" required>
                @error('nama_komponen')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="satuan_dasar">Satuan Dasar (Opsional, cth: kg, gram, pcs, buah):</label>
                <input type="text" id="satuan_dasar" name="satuan_dasar" value="{{ old('satuan_dasar') }}" placeholder="Contoh: kg, gram, liter, pcs">
                @error('satuan_dasar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('admin.komponen-masakan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection