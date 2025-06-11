{{-- resources/views/admin/index_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Semua Pesanan')

@push('styles')
<style>
    
    .table-responsive {
        display: none;
    }
    .order-card-list {
        margin-top: 25px;
    }
    .order-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.3s ease;
        border-left: 5px solid #6c757d; 
    }
    .order-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    /* Atur warna border kiri berdasarkan status */
    .order-card.status-pending { border-left-color: #ffc107; }
    .order-card.status-diproses { border-left-color: #007bff; }
    .order-card.status-dikirim { border-left-color: #6f42c1; }
    .order-card.status-selesai { border-left-color: #28a745; }
    .order-card.status-dibatalkan { border-left-color: #dc3545; }

    /* Bagian Header Kartu */
    .card-header-pesanan { /* Menggunakan nama class yang unik */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .card-header-info .order-id {
        font-weight: 700;
        font-size: 1.2em;
        color: #2c3e50;
    }
    .card-header-info .customer-name {
        color: #555;
        font-size: 1em;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8em;
        text-transform: capitalize;
        color: white;
    }
    .status-badge.pending { background-color: #ff9800; }
    .status-badge.diproses { background-color: #007bff; }
    .status-badge.dikirim { background-color: #6f42c1; }
    .status-badge.selesai { background-color: #28a745; }
    .status-badge.dibatalkan { background-color: #dc3545; }

    /* Bagian Isi Kartu */
    .card-body-pesanan { /* Menggunakan nama class yang unik */
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .detail-item {
        color: #555;
        font-size: 0.95em;
    }
    .detail-item strong {
        display: block;
        color: #333;
        font-weight: 600;
        font-size: 0.9em;
        margin-bottom: 4px;
        opacity: 0.8;
    }

    /* Bagian Footer Kartu (Aksi) */
    .card-actions {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #f0f0f0;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .card-actions .btn-sm { width: 38px; height: 32px; font-size: 0.9em; }

    /* Styling Filter dan Paginasi (tetap sama) */
    .filter-search-container {
        display: flex; justify-content: space-between; align-items: flex-start; 
        gap: 15px; margin-bottom: 25px; background-color: #f8f9fa;
        padding: 20px; border-radius: 10px; border: 1px solid #e9ecef;
        flex-wrap: wrap; 
    }
    .filter-search-container form { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; width: 100%; }
    .form-filter-group { display: flex; flex-direction: column; gap: 5px; }
    .form-filter-group label { font-weight: 600; color: #495057; white-space: nowrap; font-size: 0.9em; margin-bottom: 0; }
    .form-filter-group select,
    .form-filter-group input[type="text"],
    .form-filter-group input[type="date"] { padding: 10px 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 1em; box-sizing: border-box; }
    .action-buttons-filter { display: flex; gap: 10px; align-items: flex-end; margin-top: 23px; }
    .pagination-container { margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 10px; }
    /* ... (CSS Paginasi dan Modal lengkap Anda) ... */
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Kelola Semua Pesanan</h1>
        </div>

        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        <div id="ajax-message-container" style="display: none; margin-bottom: 20px;"><div id="ajax-message" class="alert"></div></div>

        <a href="{{ route('admin.pesanan.create') }}" class="btn btn-primary" style="margin-bottom: 25px;"><i class="fas fa-plus" style="margin-right: 8px;"></i> Tambah Pesanan Baru</a>

        <div class="filter-search-container">
            <form action="{{ route('admin.pesanan.index') }}" method="GET" id="filterPesananForm">
                {{-- ... (Isi form filter Anda tetap sama) ... --}}
            </form>
        </div>

        {{-- DAFTAR KARTU PESANAN BARU --}}
        <div class="order-card-list">
            @forelse ($pesanans as $pesanan)
                <div class="order-card status-{{ strtolower($pesanan->status_pesanan) }}">
                    <div class="card-header-pesanan">
                        <div class="card-header-info">
                            <span class="order-id">Pesanan #{{ $pesanan->id }}</span>
                            <span class="customer-name">{{ $pesanan->nama_pelanggan }}</span>
                        </div>
                        <span class="status-badge {{ strtolower($pesanan->status_pesanan) }}">{{ ucfirst($pesanan->status_pesanan) }}</span>
                    </div>

                    <div class="card-body-pesanan">
                        <div class="detail-item">
                            <strong><i class="fas fa-calendar-alt" style="margin-right: 5px;"></i> Tanggal Kirim</strong>
                            <span>{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->translatedFormat('d F Y') }} @ {{ $pesanan->waktu_pengiriman ?? '' }}</span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="fas fa-box-open" style="margin-right: 5px;"></i> Jenis Penyajian</strong>
                            <span>{{ $pesanan->jenis_penyajian ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="fas fa-utensils" style="margin-right: 5px;"></i> Ringkasan Item</strong>
                            <span>{{ $pesanan->itemPesanans->count() }} Jenis Item</span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="fas fa-money-bill-wave" style="margin-right: 5px;"></i> Total Harga</strong>
                            <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        {{-- Tombol aksi dengan ikon --}}
                        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                        
                        @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                            <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        
                        @if (strtolower($pesanan->status_pesanan) == 'pending')
                            <button type="button" class="btn btn-primary btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="diproses" title="Proses Pesanan"><i class="fas fa-cogs"></i></button>
                        @endif
                        @if (strtolower($pesanan->status_pesanan) == 'diproses')
                            <button type="button" class="btn btn-info btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="dikirim" title="Tandai Sudah Dikirim"><i class="fas fa-truck"></i></button>
                        @endif
                        @if (strtolower($pesanan->status_pesanan) == 'dikirim')
                            <button type="button" class="btn btn-success btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="selesai" title="Tandai Sudah Selesai"><i class="fas fa-check-circle"></i></button>
                        @endif
                        
                        @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                            <button type="button" class="btn btn-warning btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="dibatalkan" title="Batalkan Pesanan"><i class="fas fa-times-circle"></i></button>
                        @endif
                        
                        <form id="delete-form-{{ $pesanan->id }}" action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $pesanan->id }}" title="Hapus Data Pesanan"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center" style="text-align:center;">
                    <p>Tidak ada pesanan yang ditemukan sesuai kriteria.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination-container">
            {{ $pesanans->appends(request()->except('page'))->links() }}
        </div>

        <div id="deleteConfirmationModal" style="display: none;"> 
            <div>
                <span class="close-button" onclick="closeModal()">&times;</span>
                <h3>Konfirmasi Hapus</h3>
                <p>Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.</p>
                <div class="modal-buttons">
                    <button type="button" class="cancel btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="button" class="confirm btn btn-danger" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- JavaScript Anda tetap sama persis seperti versi terakhir --}}
<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

    function showAjaxMessage(message, type = 'success') {
        const container = document.getElementById('ajax-message-container');
        const msgDiv = document.getElementById('ajax-message');
        if(container && msgDiv){
            msgDiv.className = 'alert'; 
            msgDiv.textContent = '';
            msgDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-error');
            msgDiv.textContent = message;
            container.style.display = 'block';
            setTimeout(() => { container.style.display = 'none'; }, 5000);
        }
    }

    let pesananIdToDelete = null;
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    function openModal(pesananId) {
        pesananIdToDelete = pesananId;
        if(deleteConfirmationModal) {
            deleteConfirmationModal.style.display = 'flex'; 
            setTimeout(() => { deleteConfirmationModal.classList.add('show'); }, 10);
        }
    }
    function closeModal() {
        if(deleteConfirmationModal){
            deleteConfirmationModal.classList.remove('show');
            setTimeout(() => {
                deleteConfirmationModal.style.display = 'none';
                pesananIdToDelete = null;
            }, 300); 
        }
    }
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pesananId = this.dataset.id;
            openModal(pesananId);
        });
    });
    if(confirmDeleteBtn){
        confirmDeleteBtn.addEventListener('click', function() {
            if (pesananIdToDelete) {
                const deleteForm = document.getElementById(`delete-form-${pesananIdToDelete}`);
                if (deleteForm) {
                    deleteForm.submit();
                }
            }
        });
    }
    if(deleteConfirmationModal){
        const modalCloseButton = deleteConfirmationModal.querySelector('.close-button');
        const modalInternalCancelButton = deleteConfirmationModal.querySelector('.cancel');
        
        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) { closeModal(); }
        });
        if(modalCloseButton){ modalCloseButton.addEventListener('click', closeModal); }
        if(modalInternalCancelButton){ modalInternalCancelButton.addEventListener('click', closeModal); }
    }

    document.querySelectorAll('.status-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pesananId = this.dataset.id;
            const newStatus = this.dataset.status;
            let confirmationMessage = `Apakah Anda yakin ingin mengubah status pesanan #${pesananId} menjadi "${newStatus}"?`;

            if (newStatus === 'dibatalkan') {
                confirmationMessage = `Apakah Anda yakin ingin MEMBATALKAN pesanan #${pesananId}?`;
            } else if (newStatus === 'selesai') {
                confirmationMessage = `Apakah Anda yakin pesanan #${pesananId} sudah SELESAI diterima pelanggan?`;
            } else if (newStatus === 'dikirim') {
                confirmationMessage = `Konfirmasi pesanan #${pesananId} sudah DIKIRIM?`;
            }

            if (!confirm(confirmationMessage)) {
                return;
            }

            fetch(`/admin/pesanan/${pesananId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Gagal memperbarui status.');
                    });
                }
                return response.json();
            })
            .then(data => {
                showAjaxMessage(data.message, 'success');
                setTimeout(() => { 
                    window.location.reload();
                }, 1500); 
            })
            .catch(error => {
                showAjaxMessage(error.message || 'Terjadi kesalahan.', 'error');
            });
        });
    });
</script>
@endpush