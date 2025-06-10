{{-- resources/views/admin/index_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@push('styles')
<style>
    /* CSS umum dari layout utama akan berlaku. Ini adalah styling tambahan/spesifik. */
    .status-badge {
        display: inline-block; padding: 5px 10px; border-radius: 5px;
        font-weight: 600; font-size: 0.85em; text-transform: capitalize;
    }
    .status-badge.pending, .status-badge.baru { background-color: #ffe0b2; color: #e65100; }
    .status-badge.diproses { background-color: #bbdefb; color: #0d47a1; }
    .status-badge.dikirim { background-color: #d1c4e9; color: #311b92; }
    .status-badge.selesai { background-color: #c8e6c9; color: #1b5e20; }
    .status-badge.dibatalkan { background-color: #ffcdd2; color: #b71c1c; }

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
    .form-filter-group label { font-weight: 600; color: #495057; white-space: nowrap; font-size: 0.9em; margin-bottom: 0; }
    .form-filter-group select,
    .form-filter-group input[type="text"],
    .form-filter-group input[type="date"] {
        padding: 10px 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
    }
    .action-buttons-filter { display: flex; gap: 10px; align-items: flex-end; margin-top: 23px; }

    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; margin-top: 0; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eceeef; vertical-align: middle; }
    thead th { background-color: #f8f9fa; color: #495057; font-weight: 700; text-transform: uppercase; font-size: 0.9em; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f2f2f2; }
    
    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap; 
        align-items: center;
        min-width: 220px; 
    }
    .actions .btn, .actions form {
        margin-bottom: 5px; 
    }

    .pagination-container { margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 10px; }
    .pagination-container .pagination { display: flex; list-style: none; padding: 0; margin: 0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .pagination-container .page-item { display: inline-block; }
    .pagination-container .page-link { display: block; padding: 10px 15px; color: #007bff; text-decoration: none; border: 1px solid #dee2e6; margin-left: -1px; transition: background-color 0.2s, color 0.2s, border-color 0.2s; }
    .pagination-container .page-item:first-child .page-link { border-top-left-radius: 8px; border-bottom-left-radius: 8px;}
    .pagination-container .page-item:last-child .page-link { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
    .pagination-container .page-item.active .page-link { background-color: #007bff; color: white; border-color: #007bff; }
    .pagination-container .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #e9ecef; }
    .pagination-container .page-link:hover:not(.active):not(.disabled) { background-color: #e9ecef; color: #0056b3; border-color: #0056b3; }

    #deleteConfirmationModal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease-in-out; }
    #deleteConfirmationModal.show { opacity: 1; display: flex !important; }
    #deleteConfirmationModal > div { background-color: #fefefe; padding: 30px; border: 1px solid #ddd; border-radius: 12px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.2); position: relative; transform: translateY(-20px); opacity: 0; transition: transform 0.3s ease-out, opacity 0.3s ease-out; }
    #deleteConfirmationModal.show > div { transform: translateY(0); opacity: 1; }
    #deleteConfirmationModal h3 { color: #333; margin-top: 0; margin-bottom: 20px; font-size: 1.8em; }
    #deleteConfirmationModal p { margin-bottom: 30px; font-size: 1.1em; line-height: 1.6; color: #555; }
    #deleteConfirmationModal .modal-buttons { display: flex; justify-content: space-around; gap: 15px; }
    .close-button { color: #aaa; float: right; font-size: 30px; font-weight: bold; position: absolute; top: 15px; right: 20px; cursor: pointer; }
    .close-button:hover, .close-button:focus { color: #333; text-decoration: none; cursor: pointer; }

    @media (max-width: 768px) {
        .filter-search-container { flex-direction: column; align-items: stretch; }
        .filter-search-container form { flex-direction: column; }
        .form-filter-group { width: 100%; margin-bottom:10px; }
        .form-filter-group input, .form-filter-group select { width: 100%; }
        .action-buttons-filter { width: 100%; flex-direction: column; margin-top: 10px; }
        .action-buttons-filter button, .action-buttons-filter a { width: 100%; margin-left: 0 !important; margin-bottom: 10px; }
        
        table thead { display: none; }
        table tr { display: block; margin-bottom: .625em; border:1px solid #ddd; border-radius: 4px;}
        table td { display: block; text-align: right; font-size: .8em; border-bottom: 1px dotted #ccc; padding-left: 50% !important; position:relative; }
        table td::before { content: attr(data-label); float: left; font-weight: bold; text-transform: uppercase; position:absolute; left: 15px; text-align:left; width:calc(50% - 20px); white-space:nowrap;}
        table td:last-child { border-bottom: 0; }
        .actions { justify-content: flex-end; } 
        .actions .btn { width: auto; }
    }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Daftar Pesanan</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div id="ajax-message-container" style="display: none; margin-bottom: 20px;">
            <div id="ajax-message" class="alert"></div>
        </div>

        <a href="{{ route('admin.pesanan.create') }}" class="btn btn-primary btn-create" style="margin-bottom: 15px;">Tambah Pesanan Baru</a>

        <div class="filter-search-container">
            <form action="{{ route('admin.pesanan.index') }}" method="GET" id="filterPesananForm">
                <div class="form-filter-group">
                    <label for="statusFilter">Filter Status:</label>
                    <select id="statusFilter" name="status" class="filter-input" onchange="document.getElementById('filterPesananForm').submit();">
                        <option value="" {{ empty($statusFilter) ? 'selected' : '' }}>Semua Status</option>
                        @foreach ($allStatuses as $status)
                            <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-filter-group">
                    <label for="filter_tanggal_pengiriman">Tanggal Pengiriman:</label>
                    <input type="date" id="filter_tanggal_pengiriman" name="filter_tanggal_pengiriman" class="filter-input" value="{{ $tanggalFilter ?? '' }}">
                </div>
                <div class="form-filter-group" style="flex-grow: 1;">
                    <label for="searchInput">Cari Pesanan (ID, Pelanggan, Telp):</label>
                    <input type="text" id="searchInput" name="search" class="filter-input" placeholder="Masukkan kata kunci..." value="{{ $searchQuery ?? '' }}">
                </div>
                <div class="action-buttons-filter">
                    <button type="submit" class="btn btn-info">Terapkan Filter</button>
                    @if ($statusFilter || $searchQuery || $tanggalFilter)
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Reset Semua</a>
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
                        <th>Tgl Kirim @if($tanggalFilter) (Jam Kirim) @endif</th>
                        <th>Total Harga</th>
                        <th>Jenis Penyajian</th> {{-- <-- KOLOM BARU --}}
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $pesanan)
                        <tr>
                            <td data-label="ID">{{ $pesanan->id }}</td>
                            <td data-label="Pelanggan">{{ $pesanan->nama_pelanggan }}</td>
                            <td data-label="Telepon">{{ $pesanan->telepon_pelanggan }}</td>
                            <td data-label="Tgl Kirim @if($tanggalFilter) (Jam Kirim) @endif">
                                {{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d-m-Y') }}
                                @if ($pesanan->waktu_pengiriman)
                                     <br><small>({{ $pesanan->waktu_pengiriman }})</small>
                                @endif
                            </td>
                            <td data-label="Total Harga">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            
                            <td data-label="Jenis Penyajian">{{ $pesanan->jenis_penyajian ?? '-' }}</td> {{-- <-- DATA BARU --}}

                            <td data-label="Status">
                                <span class="status-badge {{ strtolower(str_replace(' ', '_', $pesanan->status_pesanan)) }}">
                                    {{ ucfirst($pesanan->status_pesanan) }}
                                </span>
                            </td>
                            <td data-label="Aksi" class="actions">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-info btn-sm">Detail</a>
                                
                                @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                                    <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn btn-success btn-sm">Edit</a>
                                @endif
                                
                                @if (strtolower($pesanan->status_pesanan) == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="diproses" title="Proses Pesanan">Proses</button>
                                @endif
                                @if (strtolower($pesanan->status_pesanan) == 'diproses')
                                    <button type="button" class="btn btn-info btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="dikirim" title="Tandai Sudah Dikirim">Kirim</button>
                                @endif
                                @if (strtolower($pesanan->status_pesanan) == 'dikirim')
                                    <button type="button" class="btn btn-success btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="selesai" title="Tandai Sudah Selesai">Selesai</button>
                                @endif
                                
                                @if (!in_array(strtolower($pesanan->status_pesanan), ['selesai', 'dibatalkan']))
                                    <button type="button" class="btn btn-warning btn-sm status-action-btn" data-id="{{ $pesanan->id }}" data-status="dibatalkan" title="Batalkan Pesanan">Batalkan</button>
                                @endif
                                
                                <form id="delete-form-{{ $pesanan->id }}" action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $pesanan->id }}" title="Hapus Data Pesanan">Hapus Data</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;"> {{-- Colspan menjadi 8 --}}
                                @if($tanggalFilter)
                                    Tidak ada pesanan yang ditemukan untuk tanggal {{ \Carbon\Carbon::parse($tanggalFilter)->translatedFormat('d F Y') }}.
                                @else
                                    Tidak ada pesanan yang ditemukan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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