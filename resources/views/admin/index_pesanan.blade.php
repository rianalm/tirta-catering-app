@extends('layouts.admin')

@section('title', 'Kelola Semua Pesanan')

@push('styles')
<style>
    .status-badge {
        display: inline-block; padding: 5px 10px; border-radius: 5px;
        font-weight: 600; font-size: 0.85em; text-transform: capitalize; color: white;
    }
    .filter-search-container {
        display: flex; justify-content: space-between; align-items: flex-start; 
        gap: 15px; margin-bottom: 25px; background-color: #f8f9fa;
        padding: 20px; border-radius: 10px; border: 1px solid #e9ecef;
        flex-wrap: wrap; 
    }
    .filter-search-container form {
        display: flex; gap: 15px; align-items: flex-end; 
        flex-wrap: wrap; width: 100%;
    }
    .form-filter-group { display: flex; flex-direction: column; gap: 5px; }
    .form-filter-group label { font-weight: 600; color: #495057; font-size: 0.9em; margin-bottom: 2px; }
    .form-filter-group select, .form-filter-group input {
        padding: 10px 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
    }
    .action-buttons-filter { display: flex; gap: 10px; align-items: flex-end; }
    
    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eceeef; vertical-align: middle; }
    thead th { background-color: #f8f9fa; color: #495057; font-weight: 700; text-transform: uppercase; font-size: 0.85em; }
    tbody tr:hover { background-color: #f6f9fc; }
    .actions { display: flex; gap: 5px; flex-wrap: wrap; }
    .actions .btn-sm { width: 36px; height: 32px; font-size: 0.85em; }
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
                <div class="form-filter-group">
                    <label for="statusFilter">Status</label>
                    <select id="statusFilter" name="status" class="filter-input" onchange="this.form.submit();">
                        <option value="">Semua Status</option>
                        @foreach ($allStatuses as $status)
                            <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-filter-group">
                    <label for="filter_tanggal_pengiriman">Tgl Kirim</label>
                    <input type="date" id="filter_tanggal_pengiriman" name="filter_tanggal_pengiriman" class="filter-input" value="{{ $tanggalFilter ?? '' }}">
                </div>
                <div class="form-filter-group" style="flex-grow: 1;">
                    <label for="searchInput">Cari</label>
                    <input type="text" id="searchInput" name="search" class="filter-input" placeholder="ID, Pelanggan, Telp..." value="{{ $searchQuery ?? '' }}">
                </div>
                <div class="action-buttons-filter">
                    <button type="submit" class="btn btn-info">Filter</button>
                    @if ($statusFilter || $searchQuery || $tanggalFilter)
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Telepon</th>
                        <th>Tgl Kirim (Jam)</th>
                        <th>Total Harga</th>
                        <th>Jenis Penyajian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td>
                            <td>{{ $pesanan->nama_pelanggan }}</td>
                            <td>{{ $pesanan->telepon_pelanggan }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d-m-Y') }}
                                @if ($pesanan->waktu_pengiriman) <br><small>({{ $pesanan->waktu_pengiriman }})</small> @endif
                            </td>
                            <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $pesanan->jenis_penyajian ?? '-' }}</td>
                            <td><span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">{{ ucfirst($pesanan->status_pesanan) }}</span></td>
                            <td class="actions">
                            <td data-label="Aksi" class="actions">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                                    <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
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
                            </td>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada pesanan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $pesanans->appends(request()->except('page'))->links() }}
        </div>
    </div>

    <div id="deleteConfirmationModal" class="custom-modal"> 
        <div class="custom-modal-content">
            <span class="close-button">&times;</span>
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.</p>
            <div class="modal-buttons">
                <button type="button" class="btn btn-secondary cancel">Batal</button>
                <button type="button" class="btn btn-danger confirm" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // --- SCRIPT LENGKAP DENGAN PERBAIKAN ---

    // Fungsi untuk menampilkan pesan AJAX (jika ada)
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';
    function showAjaxMessage(message, type = 'success') { /* ... */ }

    // Logika untuk Modal Konfirmasi Hapus
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmationModal');
        if (!deleteModal) return;

        const confirmBtn = deleteModal.querySelector('.confirm');
        const cancelBtns = deleteModal.querySelectorAll('.cancel, .close-button');
        let itemToDeleteId = null;

        function openDeleteModal(id) {
            itemToDeleteId = id;
            deleteModal.style.display = 'flex';
            setTimeout(() => deleteModal.classList.add('show'), 10);
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('show');
            setTimeout(() => {
                deleteModal.style.display = 'none';
                itemToDeleteId = null;
            }, 300);
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                openDeleteModal(this.dataset.id);
            });
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (itemToDeleteId) {
                    const form = document.getElementById(`delete-form-${itemToDeleteId}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }

        cancelBtns.forEach(btn => btn.addEventListener('click', closeDeleteModal));

        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });
    });

    // Logika untuk Tombol Aksi Status
    document.querySelectorAll('.status-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pesananId = this.dataset.id;
            const newStatus = this.dataset.status;
            let confirmationMessage = `Ubah status pesanan #${pesananId} menjadi "${newStatus}"?`;

            if (newStatus === 'dibatalkan') { confirmationMessage = `Yakin ingin MEMBATALKAN pesanan #${pesananId}?`; }
            else if (newStatus === 'selesai') { confirmationMessage = `Yakin pesanan #${pesananId} sudah SELESAI?`; }
            else if (newStatus === 'dikirim') { confirmationMessage = `Konfirmasi pesanan #${pesananId} sudah DIKIRIM?`; }

            if (!confirm(confirmationMessage)) { return; }

            fetch(`/admin/pesanan/${pesananId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => { throw new Error(errorData.message || 'Gagal update.'); });
                }
                return response.json();
            })
            .then(data => {
                showAjaxMessage(data.message, 'success');
                setTimeout(() => { window.location.reload(); }, 1500);
            })
            .catch(error => {
                showAjaxMessage(error.message, 'error');
            });
        });
    });
</script>
@endpush