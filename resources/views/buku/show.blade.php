@extends('layouts.app')

@section('title', $buku->judul)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ isset($user) && $user ? route('buku.index') : route('katalog') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-book me-2 text-primary"></i>Detail Buku
        </h1>
        <p class="text-muted mb-0 mt-1" style="font-size:.88rem;">Informasi lengkap koleksi buku</p>
    </div>
</div>

{{-- Role badge jika admin --}}
@if(isset($user) && $user && $user->canManageBuku())
<div class="alert d-flex align-items-center gap-2 mb-3" style="background:#eef2ff;color:#4f46e5;border:none;border-radius:12px;font-size:.88rem;">
    <i class="bi bi-eye fs-5"></i>
    <span>Anda melihat sebagai <strong>{{ $user->role_label }}</strong> — semua konten termasuk sinopsis dan file ditampilkan.</span>
    @if($buku->isHidden())
        <span class="badge bg-secondary ms-1"><i class="bi bi-eye-slash me-1"></i>Tersembunyi dari publik</span>
    @endif
</div>
@endif

<div class="row g-4">
    {{-- Cover & Aksi --}}
    <div class="col-lg-3 text-center">
        <div class="card mb-3">
            <div class="card-body p-4">
                <img src="{{ $buku->cover_url }}" alt="Cover {{ $buku->judul }}"
                     style="width:100%;max-width:200px;height:260px;object-fit:cover;border-radius:12px;border:2px solid #e2e8f0;box-shadow:0 8px 24px rgba(79,70,229,.12);"
                     onerror="this.src='https://via.placeholder.com/200x260?text=No+Cover'">

                <div class="mt-3">
                    <span class="badge fs-6 px-3 py-2 {{ $buku->stok > 5 ? 'bg-success' : ($buku->stok > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                        <i class="bi bi-stack me-1"></i>Stok: {{ $buku->stok }}
                    </span>
                </div>

                {{-- Visibility badge --}}
                <div class="mt-2">
                    <span class="badge bg-{{ $buku->vis_color }} px-2 py-1">
                        <i class="bi {{ $buku->vis_icon }} me-1"></i>{{ $buku->vis_label }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Aksi sesuai role --}}
        <div class="d-grid gap-2">
            {{-- Petugas & Super Admin: bisa edit --}}
            @if(isset($user) && $user && $user->canManageBuku())
                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning text-white">
                    <i class="bi bi-pencil me-1"></i>Edit Buku
                </a>
                <form action="{{ route('buku.destroy', $buku) }}" method="POST"
                      onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-1"></i>Hapus Buku
                    </button>
                </form>
            @endif

            {{-- Peminjam: download jika diizinkan --}}
            @if($canDownload)
                <a href="{{ Storage::url($buku->file_path) }}" class="btn btn-success" download>
                    <i class="bi bi-download me-1"></i>Download Softcopy
                </a>
            @elseif(isset($user) && $user && $user->isPeminjam() && !$canDownload)
                <button class="btn btn-outline-secondary" disabled>
                    <i class="bi bi-lock me-1"></i>Download Tidak Tersedia
                </button>
            @endif

            {{-- Pengunjung: lock semua extra --}}
            @if(isset($user) && $user && $user->isPengunjung())
                <div class="p-3 rounded text-center" style="background:#fef2f2;border:1px dashed #fca5a5;">
                    <i class="bi bi-lock-fill text-danger fs-4 mb-1"></i>
                    <p class="mb-1 text-danger" style="font-size:.82rem;font-weight:600;">Akses Terbatas</p>
                    <p class="mb-0 text-muted" style="font-size:.78rem;">Upgrade ke Peminjam untuk baca sinopsis & download</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Detail --}}
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-info-circle me-2"></i>Informasi Buku</h5></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><th style="width:180px" class="ps-4 text-muted fw-normal">Judul</th><td class="fw-semibold fs-5">{{ $buku->judul }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Penulis</th><td>{{ $buku->penulis }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Penerbit</th><td>{{ $buku->penerbit }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Tahun Terbit</th><td>{{ $buku->tahun_terbit }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Edisi</th><td>{{ $buku->edisi ?? '—' }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Jumlah Halaman</th><td>{{ $buku->jumlah_halaman ? number_format($buku->jumlah_halaman) . ' hal.' : '—' }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">Bahasa</th><td>{{ $buku->bahasa }}</td></tr>
                        <tr><th class="ps-4 text-muted fw-normal">ISBN</th><td>{{ $buku->isbn ?? '—' }}</td></tr>
                        <tr>
                            <th class="ps-4 text-muted fw-normal">Kategori</th>
                            <td><span class="badge-kategori">{{ $buku->kategori->nama ?? '—' }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted fw-normal">Lokasi Rak</th>
                            <td>
                                @if($buku->rak)
                                    <span class="badge-rak">{{ $buku->rak->kode_rak }}</span>
                                    <small class="text-muted ms-1">{{ $buku->rak->lokasi }}</small>
                                @else <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Deskripsi: tampil sesuai hak akses --}}
        @if($canSeeSynopsis && $buku->deskripsi)
        <div class="card" style="border-color:#d1fae5;">
            <div class="card-header" style="background:linear-gradient(135deg,#059669,#10b981);">
                <h5><i class="bi bi-file-text me-2"></i>Sinopsis / Deskripsi</h5>
            </div>
            <div class="card-body p-4">
                <p class="mb-0" style="line-height:1.8;color:#334155;">{{ $buku->deskripsi }}</p>
            </div>
        </div>
        @elseif(!$canSeeSynopsis && $buku->deskripsi)
        <div class="card" style="border-color:#e2e8f0;">
            <div class="card-header" style="background:linear-gradient(135deg,#94a3b8,#cbd5e1);">
                <h5><i class="bi bi-lock me-2"></i>Sinopsis / Deskripsi</h5>
            </div>
            <div class="card-body p-4 text-center">
                <i class="bi bi-lock-fill display-5 text-secondary mb-3"></i>
                <h6 class="fw-bold">Sinopsis Tidak Tersedia</h6>
                @if(!isset($user) || !$user)
                    <p class="text-muted mb-3" style="font-size:.9rem;">Silakan <a href="{{ route('login') }}">login</a> dan daftarkan diri sebagai Peminjam untuk membaca sinopsis.</p>
                @elseif($user->isPengunjung())
                    <p class="text-muted mb-3" style="font-size:.9rem;">Upgrade akun Anda ke <strong>Peminjam</strong> untuk membaca sinopsis buku ini. Hubungi petugas perpustakaan.</p>
                @else
                    <p class="text-muted mb-3" style="font-size:.9rem;">Buku ini belum memiliki akses sinopsis. Silakan tunggu izin dari administrator.</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
