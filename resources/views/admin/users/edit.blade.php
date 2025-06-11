@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@push('styles')
<style>
    .container-content { max-width: 700px; margin: 0 auto; }
    .content-header h1 { text-align: center; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
        font-size: 1em; box-sizing: border-box;
    }
    .roles-group { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
    .role-item { display: flex; align-items: center; margin-bottom: 10px; }
    .role-item input { margin-right: 10px; transform: scale(1.2); }
    .role-item label { margin-bottom: 0; font-weight: 500; }
    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; display: block; }
    .alert-danger ul { margin: 0; padding-left: 20px; list-style: disc; }
    .password-info { font-size: 0.8em; color: #6c757d; }
</style>
@endpush

@section('content')
    <div class="container-content">
        <div class="content-header">
            <h1>Edit User: {{ $user->name }}</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <strong>Oops! Ada beberapa masalah dengan input Anda:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Penting untuk metode update --}}

            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru:</label>
                <input type="password" id="password" name="password">
                <small class="password-info">Kosongkan jika tidak ingin mengubah password.</small>
                @error('password') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="form-group">
                <label>Peran (Role):</label>
                <div class="roles-group">
                    @forelse ($roles as $role)
                        <div class="role-item">
                            <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->name }}"
                            {{ ( is_array(old('roles')) ? in_array($role->name, old('roles')) : $user->hasRole($role->name) ) ? 'checked' : '' }}>
                            <label for="role_{{ $role->id }}">{{ ucfirst($role->name) }}</label>
                        </div>
                    @empty
                        <p>Belum ada peran (role) yang terdaftar.</p>
                    @endforelse
                </div>
                 @error('roles') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection