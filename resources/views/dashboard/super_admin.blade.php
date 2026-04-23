@extends('layouts.app')
@section('title', 'Dashboard Super Admin')

@section('content')
{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-shield-lock me-2" style="color:#ef4444;"></i>Dashboard Super Admin
        </h1>
        <p class="text-muted mb-0 mt-1">Selamat datang, <strong>{{ Auth::user()->name }}</strong> — Staf IT Kampus</p>
    </div>
    <span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-stars me-1"></i>Super Admin</span>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    @php
        $stats = [
            ['icon'=>'bi-journals','label'=>'Total Buku','value'=>$totalBuku,'color'=>'#4f46e5','bg'=>'#eef2ff'],
            ['icon'=>'bi-people','label'=>'Total User','value'=>$totalUser,'color'=>'#059669','bg'=>'#ecfdf5'],
            ['icon'=>'bi-eye-slash','label'=>'Buku Hidden','value'=>$bukus->getCollection()->where('visibility','hidden')->count(),'color'=>'#dc2626','bg'=>'#fef2f2'],
            ['icon'=>'bi-download','label'=>'Buku Download','value'=>$bukus->getCollection()->where('visibility','full')->count(),'color'=>'#d97706','bg'=>'#fffbeb'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="col-md-3 col-6">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div style="width:48px;height:48px;background:{{ $s['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi {{ $s['icon'] }} fs-4" style="color:{{ $s['color'] }};"></i>
            </div>
            <div>
                <div class="fw-bold fs-4 mb-0">{{ $s['value'] }}</div>
                <div class="text-muted" style="font-size:.82rem;">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Role Distribution --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><h5><i class="bi bi-people me-2"></i>Distribusi Role</h5></div>
            <div class="card-body p-3">
                @php
                    $roleLabels = \App\Models\User::ROLE_LABELS;
                    $roleColors = ['super_admin'=>'danger','petugas'=>'primary','peminjam'=>'success','pengunjung'=>'secondary'];
                @endphp
                @foreach($roleLabels as $key => $label)
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded" style="background:#f8fafc;">
                    <span class="badge bg-{{ $roleColors[$key] ?? 'secondary' }}">{{ $label }}</span>
                    <strong>{{ $roleStats[$key] ?? 0 }} user</strong>
                </div>
                @endforeach
                <div class="mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-person-gear me-1"></i>Kelola User <span class="badge bg-secondary ms-1">soon</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Visibility Legend --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><h5><i class="bi bi-sliders me-2"></i>Kontrol Visibilitas Buku</h5></div>
            <div class="card-body p-3">
                <p class="text-muted mb-3" style="font-size:.88rem;">Atur apa yang bisa dilihat oleh Peminjam pada setiap buku:</p>
                <div class="row g-2">
                    @foreach(\App\Models\Buku::VIS_LABELS as $key => $label)
                    <div class="col-md-6">
                        <div class="p-2 rounded border d-flex align-items-center gap-2" style="font-size:.88rem;">
                            <i class="bi {{ \App\Models\Buku::VIS_ICONS[$key] }} text-{{ \App\Models\Buku::VIS_COLORS[$key] }}"></i>
                            <div>
                                <div class="fw-semibold">{{ $label }}</div>
                                @if($key === 'hidden') <small class="text-muted">Tidak tampil untuk umum</small>
                                @elseif($key === 'data_only') <small class="text-muted">Judul, penulis, dll. (default)</small>
                                @elseif($key === 'data_synopsis') <small class="text-muted">+ Sinopsis untuk Peminjam</small>
                                @else <small class="text-muted">+ Download untuk Peminjam</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Buku dengan kontrol Visibility --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-table me-2"></i>Manajemen Visibilitas Buku</h5>
        <a href="{{ route('buku.create') }}" class="btn btn-sm" style="background:#fff;color:#4f46e5;border-radius:8px;font-weight:600;font-size:.82rem;">
            <i class="bi bi-plus-lg me-1"></i>Tambah
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th><th>Judul Buku</th><th>Penulis</th><th>Kategori</th>
                        <th class="text-center">Visibilitas Saat Ini</th>
                        <th class="text-center">Ubah Visibilitas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bukus as $i => $buku)
                    <tr>
                        <td class="text-muted">{{ $bukus->firstItem() + $i }}</td>
                        <td class="fw-semibold" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $buku->judul }}
                        </td>
                        <td>{{ $buku->penulis }}</td>
                        <td><span class="badge-kategori">{{ $buku->kategori->nama ?? '—' }}</span></td>
                        <td class="text-center">
                            <span class="badge bg-{{ $buku->vis_color }}">
                                <i class="bi {{ $buku->vis_icon }} me-1"></i>{{ $buku->vis_label }}
                            </span>
                        </td>
                        <td class="text-center" style="min-width:180px;">
                            <form action="{{ route('buku.visibility', $buku) }}" method="POST" class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="visibility" class="form-select form-select-sm" style="border-radius:8px;font-size:.8rem;">
                                    @foreach(\App\Models\Buku::VIS_LABELS as $key => $label)
                                        <option value="{{ $key }}" {{ $buku->visibility === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary px-2" title="Simpan">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($bukus->hasPages())
    <div class="card-footer bg-transparent pt-0 pb-3 px-3">{{ $bukus->links() }}</div>
    @endif
</div>
@endsection
