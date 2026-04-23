@extends('layouts.app')
@section('title', 'Dashboard Petugas')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-person-badge me-2 text-primary"></i>Dashboard Petugas
        </h1>
        <p class="text-muted mb-0 mt-1">Selamat datang, <strong>{{ Auth::user()->name }}</strong> — Admin Perpustakaan</p>
    </div>
    <span class="badge bg-primary px-3 py-2 fs-6"><i class="bi bi-person-badge me-1"></i>Petugas</span>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['icon'=>'bi-journals','label'=>'Total Buku','value'=>$totalBuku,'bg'=>'#eef2ff','color'=>'#4f46e5'],
            ['icon'=>'bi-tag','label'=>'Kategori','value'=>$totalKategori,'bg'=>'#ecfdf5','color'=>'#059669'],
            ['icon'=>'bi-bookshelf','label'=>'Rak Tersedia','value'=>$totalRak,'bg'=>'#fff7ed','color'=>'#d97706'],
            ['icon'=>'bi-eye-slash','label'=>'Buku Hidden','value'=>$hiddenCount,'bg'=>'#fef2f2','color'=>'#dc2626'],
        ];
    @endphp
    @foreach($cards as $c)
    <div class="col-md-3 col-6">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div style="width:48px;height:48px;background:{{ $c['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi {{ $c['icon'] }} fs-4" style="color:{{ $c['color'] }};"></i>
            </div>
            <div>
                <div class="fw-bold fs-4 mb-0">{{ $c['value'] }}</div>
                <div class="text-muted" style="font-size:.82rem;">{{ $c['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Quick Actions --}}
<div class="card mb-4">
    <div class="card-header"><h5><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5></div>
    <div class="card-body p-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('buku.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah Buku Baru
            </a>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-journals me-1"></i>Lihat Semua Buku
            </a>
        </div>
    </div>
</div>

{{-- Buku Terbaru --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-clock-history me-2"></i>Buku Terbaru Ditambahkan</h5>
        <a href="{{ route('buku.index') }}" class="btn btn-sm" style="background:#fff;color:#4f46e5;border-radius:8px;font-weight:600;font-size:.82rem;">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>#</th><th>Cover</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Visibilitas</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($bukuTerbaru as $i => $buku)
                    <tr>
                        <td class="text-muted">{{ $i+1 }}</td>
                        <td><img src="{{ $buku->cover_url }}" class="cover-thumb" onerror="this.src='https://via.placeholder.com/48x64?text=?'"></td>
                        <td class="fw-semibold" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td><span class="badge-kategori">{{ $buku->kategori->nama ?? '—' }}</span></td>
                        <td>
                            <span class="badge badge-stok {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">{{ $buku->stok }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $buku->vis_color }}">{{ $buku->vis_label }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($bukuTerbaru->isEmpty())
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data buku.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
