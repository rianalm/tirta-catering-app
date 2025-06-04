{{-- resources/views/admin/index_pesanan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@push('styles')
<style>
    /* Gaya CSS yang sebelumnya ada di <style> tag di file asli dipindahkan ke sini */
    /* (Saya akan menyalin sebagian besar gaya yang relevan dari file asli Anda) */
    .container-content { /* Menggantikan .container lama jika ada */
        /* Styling untuk container konten spesifik, jika berbeda dari layout utama */
    }
    /* Styling untuk alert, tombol, filter, tabel, paginasi, dan modal */
    .alert {
        padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 1em; font-weight: 500;
    }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    .btn-create {
        display: inline-block; background-color: #007bff; color: white; padding: 12px 25px;
        border: none; border-radius: 8px; cursor: pointer; font-size: 1.1em;
        font-weight: 600; text-decoration: none; transition: background-color 0.2s ease-in-out;
        margin-bottom: 25px;
    }
    .btn-create:hover { background-color: #0056b3; }

    .filter-search-container {
        display: flex; justify-content: space-between; align-items: center;
        gap: 20px; margin-bottom: 25px; background-color: #f8f9fa;
        padding: 15px 20px; border-radius: 10px; border: 1px solid #e9ecef;
        flex-wrap: wrap; /* Agar responsif */
    }
    .filter-group, .search-group { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-group label, .search-group label { font-weight: 600; color: #495057; white-space: nowrap; }
    .filter-group select, .search-group input[type="text"], .status-select {
        padding: 10px 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box; transition: border-color 0.2s;
        flex-grow: 1;
    }
    .filter-group select:focus, .search-group input[type="text"]:focus, .status-select:focus {
        border-color: #007bff; outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    .search-group input[type="text"] { min-width: 200px; }
    .search-group form { display: flex; gap: 10px; width: auto; flex-grow: 1; } /* Disesuaikan agar lebih fleksibel */


    .table-responsive { overflow-x: auto; }
    table {
        width: 100%; border-collapse: collapse; margin-top: 0; /* Disesuaikan karena header sudah ada */
        background-color: #fff; border-radius: 10px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    th, td {
        padding: 15px; text-align: left; border-bottom: 1px solid #eceeef; vertical-align: middle;
    }
    th {
        background-color: #f8f9fa; color: #495057; font-weight: 700;
        text-transform: uppercase; font-size: 0.9em;
    }
    tbody tr:hover { background-color: #f2f2f2; }

    .btn-info, .btn-success, .btn-danger { /* Diambil dari .actions di layout */
        color: white; border: none; border-radius: 6px; cursor: pointer;
        transition: background-color 0.2s; text-decoration: none;
    }
    .btn-sm { padding: 7px 12px; font-size: 0.85em; margin-left: 5px; }

    /* Paginasi */
    .pagination-container {
        margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 10px;
    }
    .pagination-container .pagination {
        display: flex; list-style: none; padding: 0; margin: 0;
        border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .pagination-container .page-item { display: inline-block; }
    .pagination-container .page-link {
        display: block; padding: 10px 15px; color: #007bff; text-decoration: none;
        border: 1px solid #dee2e6; margin-left: -1px;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
    }
    .pagination-container .page-item:first-child .page-link { border-top-left-radius: 8px; border-bottom-left-radius: 8px;}
    .pagination-container .page-item:last-child .page-link { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
    .pagination-container .page-item.active .page-link { background-color: #007bff; color: white; border-color: #007bff; }
    .pagination-container .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #e9ecef; }
    .pagination-container .page-link:hover:not(.active):not(.disabled) { background-color: #e9ecef; color: #0056b3; border-color: #0056b3; }

    /* Modal */
    #deleteConfirmationModal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);
        justify-content: center; align-items: center;
    }
    #deleteConfirmationModal > div {
        background-color: #fefefe; padding: 30px; border: 1px solid #ddd;
        border-radius: 12px; max-width: 450px; width: 90%; text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2); position: relative;
        transform: translateY(-20px); opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    #deleteConfirmationModal.show > div { transform: translateY(0); opacity: 1; }
    #deleteConfirmationModal h3 { color: #dc3545; margin-top: 0; margin-bottom: 20px; font-size: 1.8em; }
    #deleteConfirmationModal p { margin-bottom: 30px; font-size: 1.1em; line-height: 1.6; color: #555; }
    #deleteConfirmationModal .modal-buttons { display: flex; justify-content: space-around; gap: 15px; }
    #deleteConfirmationModal .modal-buttons button {
        padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer;
        font-size: 1em; font-weight: 600; flex-grow: 1; transition: background-color 0.2s;
    }
    #deleteConfirmationModal .modal-buttons button.cancel { background-color: #6c757d; color: white; }
    #deleteConfirmationModal .modal-buttons button.cancel:hover { background-color: #5a6268; }
    #deleteConfirmationModal .modal-buttons button.confirm { background-color: #dc3545; color: white; }
    #deleteConfirmationModal .modal-buttons button.confirm:hover { background-color: #c82333; }
    .close-button {
        color: #aaa; float: right; font-size: 30px; font-weight: bold;
        position: absolute; top: 15px; right: 20px; cursor: pointer;
    }
    .close-button:hover, .close-button:focus { color: #333; text-decoration: none; cursor: pointer; }

    /* Responsive table (jika diperlukan, tapi layout utama sudah responsif) */
    @media (max-width: 768px) {
        /* Styling untuk layar kecil jika ada yang spesifik untuk tabel ini */
        .filter-search-container { flex-direction: column; align-items: stretch; }
        .search-group form { flex-direction: column; }
        .search-group input[type="text"], .search-group button, .search-group a { width: 100%; margin-left:0 !important; }

        /* Anda mungkin perlu menyesuaikan atau menghapus bagian ini jika bentrok dengan CSS tabel responsif global */
        /* thead tr { display: none; }
        tr { display: block; margin-bottom: .625em; }
        td { display: block; text-align: right; font-size: .8em; border-bottom: 1px dotted; }
        td::before { content: attr(data-label); float: left; font-weight: bold; text-transform: uppercase; }
        td:last-child { border-bottom: 0; } */
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

        <a href="{{ route('admin.pesanan.create') }}" class="btn-create">Tambah Pesanan Baru</a>

        <div class="filter-search-container">
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

            <div class="search-group">
                <form action="{{ route('admin.pesanan.index') }}" method="GET">
                    @if ($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <label for="searchInput" class="sr-only">Cari Pesanan:</label> {{-- sr-only class for accessibility if label is visually hidden --}}
                    <input type="text" id="searchInput" name="search" placeholder="Cari pelanggan/telepon/ID..." value="{{ $searchQuery ?? '' }}">
                    <button type="submit" class="btn btn-info btn-sm">Cari</button>
                    @if ($searchQuery || $statusFilter)
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-sm" style="background-color: #6c757d;">Reset</a>
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
                            <td data-label="Status">
                                <select class="status-select" data-id="{{ $pesanan->id }}" style="width:100%; max-width:150px;">
                                    @foreach ($allStatuses as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $pesanan->status_pesanan == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst($statusOption) }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Jika Anda ingin badge juga (pastikan CSS .status-badge ada dan JSnya sesuai) --}}
                                {{-- <span class="status-badge {{ strtolower($pesanan->status_pesanan) }}">{{ ucfirst($pesanan->status_pesanan) }}</span> --}}
                            </td>
                            <td data-label="Aksi" class="actions">
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

        <div class="pagination-container">
            {{ $pesanans->appends(request()->except('page'))->links() }}
        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div id="deleteConfirmationModal" style="display: none;"> {{-- Pastikan modal ini ada di luar .container-content jika posisinya fixed global --}}
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
    // CSRF Token for AJAX requests
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

    function showAjaxMessage(message, type = 'success') {
        const container = document.getElementById('ajax-message-container');
        const msgDiv = document.getElementById('ajax-message');
        
        msgDiv.className = 'alert'; 
        msgDiv.textContent = '';

        msgDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-error');
        msgDiv.textContent = message;
        container.style.display = 'block';

        setTimeout(() => {
            container.style.display = 'none';
        }, 5000);
    }

    let pesananIdToDelete = null;
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const statusFilterSelect = document.getElementById('statusFilter');

    function openModal(pesananId) {
        pesananIdToDelete = pesananId;
        deleteConfirmationModal.style.display = 'flex'; // Menggunakan flex untuk centering
        setTimeout(() => { deleteConfirmationModal.classList.add('show'); }, 10);
    }

    function closeModal() {
        deleteConfirmationModal.classList.remove('show');
        setTimeout(() => {
            deleteConfirmationModal.style.display = 'none';
            pesananIdToDelete = null;
        }, 300); // Waktu sesuai transisi CSS
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
    
    // Pastikan modal ada sebelum menambahkan event listener
    if(deleteConfirmationModal){
        deleteConfirmationModal.addEventListener('click', function(event) {
            // Jika klik dilakukan pada modal backdrop (bukan konten modalnya)
            if (event.target === deleteConfirmationModal) {
                closeModal();
            }
        });
        // Event listener untuk tombol close di dalam modal (jika ada)
        const modalCloseButton = deleteConfirmationModal.querySelector('.close-button');
        if(modalCloseButton){
            modalCloseButton.addEventListener('click', closeModal);
        }
    }


    if (statusFilterSelect) {
        statusFilterSelect.addEventListener('change', function() {
            window.location.href = this.value;
        });
    }

    document.querySelectorAll('.status-select').forEach(selectElement => {
        selectElement.addEventListener('change', function() {
            const pesananId = this.dataset.id;
            const newStatus = this.value;
            const originalSelectValue = this.querySelector(`option[value="${this.dataset.originalStatus}"]`); // Simpan status awal jika perlu rollback

            // Simpan status original jika belum ada, untuk rollback
            if (!this.dataset.originalStatus) {
                 this.dataset.originalStatus = this.options[this.selectedIndex].value; // Atau status sebelum diubah
                 // Untuk mendapatkan status sebelum diubah dengan lebih akurat, Anda mungkin perlu menyimpan status saat ini
                 // sebelum fetch, atau mengambilnya dari properti lain jika ada.
                 // Contoh sederhana:
                 const currentSelectedOption = Array.from(this.options).find(opt => opt.selected);
                 this.dataset.originalStatus = currentSelectedOption ? currentSelectedOption.value : newStatus;

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
                        // Simpan status sebelum gagal untuk revert
                        this.dataset.failedStatus = newStatus; 
                        throw new Error(errorData.message || 'Gagal memperbarui status.');
                    });
                }
                return response.json();
            })
            .then(data => {
                showAjaxMessage(data.message, 'success');
                this.dataset.originalStatus = newStatus; // Update status original setelah berhasil
                // Jika Anda ingin update elemen lain (misal badge), lakukan di sini
            })
            .catch(error => {
                showAjaxMessage(error.message || 'Terjadi kesalahan.', 'error');
                // Revert ke status original jika ada
                if (this.dataset.originalStatus && this.value !== this.dataset.originalStatus) {
                   this.value = this.dataset.originalStatus;
                }
            });
        });
         // Simpan status awal saat halaman dimuat
        selectElement.dataset.originalStatus = selectElement.value;
    });
</script>
@endpush