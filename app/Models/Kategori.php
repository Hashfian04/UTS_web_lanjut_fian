<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Kategori
 *
 * Merepresentasikan kategori buku dalam sistem perpustakaan.
 * Setiap buku diklasifikasikan ke dalam satu kategori.
 *
 * @package App\Models
 *
 * @property int    $id    Primary key kategori
 * @property string $nama  Nama kategori (mis. 'Fiksi', 'Sains & Teknologi')
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buku[] $bukus
 *
 * Contoh kategori: Fiksi, Non-Fiksi, Sains & Teknologi, Sejarah, dll.
 */
class Kategori extends Model
{
    /**
     * Kolom yang dapat diisi secara massal (mass assignment).
     *
     * @var array<string>
     */
    protected $fillable = ['nama'];

    // ─── Relasi ────────────────────────────────────────────────────────────

    /**
     * Mendapatkan semua buku yang masuk ke dalam kategori ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}
