@extends('layouts.admin')

@section('title', 'Kelola User')

@push('styles')
<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
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
    .user-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }
    .user-card {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border-top: 4px solid #6c757d; 
    }
    /* Warna border atas kartu berdasarkan peran */
    .user-card.role-admin { border-top-color: #dc3545; }
    .user-card.role-tim_dapur { border-top-color: #007bff; }
    .user-card.role-tim_packing { border-top-color: #17a2b8; }
    .user-card.role-driver { border-top-color: #28a745; }

    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .user-card-body {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-grow: 1;
    }
    .user-card-avatar i {
        font-size: 3.5em;
        color: #adb5bd;
    }
    .user-card-info .user-name {
        font-weight: 600;
        font-size: 1.2em;
        color: #2c3e50;
    }
    .user-card-info .user-email {
        font-size: 0.9em;
        color: #777;
        word-break: break-all;
    }
    .user-card-roles {
        margin-top: 8px;
    }
    .role-badge {
        display: inline-block; padding: 4px 8px; font-size: 0.75em;
        font-weight: 700; border-radius: 4px; color: #fff;
        text-transform: capitalize;
    }
    .role-badge.admin { background-color: #dc3545; }
    .role-badge.tim_dapur { background-color: #007bff; }
    .role-badge.tim_packing { background-color: #17a2b8; }
    .role-badge.driver { background-color: #28a745; }

    .user-card-actions {
        background-color: #f8f9fa;
        padding: 10px 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .user-card-actions .btn-sm { width: 38px; height: 32px; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Kelola User</h1>
        </div>

        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> Tambah User Baru
            </a>
            <form action="{{ route('admin.users.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                 @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div class="user-card-grid">
            @forelse ($users as $user)
                <div class="user-card role-{{ strtolower($user->getRoleNames()->first() ?? '') }}">
                    <div class="user-card-body">
                        <div class="user-card-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="user-card-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                            <div class="user-card-roles">
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $roleName)
                                        <span class="role-badge {{ str_replace(' ', '_', $roleName) }}">{{ $roleName }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="user-card-actions">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        
                        @if(auth()->user()->id != $user->id && $user->id != 1)
                            <form id="delete-form-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="btn btn-danger btn-sm delete-user-btn" data-id="{{ $user->id }}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info" style="grid-column: 1 / -1;">Tidak ada data user yang ditemukan.</div>
            @endforelse
        </div>

        <div class="pagination-container">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

    <div id="deleteUserModal" class="custom-modal">
        <div class="custom-modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak bisa dibatalkan.</p>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel btn btn-secondary">Batal</button>
                <button type="button" class="btn-confirm btn-danger" id="confirmUserDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteUserModal = document.getElementById('deleteUserModal');
        if (!deleteUserModal) return;
        
        const confirmDeleteBtn = document.getElementById('confirmUserDeleteBtn');
        const cancelDeleteBtns = deleteUserModal.querySelectorAll('.btn-cancel');
        let userIdToDelete = null;

        function openModal(id) {
            userIdToDelete = id;
            deleteUserModal.style.display = 'flex';
            setTimeout(() => { deleteUserModal.classList.add('show'); }, 10);
        }

        function closeModal() {
            deleteUserModal.classList.remove('show');
            setTimeout(() => {
                deleteUserModal.style.display = 'none';
                userIdToDelete = null;
            }, 300);
        }

        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                openModal(this.dataset.id);
            });
        });

        if(confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                if (userIdToDelete) {
                    const form = document.getElementById(`delete-form-user-${userIdToDelete}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
        
        cancelDeleteBtns.forEach(btn => btn.addEventListener('click', closeModal));
        deleteUserModal.addEventListener('click', function(event) {
            if (event.target === deleteUserModal) {
                closeModal();
            }
        });
    });
</script>
@endpush