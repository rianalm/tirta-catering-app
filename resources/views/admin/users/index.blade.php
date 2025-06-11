@extends('layouts.admin')

@section('title', 'Kelola User')

@push('styles')
<style>
    /* Styling dasar untuk tabel dan tombol aksi, mirip dengan halaman lain */
    .actions .btn-sm {
        width: 38px;
        height: 32px;
    }
    .role-badge {
        display: inline-block; padding: 4px 8px; font-size: 0.8em;
        font-weight: 600; border-radius: 4px; color: #fff;
        background-color: #6c757d; margin-right: 5px; margin-bottom: 5px;
        text-transform: capitalize;
    }
    .role-badge.admin { background-color: #dc3545; }
    .role-badge.tim_dapur, .role-badge.tim_packing, .role-badge.driver { background-color: #007bff; }
    
    /* Styling untuk Modal (bisa digeneralisasi di layout utama jika mau) */
    .modal {
        display: none; position: fixed; z-index: 1050; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);
        justify-content: center; align-items: center; opacity: 0;
        transition: opacity 0.3s ease-in-out;
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
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>Kelola User</h1>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> Tambah User Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $roleName)
                                        <span class="role-badge {{ str_replace(' ', '_', $roleName) }}">{{ $roleName }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success btn-sm" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                
                                @if(auth()->user()->id != $user->id && $user->id != 1)
                                    <form id="delete-form-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    {{-- MODIFIKASI: Tombol hapus sekarang memicu modal --}}
                                    <button type="button" class="btn btn-danger btn-sm delete-user-btn" data-id="{{ $user->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 25px;">
            {{ $users->links() }}
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS BARU --}}
    <div id="deleteUserModal" class="modal">
        <div class="modal-content">
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
        const confirmDeleteBtn = document.getElementById('confirmUserDeleteBtn');
        const cancelDeleteBtns = deleteUserModal.querySelectorAll('.btn-cancel');
        let userIdToDelete = null;

        // Fungsi untuk membuka modal
        function openModal(id) {
            userIdToDelete = id;
            if(deleteUserModal) {
                deleteUserModal.style.display = 'flex';
                setTimeout(() => { deleteUserModal.classList.add('show'); }, 10);
            }
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            if(deleteUserModal) {
                deleteUserModal.classList.remove('show');
                setTimeout(() => {
                    deleteUserModal.style.display = 'none';
                    userIdToDelete = null;
                }, 300);
            }
        }

        // Tambahkan event listener ke semua tombol hapus di tabel
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                openModal(this.dataset.id);
            });
        });

        // Event listener untuk tombol konfirmasi hapus di dalam modal
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
        
        // Event listener untuk tombol batal dan klik di luar modal
        cancelDeleteBtns.forEach(btn => btn.addEventListener('click', closeModal));
        if(deleteUserModal) {
            deleteUserModal.addEventListener('click', function(event) {
                if (event.target === deleteUserModal) {
                    closeModal();
                }
            });
        }
    });
</script>
@endpush