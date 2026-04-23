<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Model Buku
 *
 * Merepresentasikan koleksi buku di perpustakaan LibraSpace.
 * Mendukung soft deletes agar data tidak hilang permanen saat dihapus.
 *
 * ─── Sistem Visibilitas ─────────────────────────────────────────────────────
 * Setiap buku memiliki level visibilitas yang dikontrol oleh Super Admin:
 *
 * | Kode           | Label                  | Akses Peminjam                  |
 * |----------------|------------------------|---------------------------------|
 * | hidden         | Tersembunyi            | Tidak tampil untuk umum         |
 * | data_only      | Data Saja              | Hanya info dasar (default)      |
 * | data_synopsis  | Data + Sinopsis        | Dapat membaca sinopsis          |
 * | full           | Lengkap (+ Download)   | Sinopsis + download softcopy    |
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package App\Models
 *
 * @property int         $id
 * @property string      $judul
 * @property string      $penulis
 * @property string      $penerbit
 * @property int         $tahun_terbit
 * @property string|null $edisi
 * @property int|null    $jumlah_halaman
 * @property string      $bahasa
 * @property int         $kategori_id      FK ke tabel kategoris
 * @property int|null    $rak_id           FK ke tabel raks (wajib)
 * @property string|null $deskripsi        Sinopsis / ringkasan isi buku
 * @property string|null $cover            Path relatif file gambar cover di storage
 * @property int         $stok             Jumlah eksemplar yang tersedia
 * @property string|null $isbn             ISBN 13 digit
 * @property string      $visibility       Level visibilitas: hidden|data_only|data_synopsis|full
 * @property string|null $file_path        Path file softcopy (PDF/ebook) untuk download
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at  Untuk soft deletes
 *
 * @property-read string $cover_url   URL publik cover buku (accessor)
 * @property-read string $vis_label   Label teks untuk visibilitas (accessor)
 * @property-read string $vis_color   Warna Bootstrap badge visibilitas (accessor)
 * @property-read string $vis_icon    Kelas ikon Bootstrap Icons (accessor)
 *
 * @property-read \App\Models\Kategori $kategori
 * @property-read \App\Models\Rak|null $rak
 */
class Buku extends Model
{
    use SoftDeletes;

    // ─── Visibility Constants ──────────────────────────────────────────────

    /** Buku tersembunyi dari semua pengguna kecuali Petugas & Super Admin */
    const VIS_HIDDEN        = 'hidden';

    /** Hanya data buku (judul, penulis, dll.) yang tampil — tanpa sinopsis (default) */
    const VIS_DATA_ONLY     = 'data_only';

    /** Data buku + sinopsis dapat dibaca oleh Peminjam */
    const VIS_DATA_SYNOPSIS = 'data_synopsis';

    /** Data + sinopsis + download softcopy tersedia untuk Peminjam */
    const VIS_FULL          = 'full';

    /** Label tampilan untuk setiap level visibilitas */
    const VIS_LABELS = [
        self::VIS_HIDDEN        => 'Tersembunyi',
        self::VIS_DATA_ONLY     => 'Data Saja',
        self::VIS_DATA_SYNOPSIS => 'Data + Sinopsis',
        self::VIS_FULL          => 'Lengkap (+ Download)',
    ];

    /** Warna Bootstrap badge untuk setiap level visibilitas */
    const VIS_COLORS = [
        self::VIS_HIDDEN        => 'secondary',
        self::VIS_DATA_ONLY     => 'info',
        self::VIS_DATA_SYNOPSIS => 'warning',
        self::VIS_FULL          => 'success',
    ];

    /** Kelas ikon Bootstrap Icons untuk setiap level visibilitas */
    const VIS_ICONS = [
        self::VIS_HIDDEN        => 'bi-eye-slash',
        self::VIS_DATA_ONLY     => 'bi-card-text',
        self::VIS_DATA_SYNOPSIS => 'bi-journal-text',
        self::VIS_FULL          => 'bi-download',
    ];

    /**
     * Kolom yang dapat diisi secara massal (mass assignment).
     *
     * @var array<string>
     */
    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit',
        'edisi', 'jumlah_halaman', 'bahasa',
        'kategori_id', 'rak_id',
        'deskripsi', 'cover', 'stok', 'isbn',
        'visibility', 'file_path',
    ];

    /**
     * Casting tipe data kolom ke tipe PHP yang sesuai.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_terbit'   => 'integer',
        'jumlah_halaman' => 'integer',
        'stok'           => 'integer',
    ];

    // ─── Accessors ─────────────────────────────────────────────────────────

    /**
     * Mendapatkan URL publik gambar cover buku.
     * Jika cover tidak ada/tidak valid, kembalikan placeholder.
     *
     * @return string URL gambar cover
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover && Storage::disk('public')->exists($this->cover)) {
            return Storage::url($this->cover);
        }
        return asset('images/no-cover.png');
    }

    /**
     * Mendapatkan label teks visibilitas buku.
     * Contoh: 'data_synopsis' → 'Data + Sinopsis'
     *
     * @return string
     */
    public function getVisLabelAttribute(): string
    {
        return self::VIS_LABELS[$this->visibility] ?? ucfirst($this->visibility);
    }

    /**
     * Mendapatkan nama kelas warna Bootstrap untuk badge visibilitas.
     * Contoh: 'full' → 'success' (digunakan sebagai `badge bg-success`)
     *
     * @return string
     */
    public function getVisColorAttribute(): string
    {
        return self::VIS_COLORS[$this->visibility] ?? 'secondary';
    }

    /**
     * Mendapatkan kelas ikon Bootstrap Icons untuk level visibilitas.
     * Contoh: 'hidden' → 'bi-eye-slash'
     *
     * @return string
     */
    public function getVisIconAttribute(): string
    {
        return self::VIS_ICONS[$this->visibility] ?? 'bi-eye';
    }

    // ─── Visibility Helpers ────────────────────────────────────────────────

    /**
     * Cek apakah buku disembunyikan dari pengguna umum.
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->visibility === self::VIS_HIDDEN;
    }

    /**
     * Cek apakah sinopsis buku boleh ditampilkan kepada Peminjam.
     * Berlaku untuk visibilitas 'data_synopsis' dan 'full'.
     *
     * @return bool
     */
    public function canShowSynopsis(): bool
    {
        return in_array($this->visibility, [self::VIS_DATA_SYNOPSIS, self::VIS_FULL]);
    }

    /**
     * Cek apakah softcopy buku boleh diunduh oleh Peminjam.
     * Berlaku HANYA jika visibilitas 'full' DAN file_path tersedia.
     *
     * @return bool
     */
    public function canDownload(): bool
    {
        return $this->visibility === self::VIS_FULL && !empty($this->file_path);
    }

    // ─── Relations ─────────────────────────────────────────────────────────

    /**
     * Mendapatkan kategori dari buku ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Mendapatkan rak tempat buku ini berada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rak()
    {
        return $this->belongsTo(Rak::class);
    }
}
