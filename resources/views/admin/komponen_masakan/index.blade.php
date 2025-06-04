{{-- resources/views/admin/komponen_masakan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Komponen Masakan')

@push('styles')
<style>
    /* Styling mirip dengan produk/index.blade.php, bisa digeneralisasi */
    .container-content { /* max-width: 900px; (sesuaikan jika perlu) */ }
    .header-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; flex-wrap: wrap; gap: 15px;
    }
    .header-actions .search-box { display: flex; gap: 10px; }
    .header-actions .search-box input[type="text"] {
        padding: 10px 15px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; width: 250px; box-sizing: border-box;
    }
    .header-actions .search-box button { /* .btn .btn-primary */ }
    .btn-add { /* .btn .btn-success */ }

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
    .actions .btn-edit, .actions .btn-delete {
        padding: 8px 12px; font-size: 0.9em; font-weight: 500;
    }
    .pagination {
        margin-top: 25px; display: flex; justify-content: center; align-items: center; gap: 10px;
    }
     /* ... (Styling pagination sama seperti produk/index) ... */
    .pagination a, .pagination span { padding: 8px 15px; border: 1px solid #dee2e6; border-radius: 6px; text-decoration: none; color: #007bff; transition: background-color 0.2s, color 0.2s; }
    .pagination a:hover { background-color: #007bff; color: white; }
    .pagination .current { background-color: #007bff; color: white; border-color: #007bff; } /* Untuk Laravel 8+ default pagination */
    .pagination .page-item.active .page-link { background-color: #007bff; color: white; border-color: #007bff; } /* Untuk Laravel 9+ Bootstrap-5 view */
    .pagination .disabled { color: #6c757d; pointer-events: none; background-color: #e9ecef; }


    /* Alert & Modal (Sama seperti di index_produk) */
    .alert.show { opacity: 1; display: block !important; }
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
     /* ... (Sisa styling modal) ... */
    .modal-content h3 { color: #333; margin-top: 0; margin-bottom: 20px; }
    .modal-content p { margin-bottom: 25px; color: #555; }
    .modal-buttons { display: flex; justify-content: center; gap: 15px; }


    @media (max-width: 768px) {
        /* ... (Styling responsif sama seperti produk/index) ... */
        .header-actions { flex-direction: column; align-items: stretch; }
        .header-actions .search-box { width: 100%; flex-direction: column; }
        .header-actions .search-box input[type="text"],
        .header-actions .search-box button,
        .header-actions .search-box .btn-reset { width: 100%; margin-left: 0 !important; }
        .btn-add { width: 100%; text-align: center; }
        
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
            <h1>Manajemen Komponen Masakan</h1>
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

        <div class="header-actions">
            <a href="{{ route('admin.komponen-masakan.create') }}" class="btn btn-success btn-add">Tambah Komponen</a>
            <form action="{{ route('admin.komponen-masakan.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari komponen..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.komponen-masakan.index') }}" class="btn btn-secondary btn-reset" style="text-decoration: none;">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Komponen</th>
                        <th>Satuan Dasar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komponenMasakans as $komponen)
                        <tr>
                            <td data-label="ID">{{ $komponen->id }}</td>
                            <td data-label="Nama Komponen">{{ $komponen->nama_komponen }}</td>
                            <td data-label="Satuan Dasar">{{ $komponen->satuan_dasar ?? '-' }}</td>
                            <td data-label="Aksi" class="actions">
                                <a href="{{ route('admin.komponen-masakan.edit', $komponen->id) }}" class="btn btn-warning btn-edit">Edit</a>
                                {{-- Perbaikan: Menambahkan ID pada form hapus --}}
                                <form id="delete-form-komponen-{{ $komponen->id }}" action="{{ route('admin.komponen-masakan.destroy', $komponen->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-delete delete-btn" data-id="{{ $komponen->id }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">Tidak ada data komponen masakan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $komponenMasakans->appends(request()->query())->links() }}
        </div>

        <div style="text-align: center; margin-top:30px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus (Sama seperti di produk/index) --}}
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus komponen masakan ini? Jika komponen ini digunakan oleh produk, mungkin akan menyebabkan error atau perlu penyesuaian pada produk terkait.</p>
            <div class="modal-buttons">
                <button type="button" class="btn btn-cancel btn-secondary" onclick="closeModal()">Batal</button>
                <button type="button" class="btn btn-confirm btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fungsi showAlertOnLoad dan logika modal sama seperti di produk/index.blade.php
    // Pastikan variabel idToDelete dan selector form disesuaikan jika perlu (misal komponenIdToDelete)

    function showAlertOnLoad(message, type = 'success') {
        // ... (salin fungsi showAlertOnLoad dari produk/index.blade.php)
        const alertDiv = document.querySelector('.alert.' + (type === 'success' ? 'alert-success' : 'alert-error'));
        if (alertDiv && message) {
            alertDiv.textContent = message;
            alertDiv.style.display = 'block';
            alertDiv.classList.add('show');
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => { alertDiv.style.display = 'none'; }, 300);
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

    let komponenIdToDelete = null; // Variabel spesifik untuk komponen
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    function openModal(id) {
        komponenIdToDelete = id;
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
                komponenIdToDelete = null;
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
            if (komponenIdToDelete) {
                // Menggunakan ID form yang sudah diperbaiki di Blade
                const form = document.getElementById(`delete-form-komponen-${komponenIdToDelete}`);
                if (form) {
                    form.submit();
                } else {
                    console.error('Delete form not found for komponen ID:', komponenIdToDelete);
                    // Fallback jika ID tidak ditemukan (seharusnya tidak terjadi jika ID di form benar)
                    // const dynamicForm = document.createElement('form');
                    // dynamicForm.action = `/admin/komponen-masakan/${komponenIdToDelete}`; // Sesuaikan URL
                    // dynamicForm.method = 'POST';
                    // dynamicForm.innerHTML = `@csrf @method('DELETE')`;
                    // document.body.appendChild(dynamicForm);
                    // dynamicForm.submit();
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
        const modalInternalCloseButton = deleteConfirmationModal.querySelector('.btn-cancel');
        if(modalInternalCloseButton) modalInternalCloseButton.addEventListener('click', closeModal);
    }
</script>
@endpush