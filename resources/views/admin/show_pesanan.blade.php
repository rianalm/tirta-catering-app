{{-- resources/views/admin/show_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->id)

@push('styles')
<style>
    /* ... (CSS Anda tetap sama seperti versi terakhir yang sudah benar) ... */
    .container-content { max-width: 900px; margin: 0 auto; }
    .content-header h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2.5em; font-weight: 700; }
    .detail-section { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; }
    .detail-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .detail-section h2 {
        color: #34495e; font-size: 1.6em; margin-top: 0; margin-bottom: 15px;
        border-bottom: 2px solid #e9ecef; padding-bottom: 10px;
    }
    .detail-item { display: flex; margin-bottom: 10px; flex-wrap: wrap; /* Menambahkan wrap untuk mobile */ }
    .detail-item strong { flex: 0 0 180px; color: #555; font-weight: 600; }
    .detail-item span { flex-grow: 1; color: #333; }
    .item-list { list-style: none; padding: 0; margin-top: 10px; }
    .item-list li {
        background-color: #f8f9fa; border: 1px solid #e0e0e0; padding: 10px 15px;
        margin-bottom: 8px; border-radius: 6px; display: flex;
        justify-content: space-between; align-items: center; flex-wrap: wrap;
    }
    .item-list li span { font-weight: 500; color: #444; }
    .status-badge {
        display: inline-block; padding: 5px 10px; border-radius: 5px;
        font-weight: 600; font-size: 0.9em; text-transform: capitalize;
    }

     .invoice-section {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 20px;
        margin-top: 20px;
        border-radius: 8px;
    }
    .invoice-details {
        margin-top: 15px;
    }
    .price-summary { text-align: right; }
    .price-summary .detail-item {
        justify-content: flex-end;
        gap: 20px;
    }
    .price-summary .grand-total {
        font-size: 1.2em;
        font-weight: bold;
        border-top: 1px solid #ccc;
        padding-top: 10px;
        margin-top: 10px;
    }
    .form-invoice .form-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .form-invoice .form-group input {
        width: 50%;
        text-align: right;
    }

    .status-badge.pending, .status-badge.baru { background-color: #ffe0b2; color: #e65100; }
    .status-badge.diproses { background-color: #bbdefb; color: #0d47a1; }
    .status-badge.dikirim { background-color: #d1c4e9; color: #311b92; }
    .status-badge.selesai { background-color: #c8e6c9; color: #1b5e20; }
    .status-badge.dibatalkan { background-color: #ffcdd2; color: #b71c1c; }

    .actions {
        display: flex;
        justify-content: center; /* Tombol di tengah */
        align-items: center;
        gap: 10px; /* Jarak antar tombol */
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .actions .btn-sm {
        width: 40px; /* Sedikit lebih besar untuk ikon */
        height: 36px;
    }

    .actions .btn-back {
        padding: 8px 15px; /* Tombol kembali dengan teks */
    }

    @media (max-width: 768px) {
        .container-content { padding: 20px; }
        .detail-item { flex-direction: column; align-items: flex-start; }
        .detail-item strong { flex: none; width: 100%; margin-bottom: 3px; }
        .detail-item span { width: 100%; }
        .actions .btn { width: 100%; margin: 5px 0 !important; /* Perbaikan margin pada mobile */ }
    }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Detail Pesanan #{{ $pesanan->id }}</h1>
        </div>

        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        
        <div class="detail-section">
            <h2>Informasi Umum</h2>
            <div class="detail-item">
                <strong>ID Pesanan:</strong>
                <span>{{ $pesanan->id }}</span>
            </div>
            <div class="detail-item">
                <strong>Tanggal Pesanan:</strong>
                <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->translatedFormat('d F Y, H:i') }}</span>
            </div>
            <div class="detail-item">
                <strong>Status Pesanan:</strong>
                <span>
                    <span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">
                        {{ ucfirst($pesanan->status_pesanan) }}
                    </span>
                </span>
            </div>
            <div class="detail-item">
                <strong>Total Harga:</strong>
                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Detail Pelanggan</h2>
            <div class="detail-item">
                <strong>Nama Pelanggan:</strong>
                <span>{{ $pesanan->nama_pelanggan }}</span>
            </div>
            <div class="detail-item">
                <strong>Nomor Telepon:</strong>
                <span>{{ $pesanan->telepon_pelanggan }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Item Pesanan</h2>
            @if ($pesanan->itemPesanans->isEmpty())
                <p>Tidak ada item pesanan.</p>
            @else
                <ul class="item-list">
                    @foreach ($pesanan->itemPesanans as $item)
                        <li>
                            <span>{{ $item->produk->nama_produk }}</span>
                            <span>{{ $item->jumlah_porsi }} porsi @ Rp {{ number_format($item->harga_satuan_saat_pesan, 0, ',', '.') }} = Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="detail-section">
            <h2>Detail Pengiriman & Penyajian</h2>
            <div class="detail-item">
                <strong>Tanggal Pengiriman:</strong>
                <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="detail-item">
                <strong>Waktu Pengiriman:</strong>
                <span>{{ $pesanan->waktu_pengiriman ?? '-' }}</span>
            </div>
            
            {{-- PENAMBAHAN DETAIL JENIS PENYAJIAN --}}
            <div class="detail-item">
                <strong>Jenis Penyajian:</strong>
                <span>{{ $pesanan->jenis_penyajian ?? '-' }}</span>
            </div>
            {{-- AKHIR PENAMBAHAN --}}

            <div class="detail-item">
                <strong>Alamat Pengiriman:</strong>
                <span>{{ $pesanan->alamat_pengiriman }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h2>Catatan Khusus</h2>
            <div class="detail-item">
                <strong>Catatan:</strong>
                <span>{{ $pesanan->catatan_khusus ?? '-' }}</span>
            </div>
        </div>

        <div class="detail-section invoice-section">
            <h2>Rincian Biaya & Invoice</h2>
            <div class="price-summary">
                <div class="detail-item"><strong>Subtotal Item:</strong><span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></div>
                <div class="detail-item"><strong>Ongkos Kirim:</strong><span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span></div>
                <div class="detail-item"><strong>Service Charge / Biaya Lain:</strong><span>Rp {{ number_format($pesanan->biaya_lain, 0, ',', '.') }}</span></div>
                <div class="detail-item"><strong>Pajak ({{ $pesanan->pajak_persen > 0 ? $pesanan->pajak_persen.'%' : 'Nominal' }}):</strong><span>Rp {{ number_format($pesanan->pajak, 0, ',', '.') }}</span></div>
                <div class="detail-item grand-total"><strong>GRAND TOTAL:</strong><span>Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span></div>
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <a href="{{ route('admin.pesanan.invoice.edit', $pesanan->id) }}" class="btn btn-primary" title="Lengkapi/Edit Rincian Invoice">
                    <i class="fas fa-file-invoice-dollar" style="margin-right: 8px;"></i> Lengkapi
                </a>
            </div>
        </div>
    <div class="actions">
            <a href="{{ route('admin.pesanan.invoice.pdf', $pesanan->id) }}" target="_blank" class="btn btn-dark" title=" Cetak Invoice" style="background-color:rgba(223, 25, 18, 0.8);">
                <i class="fas fa-print"></i> Cetak
            </a>
            
            @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn btn-warning" title="Edit Detail Pesanan">
                    <i class="fas fa-pencil-alt"></i> Edit Pesanan
                </a>
            @endif
            
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-back">Kembali</a>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteConfirmationModalShow" style="display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background-color: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 400px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.</p>
            <div style="margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModalShow()" style="margin-right: 10px;">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtnShow">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let pesananIdToDeleteShow = null;
    const deleteModalShow = document.getElementById('deleteConfirmationModalShow');
    const confirmDeleteBtnShow = document.getElementById('confirmDeleteBtnShow');

    function openModalShow(id) {
        pesananIdToDeleteShow = id;
        if(deleteModalShow) deleteModalShow.style.display = 'flex';
    }

    function closeModalShow() {
        if(deleteModalShow) deleteModalShow.style.display = 'none';
        pesananIdToDeleteShow = null;
    }

    document.querySelectorAll('.delete-btn-show').forEach(button => {
        button.addEventListener('click', function() {
            openModalShow(this.dataset.id);
        });
    });

    if(confirmDeleteBtnShow){
        confirmDeleteBtnShow.addEventListener('click', function() {
            if (pesananIdToDeleteShow) {
                const form = document.getElementById(`delete-form-show-${pesananIdToDeleteShow}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }
    
    if(deleteModalShow) {
        deleteModalShow.addEventListener('click', function(event) {
            if (event.target === deleteModalShow) {
                closeModalShow();
            }
        });
        const cancelBtn = deleteModalShow.querySelector('.btn-secondary');
        if (cancelBtn) cancelBtn.addEventListener('click', closeModalShow);
    }
</script>
@endpush