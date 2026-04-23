@extends('layouts.app')
@section('title', 'Dashboard Peminjam')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-person-check me-2 text-success"></i>Ruang Peminjam
        </h1>
        <p class="text-muted mb-0 mt-1">Halo, <strong>{{ Auth::user()->name }}</strong>! Selamat membaca 📖</p>
    </div>
    <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-person-check me-1"></i>Peminjam</span>
</div>

{{-- Welcome Card --}}
<div class="card mb-4 overflow-hidden">
    <div class="row g-0">
        <div class="col-md-8 p-4">
            <h5 class="fw-bold mb-2">Akses Anda sebagai Peminjam</h5>
            <p class="text-muted mb-3" style="font-size:.9rem;">Anda dapat membaca sinopsis buku yang diizinkan, dan mengunduh softcopy jika tersedia dan diizinkan oleh administrator.</p>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge" style="background:#ecfdf5;color:#065f46;font-size:.82rem;padding:.5em 1em;border-radius:20px;">
                    <i class="bi bi-check-circle me-1"></i>Lihat Detail Buku
                </span>
                <span class="badge" style="background:#ecfdf5;color:#065f46;font-size:.82rem;padding:.5em 1em;border-radius:20px;">
                    <i class="bi bi-check-circle me-1"></i>Baca Sinopsis (jika diizinkan)
                </span>
                <span class="badge" style="background:#ecfdf5;color:#065f46;font-size:.82rem;padding:.5em 1em;border-radius:20px;">
                    <i class="bi bi-check-circle me-1"></i>Download Softcopy (jika tersedia)
                </span>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-center p-4" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">
            <div class="text-center">
                <div class="fw-bold fs-1" style="color:#059669;">{{ $totalTersedia }}</div>
                <div style="color:#065f46;font-size:.9rem;font-weight:600;">Buku Tersedia</div>
            </div>
        </div>
    </div>
</div>

{{-- Buku dengan Sinopsis --}}
<div class="mb-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-journal-text me-2 text-warning"></i>Buku yang Bisa Dibaca Sinopsisnya
    </h5>
    @if($booksWithSynopsis->isEmpty())
        <div class="card p-4 text-center text-muted">
            <i class="bi bi-box display-5 mb-2"></i>
            <p class="mb-0">Belum ada buku dengan sinopsis yang tersedia untuk Anda.</p>
        </div>
    @else
    <div class="row g-3">
        @foreach($booksWithSynopsis as $buku)
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 p-3 d-flex flex-row gap-3">
                <img src="{{ $buku->cover_url }}" style="width:56px;height:76px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;flex-shrink:0;"
                     onerror="this.src='https://via.placeholder.com/56x76?text=?'">
                <div class="overflow-hidden">
                    <div class="fw-semibold" style="font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->judul }}</div>
                    <div class="text-muted" style="font-size:.8rem;">{{ $buku->penulis }}</div>
                    <div class="d-flex gap-1 mt-2 flex-wrap">
                        <span class="badge bg-warning text-dark" style="font-size:.7rem;border-radius:20px;">
                            <i class="bi bi-journal-text me-1"></i>Sinopsis
                        </span>
                        @if($buku->canDownload())
                        <span class="badge bg-success" style="font-size:.7rem;border-radius:20px;">
                            <i class="bi bi-download me-1"></i>Download
                        </span>
                        @endif
                    </div>
                    <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-success mt-2" style="font-size:.78rem;">Baca Sekarang</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Buku dengan Download --}}
<div class="mb-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-download me-2 text-success"></i>Buku yang Bisa Didownload
    </h5>
    @if($booksWithDownload->isEmpty())
        <div class="card p-4 text-center">
            <div class="text-muted">
                <i class="bi bi-file-earmark-x display-5 mb-2"></i>
                <p class="mb-0">Belum ada buku yang tersedia untuk diunduh saat ini.</p>
            </div>
        </div>
    @else
    <div class="row g-3">
        @foreach($booksWithDownload as $buku)
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 p-3 d-flex flex-row gap-3" style="border-color:#d1fae5;">
                <img src="{{ $buku->cover_url }}" style="width:56px;height:76px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;flex-shrink:0;"
                     onerror="this.src='https://via.placeholder.com/56x76?text=?'">
                <div class="overflow-hidden">
                    <div class="fw-semibold" style="font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->judul }}</div>
                    <div class="text-muted" style="font-size:.8rem;">{{ $buku->penulis }}</div>
                    <div class="mt-2">
                        <span class="badge bg-success" style="font-size:.7rem;border-radius:20px;">
                            <i class="bi bi-download me-1"></i>Tersedia
                        </span>
                    </div>
                    <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-success mt-2" style="font-size:.78rem;">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>


{{-- Aksi Cepat Anggota --}}
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="bi bi-lightning-charge me-2"></i>Aksi Cepat</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="d-flex align-items-start p-3 rounded-3 h-100"
                     style="background: #fff7ed; border: 1px solid #fed7aa;">
                    <div class="flex-shrink-0 me-3 mt-1">
                        <div style="width:42px;height:42px;background:#f97316;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-plus-circle text-white fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold mb-1" style="font-size:.9rem;">Tambah Buku</div>
                        <div class="text-muted mb-2" style="font-size:.78rem;">
                            Menambahkan buku baru ke koleksi perpustakaan.
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.7rem;">
                                <i class="bi bi-shield-lock me-1"></i>Admin Only
                            </span>
                        </div>
                        <a href="{{ route('buku.create.anggota') }}"
                           class="btn btn-sm btn-warning"
                           style="font-size:.78rem;">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Buku
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-start p-3 rounded-3 h-100"
                     style="background: #eff6ff; border: 1px solid #bfdbfe;">
                    <div class="flex-shrink-0 me-3 mt-1">
                        <div style="width:42px;height:42px;background:#3b82f6;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-search text-white fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold mb-1" style="font-size:.9rem;">Cari Buku</div>
                        <div class="text-muted mb-2" style="font-size:.78rem;">
                            Telusuri katalog koleksi buku perpustakaan.
                        </div>
                        <a href="{{ route('katalog') }}"
                           class="btn btn-sm btn-primary"
                           style="font-size:.78rem;">
                            <i class="bi bi-search me-1"></i>Buka Katalog
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-start p-3 rounded-3 h-100"
                     style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                    <div class="flex-shrink-0 me-3 mt-1">
                        <div style="width:42px;height:42px;background:#22c55e;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-person text-white fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold mb-1" style="font-size:.9rem;">Profil Saya</div>
                        <div class="text-muted mb-2" style="font-size:.78rem;">
                            Kelola data profil dan akun Anda.
                        </div>
                        <a href="{{ route('profile.edit') }}"
                           class="btn btn-sm btn-success"
                           style="font-size:.78rem;">
                            <i class="bi bi-person-gear me-1"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info Riwayat --}}
<div class="card">
    <div class="card-header"><h5><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman</h5></div>
    <div class="card-body p-4 text-center">
        <i class="bi bi-journal-bookmark display-4 text-secondary mb-3"></i>
        <h6 class="fw-bold">Fitur Riwayat Peminjaman</h6>
        <p class="text-muted mb-0" style="font-size:.9rem;">
            Riwayat peminjaman buku Anda akan tampil di sini setelah fitur manajemen peminjaman sepenuhnya tersedia.
            Hubungi petugas untuk informasi peminjaman Anda saat ini.
        </p>
    </div>
</div>
@endsection

