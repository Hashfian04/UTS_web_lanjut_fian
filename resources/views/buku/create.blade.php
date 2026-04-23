@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="fw-bold mb-0" style="font-size:1.6rem;">
            <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Buku
        </h1>
        <p class="text-muted mb-0 mt-1" style="font-size:.88rem;">Isi formulir di bawah untuk menambah buku baru</p>
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

<form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf

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
                                   value="{{ old('judul') }}" placeholder="Masukkan judul buku">
                            <div class="form-text">Minimal 5, maksimal 100 karakter.</div>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                            <input type="text" id="penulis" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                                   value="{{ old('penulis') }}" placeholder="Nama penulis">
                            <div class="form-text">Minimal 3, maksimal 30 karakter.</div>
                            @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" id="penerbit" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                                   value="{{ old('penerbit') }}" placeholder="Nama penerbit">
                            <div class="form-text">Minimal 3, maksimal 30 karakter.</div>
                            @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                                   value="{{ old('tahun_terbit') }}" placeholder="{{ date('Y') }}">
                            <div class="form-text">Tahun 2020 s.d {{ date('Y') }}.</div>
                            @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="edisi" class="form-label">Edisi</label>
                            <input type="text" id="edisi" name="edisi" class="form-control @error('edisi') is-invalid @enderror"
                                   value="{{ old('edisi') }}" placeholder="Mis: Edisi ke-3">
                            @error('edisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jumlah_halaman" class="form-label">Jumlah Halaman</label>
                            <input type="number" id="jumlah_halaman" name="jumlah_halaman" class="form-control @error('jumlah_halaman') is-invalid @enderror"
                                   value="{{ old('jumlah_halaman') }}" min="1" placeholder="0">
                            @error('jumlah_halaman')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bahasa" class="form-label">Bahasa <span class="text-danger">*</span></label>
                            <input type="text" id="bahasa" name="bahasa" class="form-control @error('bahasa') is-invalid @enderror"
                                   value="{{ old('bahasa', 'Indonesia') }}" placeholder="Indonesia">
                            @error('bahasa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                   value="{{ old('isbn') }}" placeholder="978-602-xxxxxx">
                            @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" id="stok" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', 1) }}">
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi / Sinopsis</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Masukkan ringkasan atau sinopsis buku…">{{ old('deskripsi') }}</textarea>
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
                    <img id="coverPreview" src="https://via.placeholder.com/200x260?text=Cover"
                         class="cover-preview mb-3" alt="Preview Cover">
                    <div>
                        <input type="file" id="cover" name="cover"
                               class="form-control @error('cover') is-invalid @enderror"
                               accept=".jpg,.jpeg"
                               onchange="previewCover(this)">
                        <div class="form-text mt-1">Format: JPG saja &bull; Ukuran: 50 kB &ndash; 200 kB</div>
                        @error('cover')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div id="coverClientError" class="text-danger small mt-1" style="display:none;"></div>
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
                        <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label for="rak_id" class="form-label">Lokasi Rak</label>
                        <select id="rak_id" name="rak_id" class="form-select @error('rak_id') is-invalid @enderror">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($raks as $rak)
                                <option value="{{ $rak->id }}" {{ old('rak_id') == $rak->id ? 'selected' : '' }}>
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
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i>Simpan Buku
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
/**
 * Fungsi preview cover — hanya menampilkan peringatan visual (warning),
 * TIDAK memblokir submit. Validasi sesungguhnya dilakukan di server.
 */
function previewCover(input) {
    const errEl     = document.getElementById('coverClientError');
    const coverInput = document.getElementById('cover');

    // Reset state warning
    errEl.style.display = 'none';
    errEl.innerHTML = '';
    coverInput.classList.remove('is-invalid');

    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const ext  = file.name.split('.').pop().toLowerCase();
    const size = file.size;
    const warns = [];

    if (!['jpg','jpeg'].includes(ext)) {
        warns.push('<i class="bi bi-x-circle me-1"></i>Format harus JPG/JPEG (dipilih: ' + ext.toUpperCase() + '). Server akan menolak file ini.');
    }
    if (size < 50 * 1024) {
        warns.push('<i class="bi bi-x-circle me-1"></i>Ukuran terlalu kecil: ' + (size/1024).toFixed(1) + ' kB (min 50 kB). Server akan menolak file ini.');
    }
    if (size > 200 * 1024) {
        warns.push('<i class="bi bi-x-circle me-1"></i>Ukuran terlalu besar: ' + (size/1024).toFixed(1) + ' kB (maks 200 kB). Server akan menolak file ini.');
    }

    if (warns.length > 0) {
        // Tampilkan peringatan, tapi BIARKAN form tetap bisa disubmit
        coverInput.classList.add('is-invalid');
        errEl.innerHTML = warns.join('<br>');
        errEl.style.display = 'block';
        document.getElementById('coverPreview').src = 'https://via.placeholder.com/200x260?text=Cover';
        return;
    }

    // File valid — tampilkan preview
    const reader = new FileReader();
    reader.onload = e => document.getElementById('coverPreview').src = e.target.result;
    reader.readAsDataURL(file);
}
</script>
@endpush
