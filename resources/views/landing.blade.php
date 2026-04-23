@extends('layouts.main')

@section('title', 'Selamat Datang')

@section('content')
{{-- Hero Section --}}
<div class="hero-section text-center py-5 mb-5">
    <div class="hero-badge mb-3">
        <span class="badge px-3 py-2" style="background:#eef2ff;color:#4f46e5;border-radius:20px;font-size:.85rem;font-weight:600;">
            <i class="bi bi-stars me-1"></i> Perpustakaan Digital Modern
        </span>
    </div>

    <h1 class="display-4 fw-black mb-3" style="line-height:1.15;">
        Selamat Datang di<br>
        <span style="background:linear-gradient(135deg,#4f46e5,#818cf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
            Perpustakaan XYZ
        </span>
    </h1>

    <p class="lead mb-4" style="color:#64748b;max-width:520px;margin:0 auto;">
        Platform manajemen koleksi buku terpadu. Discover, manage, and organize your library effortlessly.
    </p>

    {{-- Stat Card: Jumlah Buku --}}
    <div class="d-flex justify-content-center mb-5">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">{{ number_format($jumlahBuku) }}</div>
                <div class="stat-label">Buku Tersedia</div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="cta-box mx-auto p-4 mb-5" style="max-width:480px;">
        <p class="mb-4" style="color:#475569;font-size:1rem;line-height:1.6;">
            <i class="bi bi-lock-fill me-2 text-warning"></i>
            Silakan <strong>Login</strong> untuk mengakses fitur lengkap pengelolaan buku.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5 py-2">
                <i class="bi bi-person-plus me-2"></i>Register
            </a>
        </div>
    </div>
</div>

{{-- Feature Cards --}}
<div class="row g-4 justify-content-center mb-5">
    <div class="col-md-4">
        <div class="feature-card text-center p-4">
            <div class="feature-icon mb-3" style="background:#eef2ff;">
                <i class="bi bi-journals text-primary fs-2"></i>
            </div>
            <h5 class="fw-bold">Kelola Koleksi</h5>
            <p class="text-muted mb-0" style="font-size:.9rem;">Tambah, edit, dan hapus data buku dengan mudah. Lengkap dengan informasi penulis, penerbit, dan cover.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card text-center p-4">
            <div class="feature-icon mb-3" style="background:#ecfdf5;">
                <i class="bi bi-search text-success fs-2"></i>
            </div>
            <h5 class="fw-bold">Cari & Filter</h5>
            <p class="text-muted mb-0" style="font-size:.9rem;">Temukan buku berdasarkan judul, penulis, atau ISBN. Filter berdasarkan kategori dan lokasi rak.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card text-center p-4">
            <div class="feature-icon mb-3" style="background:#fff7ed;">
                <i class="bi bi-shield-lock-fill text-warning fs-2"></i>
            </div>
            <h5 class="fw-bold">Akses Terproteksi</h5>
            <p class="text-muted mb-0" style="font-size:.9rem;">Data koleksi buku hanya bisa diakses oleh admin yang sudah login. Aman dan terpercaya.</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hero-section { animation: fadeUp .6s ease; }
@keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:none; } }

.stat-card {
    display: flex; align-items: center; gap: 1.25rem;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff; border-radius: 20px; padding: 1.25rem 2.5rem;
    box-shadow: 0 12px 40px rgba(79,70,229,.3);
}
.stat-icon { font-size: 2.5rem; opacity: .9; }
.stat-number { font-size: 2.8rem; font-weight: 800; line-height: 1; }
.stat-label { font-size: .9rem; opacity: .85; font-weight: 500; margin-top: .2rem; }

.cta-box {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 16px; box-shadow: 0 4px 20px rgba(79,70,229,.08);
}

.feature-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05); transition: all .25s;
}
.feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(79,70,229,.15); }
.feature-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
</style>
@endpush
