{{-- resources/views/admin/produk/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@push('styles')
<style>
    .header-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; flex-wrap: wrap; gap: 15px;
    }
    .header-actions .search-box { display: flex; gap: 10px; }
    .header-actions .search-box input[type="text"] {
        padding: 10px 15px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; width: 250px; box-sizing: border-box;
    }
    .table-responsive { overflow-x: auto; }
    table {
        width: 100%; border-collapse: collapse; margin-top: 0; 
        background-color: #fff; border-radius: 10px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eceeef; vertical-align: middle; }
    thead th {
        background-color: #f8f9fa; color: #495057; font-weight: 700;
        text-transform: uppercase; font-size: 0.9em;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f2f2f2; }
    .actions { display: flex; gap: 8px; }
    .actions .btn-edit, .actions .btn-delete, .actions .btn-show {
        padding: 8px 12px; font-size: 0.9em; font-weight: 500;
        /* Warna sudah diatur oleh class .btn-* dari layout */
    }
    .alert.show { opacity: 1; display: block !important; } /* Pastikan display block saat show */
    .modal {
        display: none; position: fixed; z-index: 1050; 
        left: 0; top: 0; width: 100%; height: 100%; overflow: auto;
        background-color: rgba(0,0,0,0.4); justify-content: center; align-items: center;
        opacity: 0; transition: opacity 0.3s ease-in-out;
    }
    .modal.show { opacity: 1; display: flex !important; } 
    .modal-content {
        background-color: #fefefe; margin: auto; padding: 30px; border: 1px solid #888;
        border-radius: 12px; width: 90%; max-width: 400px; text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transform: translateY(-20px); transition: transform 0.3s ease-in-out;
    }
    .modal.show .modal-content { transform: translateY(0); }
    .modal-content h3 { color: #333; margin-top: 0; margin-bottom: 20px; }
    .modal-content p { margin-bottom: 25px; color: #555; }
    .modal-buttons { display: flex; justify-content: center; gap: 15px; }
   
    @media (max-width: 768px) {
        .header-actions { flex-direction: column; align-items: stretch; }
        .header-actions .search-box { width: 100%; flex-direction: column; }
        .header-actions .search-box input[type="text"],
        .header-actions .search-box button,
        .header-actions .search-box .btn-reset { width: 100%; margin-left: 0 !important; }
        .btn-add { width: 100%; text-align: center; }
        /* Responsive table (copy dari index_pesanan jika sama) */
        table thead { display: none; }
        table tr { display: block; margin-bottom: .625em; border:1px solid #ddd; border-radius: 4px;}
        table td { display: block; text-align: right; font-size: .8em; border-bottom: 1px dotted #ccc; }
        table td::before { content: attr(data-label); float: left; font-weight: bold; text-transform: uppercase; }
        table td:last-child { border-bottom: 0; }
    }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Manajemen Produk</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success show" style="display: block;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error show" style="display: block;">
                {{ session('error') }}
            </div>
        @endif
        {{-- Container untuk pesan JS, jika masih dipakai --}}
        {{-- <div class="ajax-message-container" style="display: none;">
            <div class="alert"></div>
        </div> --}}


        <div class="header-actions">
            <a href="{{ route('admin.produks.create') }}" class="btn btn-success btn-add">Tambah Produk Baru</a>
            <form action="{{ route('admin.produks.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.produks.index') }}" class="btn btn-secondary btn-reset" style="text-decoration: none;">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks as $produk)
                        <tr>
                            <td data-label="ID">{{ $produk->id }}</td>
                            <td data-label="Nama Produk">{{ $produk->nama_produk }}</td>
                            <td data-label="Harga">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td data-label="Satuan">{{ $produk->satuan ?? '-' }}</td>
                            <td data-label="Aksi" class="actions">
                                <a href="{{ route('admin.produks.show', $produk->id) }}" class="btn btn-info btn-show">Detail</a>
                                <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn btn-warning btn-edit">Edit</a>
                                <form id="delete-form-produk-{{ $produk->id }}" action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-delete delete-btn" data-id="{{ $produk->id }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $produks->appends(request()->query())->links() }} {{-- Memastikan search query terbawa saat paginasi --}}
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak bisa dibatalkan.</p>
            <div class="modal-buttons">
                <button type="button" class="btn btn-cancel btn-secondary" onclick="closeModal()">Batal</button>
                <button type="button" class="btn btn-confirm btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showAlertOnLoad(message, type = 'success') {
        const alertDiv = document.querySelector('.alert.' + (type === 'success' ? 'alert-success' : 'alert-error'));
        if (alertDiv && message) { // Hanya tampilkan jika ada pesan dan elemennya
            alertDiv.textContent = message; // Set pesan dari session
            alertDiv.style.display = 'block'; // Tampilkan
            alertDiv.classList.add('show');

            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => { alertDiv.style.display = 'none'; }, 300); // Sesuaikan dengan transisi CSS
            }, 5000);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        if (successMessage) {
            showAlertOnLoad(successMessage, 'success');
        }
        if (errorMessage) {
            showAlertOnLoad(errorMessage, 'error');
        }
    });

    let produkIdToDelete = null;
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    function openModal(id) {
        produkIdToDelete = id;
        if(deleteConfirmationModal) {
            deleteConfirmationModal.style.display = 'flex';
            setTimeout(() => { deleteConfirmationModal.classList.add('show'); }, 10);
        }
    }

    function closeModal() {
        if(deleteConfirmationModal) {
            deleteConfirmationModal.classList.remove('show');
            setTimeout(() => {
                deleteConfirmationModal.style.display = 'none';
                produkIdToDelete = null;
            }, 300);
        }
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            openModal(this.dataset.id);
        });
    });

    if(confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (produkIdToDelete) {
                // Menggunakan ID form yang sudah ditambahkan di Blade
                const form = document.getElementById(`delete-form-produk-${produkIdToDelete}`);
                if (form) {
                    form.submit();
                } else {
                    console.error('Delete form not found for produk ID:', produkIdToDelete);
                }
            }
        });
    }
    
    if(deleteConfirmationModal) {
        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal();
            }
        });
        // Jika ada tombol close di dalam modal (misal &times;)
        const modalInternalCloseButton = deleteConfirmationModal.querySelector('.btn-cancel'); // Tombol batal juga bisa menutup
        if(modalInternalCloseButton) modalInternalCloseButton.addEventListener('click', closeModal);
    }
</script>
@endpush