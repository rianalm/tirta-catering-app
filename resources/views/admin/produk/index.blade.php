<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Tirta Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            max-width: 900px;
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

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-actions .search-box {
            display: flex;
            gap: 10px;
        }

        .header-actions .search-box input[type="text"] {
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            width: 250px;
            box-sizing: border-box;
        }

        .header-actions .search-box button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s;
        }

        .header-actions .search-box button:hover {
            background-color: #0056b3;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-add:hover {
            background-color: #218838;
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

        thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .actions .btn-edit,
        .actions .btn-delete,
        .actions .btn-show {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
            color: white;
        }

        .actions .btn-edit {
            background-color: #ffc107;
        }

        .actions .btn-edit:hover {
            background-color: #e0a800;
        }

        .actions .btn-delete {
            background-color: #dc3545;
        }

        .actions .btn-delete:hover {
            background-color: #c82333;
        }

        .actions .btn-show {
            background-color: #17a2b8;
        }

        .actions .btn-show:hover {
            background-color: #138496;
        }

        .pagination {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .pagination a, .pagination span {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            text-decoration: none;
            color: #007bff;
            transition: background-color 0.2s, color 0.2s;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination .current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .disabled {
            color: #6c757d;
            pointer-events: none;
            background-color: #e9ecef;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .back-link:hover {
            background-color: #5a6268;
        }

        /* Alert Messages */
        .ajax-message-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: auto;
            min-width: 300px;
            max-width: 80%;
            display: none; /* Hidden by default */
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .alert.show {
            opacity: 1;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Modal Styling (for delete confirmation) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .modal.show {
            opacity: 1;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            transform: translateY(-20px);
            transition: transform 0.3s ease-in-out;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }

        .modal-content h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .modal-content p {
            margin-bottom: 25px;
            color: #555;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-buttons button {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .modal-buttons .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .modal-buttons .btn-cancel:hover {
            background-color: #5a6268;
        }

        .modal-buttons .btn-confirm {
            background-color: #dc3545;
            color: white;
        }

        .modal-buttons .btn-confirm:hover {
            background-color: #c82333;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .header-actions .search-box {
                width: 100%;
                flex-direction: column;
            }
            .header-actions .search-box input[type="text"],
            .header-actions .search-box button {
                width: 100%;
            }
            .btn-add {
                width: 100%;
                text-align: center;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid #eceeef;
                margin-bottom: 15px;
                border-radius: 8px;
                overflow: hidden;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: #555;
            }
            td:nth-of-type(1):before { content: "ID:"; }
            td:nth-of-type(2):before { content: "Nama Produk:"; }
            td:nth-of-type(3):before { content: "Harga:"; }
            td:nth-of-type(4):before { content: "Satuan:"; }
            td:nth-of-type(5):before { content: "Aksi:"; }

            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manajemen Produk</h1>

        {{-- Pesan Sukses/Error dari Session --}}
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
            <a href="{{ route('admin.produks.create') }}" class="btn-add">Tambah Produk Baru</a>
            <form action="{{ route('admin.produks.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.produks.index') }}" class="btn-reset" style="background-color: #6c757d; color: white; padding: 10px 15px; border-radius: 8px; text-decoration: none;">Reset</a>
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
                            <td>{{ $produk->id }}</td>
                            <td>{{ $produk->nama_produk }}</td>
                            <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $produk->satuan ?? '-' }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.produks.show', $produk->id) }}" class="btn-show">Detail</a>
                                <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete delete-btn" data-id="{{ $produk->id }}">Hapus</button>
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

        {{-- Pagination --}}
        <div class="pagination">
            {{ $produks->links() }}
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak bisa dibatalkan.</p>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="button" class="btn-confirm" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // Function to show messages (for session based alerts)
        function showAlertMessage(message, type = 'success') {
            const alertDiv = document.querySelector('.alert');
            if (alertDiv) {
                alertDiv.classList.remove('alert-success', 'alert-error');
                alertDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-error', 'show');
                alertDiv.textContent = message;
                alertDiv.style.display = 'block';

                setTimeout(() => {
                    alertDiv.classList.remove('show');
                    setTimeout(() => { alertDiv.style.display = 'none'; }, 300);
                }, 5000); // Hide after 5 seconds
            }
        }

        // Check for session messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = "{{ session('success') }}";
            const errorMessage = "{{ session('error') }}";
            if (successMessage) {
                showAlertMessage(successMessage, 'success');
            }
            if (errorMessage) {
                showAlertMessage(errorMessage, 'error');
            }
        });

        // --- JavaScript untuk Modal Hapus ---
        let produkIdToDelete = null;
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        function openModal(produkId) {
            produkIdToDelete = produkId;
            deleteConfirmationModal.style.display = 'flex';
            setTimeout(() => { deleteConfirmationModal.classList.add('show'); }, 10);
        }

        function closeModal() {
            deleteConfirmationModal.classList.remove('show');
            setTimeout(() => {
                deleteConfirmationModal.style.display = 'none';
                produkIdToDelete = null;
            }, 300);
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const produkId = this.dataset.id;
                openModal(produkId);
            });
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (produkIdToDelete) {
                const form = document.querySelector(`form[action$="/produks/${produkIdToDelete}"]`);
                if (form) {
                    form.submit();
                }
            }
        });

        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal();
            }
        });
    </script>
</body>
</html>