<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Rak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * BukuController
 *
 * Menghandle seluruh operasi CRUD (Create, Read, Update, Delete)
 * untuk data buku dalam sistem perpustakaan LibraSpace.
 *
 * ─── Pembatasan Akses ────────────────────────────────────────────────────────
 * | Method              | Role yang Diizinkan                                  |
 * |---------------------|------------------------------------------------------|
 * | index()             | Semua user (publik via /katalog, auth via /buku)     |
 * | show()              | Semua user auth + publik (konten disesuaikan role)   |
 * | create(), store()   | Petugas, Super Admin                                 |
 * | edit(), update()    | Petugas, Super Admin                                 |
 * | updateVisibility()  | Super Admin saja                                     |
 * | destroy()           | Petugas, Super Admin                                 |
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package App\Http\Controllers
 */
class BukuController extends Controller
{
    // ─── INDEX ─────────────────────────────────────────────────────────────

    /**
     * Tampilkan daftar buku dengan filter dan paginasi.
     *
     * Perilaku berdasarkan role:
     * - Petugas & Super Admin: melihat semua buku termasuk yang hidden
     * - Peminjam & Pengunjung: hanya melihat buku yang tidak hidden
     *
     * Request params:
     * - `search`      : string — filter judul, penulis, atau ISBN
     * - `kategori_id` : int    — filter berdasarkan kategori
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Buku::with(['kategori', 'rak'])->orderBy('judul');

        // Pengunjung & Peminjam tidak boleh lihat yang hidden
        if ($user && !$user->canManageBuku()) {
            $query->where('visibility', '!=', Buku::VIS_HIDDEN);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus     = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('buku.index', compact('bukus', 'kategoris', 'user'));
    }

    // ─── CREATE ────────────────────────────────────────────────────────────

    /**
     * Tampilkan form untuk menambahkan buku baru.
     * Hanya dapat diakses oleh Petugas dan Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $raks      = Rak::orderBy('kode_rak')->get();
        return view('buku.create', compact('kategoris', 'raks'));
    }

    // ─── CREATE FORBIDDEN (Anggota) ────────────────────────────────────────

    /**
     * Menangani request Tambah Buku dari user yang BUKAN Petugas/Super Admin.
     *
     * Route ini dapat diakses semua user login, namun selalu menolak dengan
     * pesan error yang informatif dan mengarahkan kembali ke dashboard.
     * Digunakan sebagai target link "Tambah Buku" di dashboard Anggota/Peminjam.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForbidden()
    {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Hanya Admin (Petugas Perpus) yang dapat insert buku baru.');
    }

    // ─── STORE ─────────────────────────────────────────────────────────────

    /**
     * Simpan data buku baru ke database.
     *
     * Jika ada file cover, upload ke storage/app/public/covers/
     * dan simpan path relatifnya ke kolom `cover`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi ukuran cover (50kB – 200kB) dilakukan manual karena
        // Laravel hanya mendukung batas atas (max), bukan batas bawah.
        if ($request->hasFile('cover')) {
            $coverSize = $request->file('cover')->getSize(); // bytes
            if ($coverSize < 51200) {
                return back()
                    ->withInput()
                    ->withErrors(['cover' => 'Ukuran cover terlalu kecil. Minimum 50 kB (ukuran saat ini: ' . round($coverSize / 1024, 1) . ' kB).']);
            }
        }

        $validated = $request->validate([
            'judul'          => 'required|string|min:5|max:100',
            'penulis'        => 'required|string|min:3|max:30',
            'penerbit'       => 'required|string|min:3|max:30',
            'tahun_terbit'   => 'required|integer|min:2020|max:' . date('Y'),
            'edisi'          => 'nullable|string|max:50',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'bahasa'         => 'required|string|max:50',
            'kategori_id'    => 'required|exists:kategoris,id',
            'rak_id'         => 'nullable|exists:raks,id',
            'deskripsi'      => 'nullable|string',
            'cover'          => 'nullable|image|mimes:jpg,jpeg|max:200',
            'stok'           => 'required|integer|min:0',
            'isbn'           => 'nullable|string|max:20|unique:bukus,isbn',
        ], [
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal 5 karakter.',
            'judul.max'             => 'Judul buku maksimal 100 karakter.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.min'           => 'Nama penulis minimal 3 karakter.',
            'penulis.max'           => 'Nama penulis maksimal 30 karakter.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',
            'penerbit.min'          => 'Nama penerbit minimal 3 karakter.',
            'penerbit.max'          => 'Nama penerbit maksimal 30 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit minimal 2020.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh melebihi tahun sekarang (' . date('Y') . ').',
            'cover.image'           => 'File cover harus berupa gambar.',
            'cover.mimes'           => 'Cover buku hanya boleh format JPG/JPEG.',
            'cover.max'             => 'Ukuran cover maksimal 200 kB.',
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'stok.required'         => 'Stok wajib diisi.',
            'stok.min'              => 'Stok tidak boleh negatif.',
        ]);

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // ─── SHOW ──────────────────────────────────────────────────────────────

    /**
     * Tampilkan detail satu buku dengan konten yang disesuaikan per role.
     *
     * Logika tampilan konten:
     * - Petugas & Super Admin : selalu melihat semua konten (full view)
     * - Peminjam              : lihat sinopsis jika canShowSynopsis(), download jika canDownload()
     * - Pengunjung            : hanya data dasar (tidak ada sinopsis & download)
     * - (tanpa login)         : hanya data dasar
     *
     * Buku dengan visibility='hidden' akan menghasilkan 404 untuk non-manager.
     *
     * @param  \App\Models\Buku  $buku   Model binding otomatis dari route
     * @return \Illuminate\View\View
     */
    public function show(Buku $buku)
    {
        $user = Auth::user();

        // Sembunyikan buku hidden dari non-manager
        if ($buku->isHidden() && $user && !$user->canManageBuku()) {
            abort(404, 'Buku tidak ditemukan.');
        }

        $buku->load(['kategori', 'rak']);

        $canSeeSynopsis = false;
        $canDownload    = false;

        if ($user) {
            if ($user->canManageBuku()) {
                // Petugas & Super Admin selalu lihat semua konten
                $canSeeSynopsis = true;
                $canDownload    = $buku->canDownload();
            } elseif ($user->isPeminjam()) {
                // Peminjam: ikuti aturan visibilitas buku
                $canSeeSynopsis = $buku->canShowSynopsis();
                $canDownload    = $buku->canDownload();
            }
            // Pengunjung: $canSeeSynopsis & $canDownload tetap false
        }

        return view('buku.show', compact('buku', 'canSeeSynopsis', 'canDownload', 'user'));
    }

    // ─── EDIT ──────────────────────────────────────────────────────────────

    /**
     * Tampilkan form edit untuk buku yang sudah ada.
     * Hanya dapat diakses oleh Petugas dan Super Admin.
     *
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\View\View
     */
    public function edit(Buku $buku)
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $raks      = Rak::orderBy('kode_rak')->get();
        return view('buku.edit', compact('buku', 'kategoris', 'raks'));
    }

    // ─── UPDATE ────────────────────────────────────────────────────────────

    /**
     * Perbarui data buku yang sudah ada.
     *
     * Jika ada file cover baru, cover lama dihapus dari storage
     * dan digantikan dengan yang baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buku          $buku
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Buku $buku)
    {
        // Validasi ukuran cover (50kB – 200kB) dilakukan manual karena
        // Laravel hanya mendukung batas atas (max), bukan batas bawah.
        if ($request->hasFile('cover')) {
            $coverSize = $request->file('cover')->getSize(); // bytes
            if ($coverSize < 51200) {
                return back()
                    ->withInput()
                    ->withErrors(['cover' => 'Ukuran cover terlalu kecil. Minimum 50 kB (ukuran saat ini: ' . round($coverSize / 1024, 1) . ' kB).']);
            }
        }

        $validated = $request->validate([
            'judul'          => 'required|string|min:5|max:100',
            'penulis'        => 'required|string|min:3|max:30',
            'penerbit'       => 'required|string|min:3|max:30',
            'tahun_terbit'   => 'required|integer|min:2020|max:' . date('Y'),
            'edisi'          => 'nullable|string|max:50',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'bahasa'         => 'required|string|max:50',
            'kategori_id'    => 'required|exists:kategoris,id',
            'rak_id'         => 'nullable|exists:raks,id',
            'deskripsi'      => 'nullable|string',
            'cover'          => 'nullable|image|mimes:jpg,jpeg|max:200',
            'stok'           => 'required|integer|min:0',
            'isbn'           => 'nullable|string|max:20|unique:bukus,isbn,' . $buku->id,
        ], [
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal 5 karakter.',
            'judul.max'             => 'Judul buku maksimal 100 karakter.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.min'           => 'Nama penulis minimal 3 karakter.',
            'penulis.max'           => 'Nama penulis maksimal 30 karakter.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',
            'penerbit.min'          => 'Nama penerbit minimal 3 karakter.',
            'penerbit.max'          => 'Nama penerbit maksimal 30 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit minimal 2020.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh melebihi tahun sekarang (' . date('Y') . ').',
            'cover.image'           => 'File cover harus berupa gambar.',
            'cover.mimes'           => 'Cover buku hanya boleh format JPG/JPEG.',
            'cover.max'             => 'Ukuran cover maksimal 200 kB.',
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'stok.required'         => 'Stok wajib diisi.',
            'stok.min'              => 'Stok tidak boleh negatif.',
        ]);

        // Hapus cover lama dan simpan yang baru
        if ($request->hasFile('cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // ─── UPDATE VISIBILITY ─────────────────────────────────────────────────

    /**
     * Perbarui level visibilitas buku (Super Admin only).
     *
     * Dipanggil via PATCH /buku/{buku}/visibility
     * Hanya Super Admin yang dapat mengakses route ini.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buku          $buku
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateVisibility(Request $request, Buku $buku)
    {
        $request->validate([
            'visibility' => 'required|in:hidden,data_only,data_synopsis,full',
        ]);

        $buku->update(['visibility' => $request->visibility]);

        return back()->with('success', 'Visibilitas buku "' . $buku->judul . '" berhasil diperbarui.');
    }

    // ─── DESTROY ───────────────────────────────────────────────────────────

    /**
     * Hapus buku dari database (soft delete).
     *
     * Cover buku juga dihapus dari storage jika ada.
     * Data buku tidak hilang permanen karena menggunakan SoftDeletes.
     *
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Buku $buku)
    {
        if ($buku->cover) Storage::disk('public')->delete($buku->cover);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
