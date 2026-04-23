@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-journals me-2 text-primary"></i>Data Buku
        </h1>
        <p class="text-muted mb-0 mt-1" style="font-size:.88rem;">
            Kelola seluruh koleksi buku perpustakaan
        </p>
    </div>
    <a href="{{ route('buku.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>Tambah Buku
    </a>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('buku.index') }}" class="card mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Cari Buku</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Judul, penulis, atau ISBN…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel"></i>
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>
    </div>
</form>

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-table me-2"></i>Daftar Buku</h5>
        <span class="badge bg-white text-primary fw-semibold">{{ $bukus->total() }} buku</span>
    </div>
    <div class="card-body p-0">
        @if($bukus->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Belum ada data buku.
                    <a href="{{ route('buku.create') }}">Tambah sekarang</a>
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th style="width:64px">Cover</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Rak</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th class="text-center" style="width:130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bukus as $i => $buku)
                        <tr>
                            <td class="text-muted">{{ $bukus->firstItem() + $i }}</td>
                            <td>
                                <img src="{{ $buku->cover_url }}"
                                     alt="Cover {{ $buku->judul }}"
                                     class="cover-thumb"
                                     onerror="this.src='https://via.placeholder.com/48x64?text=No+Cover'">
                            </td>
                            <td>
                                <div class="fw-semibold" style="max-width:220px;
                                     white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $buku->judul }}
                                </div>
                                @if($buku->isbn)
                                    <small class="text-muted">ISBN: {{ $buku->isbn }}</small>
                                @endif
                            </td>
                            <td>{{ $buku->penulis }}</td>
                            <td>
                                <span class="badge-kategori">{{ $buku->kategori->nama ?? '—' }}</span>
                            </td>
                            <td>
                                @if($buku->rak)
                                    <span class="badge-rak">{{ $buku->rak->kode_rak }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $buku->tahun_terbit }}</td>
                            <td>
                                <span class="badge badge-stok
                                    {{ $buku->stok > 5 ? 'bg-success' : ($buku->stok > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $buku->stok }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('buku.show', $buku) }}"
                                       class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $buku) }}"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $buku) }}" method="POST"
                                          onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if($bukus->hasPages())
        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
            {{ $bukus->links() }}
        </div>
    @endif
</div>
@endsection
