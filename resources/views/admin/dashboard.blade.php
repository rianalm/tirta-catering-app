{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-content"> 
        <div class="content-header">
            <h1>Selamat Datang di Admin Dashboard!</h1>
        </div>
        
        <p>Gunakan navigasi di sidebar kiri untuk mengelola berbagai aspek aplikasi Tirta Catering.</p>
        
        <div style="margin-top: 30px; display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="background-color: #e9f7ef; border: 1px solid #a6d7ac; padding: 20px; border-radius: 8px; flex: 1; min-width: 200px;">
                <h3>Pesanan Aktif</h3>
                <p style="font-size: 1.5em; font-weight: bold;">-</p> {{-- Ganti dengan data dinamis jika ada --}}
            </div>
            <div style="background-color: #e6f7ff; border: 1px solid #91d5ff; padding: 20px; border-radius: 8px; flex: 1; min-width: 200px;">
                <h3>Total Produk</h3>
                <p style="font-size: 1.5em; font-weight: bold;">-</p> {{-- Ganti dengan data dinamis jika ada --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
{{-- Jika ada CSS spesifik HANYA untuk halaman dashboard --}}
<style>
    /* CSS khusus dashboard bisa ditambahkan di sini jika perlu */
</style>
@endpush

@push('scripts')
{{-- Jika ada JavaScript spesifik HANYA untuk halaman dashboard --}}
<script>
    // console.log('Dashboard page specific JS loaded');
</script>
@endpush