@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="fw-bold mb-0" style="font-size:1.5rem;">
        <i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya
    </h1>
</div>

@if(session('status') === 'profile-updated')
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill"></i><span>Profil berhasil diperbarui.</span>
    </div>
@endif

<div class="row g-4">
    {{-- Update Profile --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-person me-2"></i>Informasi Profil</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('patch')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-lock me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body p-4">
                @if(session('status') === 'password-updated')
                    <div class="alert alert-success mb-3">Password berhasil diperbarui.</div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('put')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password"
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                        @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                        @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-lock me-1"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="card border-danger" style="border-color:#ef4444 !important;">
            <div class="card-header" style="background:linear-gradient(135deg,#ef4444,#f87171);">
                <h5><i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-3" style="font-size:.9rem;">Setelah akun dihapus, semua data akan hilang permanen.</p>
                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')">
                    @csrf @method('delete')
                    <div class="mb-3">
                        <label class="form-label">Masukkan Password untuk konfirmasi</label>
                        <input type="password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror">
                        @error('password', 'userDeletion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
