<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Tirta Catering</title>
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
            max-width: 1200px;
            margin: 20px auto;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.5em;
            font-weight: 700;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error { /* Styling for error messages (e.g., from AJAX) */
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn-create {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
            margin-bottom: 25px;
        }

        .btn-create:hover {
            background-color: #0056b3;
        }

        .filter-search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .filter-group, .search-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label, .search-group label {
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
        }

        .filter-group select, .search-group input[type="text"], .status-select {
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.2s;
            flex-grow: 1;
        }

        .filter-group select:focus, .search-group input[type="text"]:focus, .status-select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .search-group input[type="text"] {
            min-width: 200px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eceeef;
            vertical-align: middle;
        }

        th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .btn-info, .btn-success, .btn-danger {
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-info {
            background-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
        }

        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 7px 12px;
            font-size: 0.85em;
            margin-left: 5px;
        }

        /* Styling untuk paginasi */
        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .pagination-container .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .pagination-container .page-item {
            display: inline-block;
        }

        .pagination-container .page-link {
            display: block;
            padding: 10px 15px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #dee2e6;
            margin-left: -1px;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        }

        .pagination-container .page-item:first-child .page-link {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .pagination-container .page-item:last-child .page-link {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .pagination-container .page-item.active .page-link {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination-container .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #e9ecef;
        }

        .pagination-container .page-link:hover:not(.active):not(.disabled) {
            background-color: #e9ecef;
            color: #0056b3;
            border-color: #0056b3;
        }


        /* Styling untuk modal */
        #deleteConfirmationModal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        #deleteConfirmationModal > div {
            background-color: #fefefe;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 12px;
            max-width: 450px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            transform: translateY(-20px);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        #deleteConfirmationModal.show > div {
            transform: translateY(0);
            opacity: 1;
        }

        #deleteConfirmationModal h3 {
            color: #dc3545;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        #deleteConfirmationModal p {
            margin-bottom: 30px;
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
        }

        #deleteConfirmationModal .modal-buttons {
            display: flex;
            justify-content: space-around;
            gap: 15px;
        }

        #deleteConfirmationModal .modal-buttons button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            flex-grow: 1;
            transition: background-color 0.2s;
        }

        #deleteConfirmationModal .modal-buttons button.cancel {
            background-color: #6c757d;
            color: white;
        }

        #deleteConfirmationModal .modal-buttons button.cancel:hover {
            background-color: #5a6268;
        }

        #deleteConfirmationModal .modal-buttons button.confirm {
            background-color: #dc3545;
            color: white;
        }

        #deleteConfirmationModal .modal-buttons button.confirm:hover {
            background-color: #c82333;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 30px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #333;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Pesanan</h1>

        {{-- Menampilkan pesan sukses dari session (misal setelah simpan/hapus) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Kontainer untuk pesan AJAX (sukses/error) --}}
        <div id="ajax-message-container" style="display: none; margin-bottom: 20px;">
            <div id="ajax-message" class="alert"></div>
        </div>

        <a href="{{ route('admin.pesanan.create') }}" class="btn-create">Tambah Pesanan Baru</a>

        <div class="filter-search-container">
            {{-- Filter Status Pesanan --}}
            <div class="filter-group">
                <label for="statusFilter">Filter Status:</label>
                <select id="statusFilter">
                    <option value="{{ route('admin.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => null])) }}"
                            {{ empty($statusFilter) ? 'selected' : '' }}>
                        Semua Status
                    </option>
                    @foreach ($allStatuses as $status)
                        <option value="{{ route('admin.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => $status])) }}"
                                {{ $statusFilter == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Form Pencarian Pesanan --}}
            <div class="search-group">
                <form action="{{ route('admin.pesanan.index') }}" method="GET" style="display: flex; gap: 10px; width: 100%;">
                    @if ($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <label for="searchInput">Cari Pesanan:</label>
                    <input type="text" id="searchInput" name="search" placeholder="Cari pelanggan/telepon/ID..." value="{{ $searchQuery ?? '' }}">
                    <button type="submit" class="btn btn-info btn-sm" style="padding: 10px 15px; margin-left: 0;">Cari</button>
                    @if ($searchQuery || $statusFilter)
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-sm" style="background-color: #6c757d; padding: 10px 15px; margin-left: 0;">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Telepon</th>
                        <th>Tanggal Kirim</th>
                        <th>Total Harga</th>
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
                            <td data-label="Tanggal Kirim">{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d-m-Y') }}</td>
                            <td data-label="Total Harga">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            {{-- KOLOM STATUS BARU (DENGAN DROPDOWN) --}}
                            <td data-label="Status">
                                <select class="status-select" data-id="{{ $pesanan->id }}">
                                    @foreach ($allStatuses as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $pesanan->status_pesanan == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst($statusOption) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            {{-- AKHIR KOLOM STATUS BARU --}}
                            <td data-label="Aksi">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn btn-success btn-sm">Edit</a>

                                <form id="delete-form-{{ $pesanan->id }}" action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $pesanan->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada pesanan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BAGIAN PAGINASI --}}
        <div class="pagination-container">
            {{ $pesanans->appends(request()->except('page'))->links() }}
        </div>

    </div>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <div id="deleteConfirmationModal">
        <div>
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.</p>
            <div class="modal-buttons">
                <button type="button" class="cancel" onclick="closeModal()">Batal</button>
                <button type="button" class="confirm" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
    {{-- END MODAL KONFIRMASI HAPUS --}}

     {{-- Script JavaScript --}}
    <script>
        // CSRF Token for AJAX requests
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

        // Function to show AJAX messages
        function showAjaxMessage(message, type = 'success') {
            const container = document.getElementById('ajax-message-container');
            const msgDiv = document.getElementById('ajax-message');
            
            // Clear previous classes and text
            msgDiv.className = 'alert'; 
            msgDiv.textContent = '';

            // Add new class and text
            msgDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-error');
            msgDiv.textContent = message;
            container.style.display = 'block';

            // Hide message after 5 seconds
            setTimeout(() => {
                container.style.display = 'none';
            }, 5000);
        }

        // --- JavaScript untuk Modal Hapus ---
        let pesananIdToDelete = null;
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const statusFilterSelect = document.getElementById('statusFilter'); // Pastikan ini ada di HTML Anda

        function openModal(pesananId) {
            pesananIdToDelete = pesananId;
            deleteConfirmationModal.style.display = 'flex';
            setTimeout(() => { deleteConfirmationModal.classList.add('show'); }, 10);
        }

        function closeModal() {
            deleteConfirmationModal.classList.remove('show');
            setTimeout(() => {
                deleteConfirmationModal.style.display = 'none';
                pesananIdToDelete = null;
            }, 300);
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const pesananId = this.dataset.id;
                openModal(pesananId);
            });
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (pesananIdToDelete) {
                const deleteForm = document.getElementById(`delete-form-${pesananIdToDelete}`);
                if (deleteForm) {
                    deleteForm.submit();
                }
            }
        });

        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal();
            }
        });

        // Pastikan Anda memiliki elemen dengan ID 'statusFilter' jika Anda menggunakan ini
        if (statusFilterSelect) {
            statusFilterSelect.addEventListener('change', function() {
                window.location.href = this.value;
            });
        }


        // --- JavaScript BARU untuk Update Status AJAX (MODIFIKASI DI SINI) ---
        document.querySelectorAll('.status-select').forEach(selectElement => {
            selectElement.addEventListener('change', function() {
                const pesananId = this.dataset.id;
                const newStatus = this.value;
                const currentRow = this.closest('tr'); // Dapatkan baris tabel saat ini
                const statusBadgeSpan = currentRow ? currentRow.querySelector('.status-badge') : null; // Dapatkan span badge

                fetch(`/admin/pesanan/${pesananId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN // Menggunakan const CSRF_TOKEN
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Gagal memperbarui status. Silakan coba lagi.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showAjaxMessage(data.message, 'success');
                    
                    // --- Bagian BARU untuk memperbarui tampilan badge secara instan ---
                    if (statusBadgeSpan) {
                        // Hapus semua kelas status lama
                        statusBadgeSpan.classList.remove('baru', 'diproses', 'selesai', 'dibatalkan');
                        // Tambahkan kelas status baru (lowercase)
                        statusBadgeSpan.classList.add(newStatus.toLowerCase());
                        // Perbarui teks badge (kapitalisasi huruf pertama)
                        statusBadgeSpan.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    }
                    // --- END Bagian BARU ---

                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    showAjaxMessage(error.message || 'Terjadi kesalahan saat memperbarui status.', 'error');
                    // Opsional: Jika terjadi error, kembalikan dropdown ke status sebelumnya
                    // this.value = this.dataset.oldStatus; // Anda perlu menyimpan status lama di dataset
                });
            });
        });
    </script>
</body>
</html> 