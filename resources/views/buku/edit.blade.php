@extends('layouts.app')

@section('title', 'Edit Buku: ' . $buku->judul)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Buku
        </h1>
        <p class="text-muted mb-0 mt-1" style="font-size:.88rem;">Memperbarui data: <strong>{{ $buku->judul }}</strong></p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>Perhatian!</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('buku.update', $buku) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- Info Utama --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>Informasi Utama</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $buku->judul) }}" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                            <input type="text" id="penulis" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                                   value="{{ old('penulis', $buku->penulis) }}" required>
                            @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" id="penerbit" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                                   value="{{ old('penerbit', $buku->penerbit) }}" required>
                            @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                                   value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1000" max="{{ date('Y') }}" required>
                            @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="edisi" class="form-label">Edisi</label>
                            <input type="text" id="edisi" name="edisi" class="form-control @error('edisi') is-invalid @enderror"
                                   value="{{ old('edisi', $buku->edisi) }}">
                            @error('edisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jumlah_halaman" class="form-label">Jumlah Halaman</label>
                            <input type="number" id="jumlah_halaman" name="jumlah_halaman" class="form-control @error('jumlah_halaman') is-invalid @enderror"
                                   value="{{ old('jumlah_halaman', $buku->jumlah_halaman) }}" min="1">
                            @error('jumlah_halaman')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bahasa" class="form-label">Bahasa <span class="text-danger">*</span></label>
                            <input type="text" id="bahasa" name="bahasa" class="form-control @error('bahasa') is-invalid @enderror"
                                   value="{{ old('bahasa', $buku->bahasa) }}" required>
                            @error('bahasa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                   value="{{ old('isbn', $buku->isbn) }}">
                            @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" id="stok" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', $buku->stok) }}" min="0" required>
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi / Sinopsis</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-4">
            {{-- Cover --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-image me-2"></i>Cover Buku</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <img id="coverPreview" src="{{ $buku->cover_url }}"
                         class="cover-preview mb-3" alt="Cover"
                         onerror="this.src='https://via.placeholder.com/200x260?text=No+Cover'">
                    <div>
                        <input type="file" id="cover" name="cover" class="form-control @error('cover') is-invalid @enderror"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               onchange="previewCover(this)">
                        <div class="form-text mt-1">Kosongkan jika tidak ingin mengganti cover.</div>
                        @error('cover')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Penempatan --}}
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-geo-alt me-2"></i>Penempatan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id', $buku->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="rak_id" class="form-label">Lokasi Rak</label>
                        <select id="rak_id" name="rak_id" class="form-select @error('rak_id') is-invalid @enderror">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($raks as $rak)
                                <option value="{{ $rak->id }}" {{ old('rak_id', $buku->rak_id) == $rak->id ? 'selected' : '' }}>
                                    {{ $rak->kode_rak }} — {{ $rak->lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('rak_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="{{ route('buku.show', $buku) }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Batal
        </a>
        <button type="submit" class="btn btn-warning text-white">
            <i class="bi bi-check-lg me-1"></i>Perbarui Buku
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('coverPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
