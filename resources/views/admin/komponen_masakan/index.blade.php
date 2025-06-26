@extends('layouts.admin')

@section('title', 'Manajemen Komponen Masakan')

@push('styles')
<style>
    .actions .btn-sm {
        width: 38px;
        height: 32px;
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
    }
    .table-responsive { overflow-x: auto; }
    table {
        width: 100%; border-collapse: collapse; margin-top: 0;
        background-color: #fff; border-radius: 10px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eceeef; }
    thead th {
        background-color: #f8f9fa; color: #495057; font-weight: 700;
        text-transform: uppercase; font-size: 0.9em;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f2f2f2; }

</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Manajemen Komponen Masakan</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="header-actions">
            <a href="{{ route('admin.komponen-masakan.create') }}" class="btn btn-success">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> Tambah Komponen
            </a>
            <form action="{{ route('admin.komponen-masakan.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari komponen..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.komponen-masakan.index') }}" class="btn btn-secondary" style="text-decoration: none;">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                {{-- ... (Isi tabel Anda tetap sama) ... --}}
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th>Nama Komponen</th>
                        <th>Satuan Dasar</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komponenMasakans as $komponen)
                        <tr>
                            <td>{{ $komponen->id }}</td>
                            <td>{{ $komponen->nama_komponen }}</td>
                            <td>{{ $komponen->satuan_dasar ?? '-' }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.komponen-masakan.edit', $komponen->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form id="delete-form-komponen-{{ $komponen->id }}" action="{{ route('admin.komponen-masakan.destroy', $komponen->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $komponen->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
        <div class="pagination-container">
            {{ $komponenMasakans->appends(request()->query())->links() }}
        </div>
    </div>

    <div id="deleteConfirmationModal" class="custom-modal">
        <div class="custom-modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus komponen ini?</p>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel btn btn-secondary">Batal</button>
                <button type="button" class="btn-confirm btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmationModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtns = deleteModal.querySelectorAll('.btn-cancel');
        let itemToDeleteId = null;

        function openModal(id) {
            itemToDeleteId = id;
            if(deleteModal) {
                deleteModal.style.display = 'flex';
                setTimeout(() => deleteModal.classList.add('show'), 10);
            }
        }

        function closeModal() {
            if(deleteModal) {
                deleteModal.classList.remove('show');
                setTimeout(() => {
                    deleteModal.style.display = 'none';
                    itemToDeleteId = null;
                }, 300);
            }
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                openModal(this.dataset.id);
            });
        });

        if(confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (itemToDeleteId) {
                    const form = document.getElementById(`delete-form-komponen-${itemToDeleteId}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
        
        cancelBtns.forEach(btn => btn.addEventListener('click', closeModal));
        if(deleteModal) {
            deleteModal.addEventListener('click', function(event) {
                if (event.target === deleteModal) {
                    closeModal();
                }
            });
        }
    });
</script>
@endpush