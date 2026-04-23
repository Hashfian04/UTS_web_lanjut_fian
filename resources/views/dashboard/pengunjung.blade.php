@extends('layouts.app')
@section('title', 'Dashboard Pengunjung')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-person me-2 text-secondary"></i>Selamat Datang!
        </h1>
        <p class="text-muted mb-0 mt-1">Halo, <strong>{{ Auth::user()->name }}</strong>. Anda sedang masuk sebagai Pengunjung</p>
    </div>
    <span class="badge bg-secondary px-3 py-2 fs-6"><i class="bi bi-person me-1"></i>Pengunjung</span>
</div>

{{-- Upgrade Banner --}}
<div class="card mb-4 p-4" style="background:linear-gradient(135deg,#4f46e5,#6366f1);border:none;">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold text-white mb-2">
                <i class="bi bi-stars me-2"></i>Upgrade ke Peminjam
            </h5>
            <p class="mb-3" style="color:rgba(255,255,255,.85);font-size:.9rem;">
                Sebagai Peminjam, Anda mendapatkan akses ke sinopsis buku, bahkan dapat mengunduh softcopy jika diperbolehkan. Hubungi petugas perpustakaan untuk upgrade akun.
            </p>
            <div class="d-flex gap-2">
                <button class="btn" style="background:#fff;color:#4f46e5;font-weight:600;border-radius:8px;" disabled>
                    <i class="bi bi-person-plus me-1"></i>Hubungi Petugas
                </button>
                <span class="badge align-self-center" style="background:rgba(255,255,255,.2);color:#fff;padding:.5em 1em;border-radius:20px;">
                    Fitur segera hadir
                </span>
            </div>
        </div>
        <div class="col-md-4 text-center mt-3 mt-md-0">
            <div style="font-size:5rem;">📚</div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4 text-center">
            <div class="fw-bold" style="font-size:3rem;color:#4f46e5;">{{ $totalBuku }}</div>
            <div class="text-muted">Buku Tersedia di Perpustakaan</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-tag me-1 text-primary"></i>Kategori Populer</h6>
            @foreach($kategoris->take(5) as $kat)
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="badge-kategori">{{ $kat->nama }}</span>
                <span class="text-muted" style="font-size:.82rem;">{{ $kat->bukus_count }} buku</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Hak akses Pengunjung --}}
<div class="card mb-4">
    <div class="card-header"><h5><i class="bi bi-info-circle me-2"></i>Hak Akses Anda</h5></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 text-center rounded" style="background:#ecfdf5;">
                    <i class="bi bi-search text-success fs-2 mb-2"></i>
                    <div class="fw-semibold">Cari Buku</div>
                    <small class="text-muted">Cari berdasarkan judul, penulis, atau kategori</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 text-center rounded" style="background:#eef2ff;">
                    <i class="bi bi-card-text text-primary fs-2 mb-2"></i>
                    <div class="fw-semibold">Lihat Data Buku</div>
                    <small class="text-muted">Info dasar: judul, penulis, penerbit, tahun, stok</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 text-center rounded" style="background:#fef2f2;">
                    <i class="bi bi-lock text-danger fs-2 mb-2"></i>
                    <div class="fw-semibold">Sinopsis & Download</div>
                    <small class="text-muted">Perlu upgrade ke Peminjam untuk akses fitur ini</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Buku Terbaru --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-journals me-2"></i>Koleksi Buku</h5>
        <a href="{{ route('buku.index') }}" class="btn btn-sm" style="background:#fff;color:#4f46e5;border-radius:8px;font-weight:600;font-size:.82rem;">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Cover</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Tahun</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($bukuTerbaru as $buku)
                    <tr>
                        <td><img src="{{ $buku->cover_url }}" class="cover-thumb" onerror="this.src='https://via.placeholder.com/48x64?text=?'"></td>
                        <td class="fw-semibold" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td><span class="badge-kategori">{{ $buku->kategori->nama ?? '—' }}</span></td>
                        <td>{{ $buku->tahun_terbit }}</td>
                        <td>
                            <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @if($bukuTerbaru->isEmpty())
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada buku.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
