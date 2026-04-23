<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Rak
 *
 * Merepresentasikan lokasi rak fisik di perpustakaan.
 * Setiap buku dapat ditempatkan pada satu rak (bersifat opsional).
 *
 * @package App\Models
 *
 * @property int    $id        Primary key rak
 * @property string $kode_rak  Kode unik rak (mis. 'R-01', 'R-A3')
 * @property string $lokasi    Deskripsi lokasi rak (mis. 'Lantai 1, Sayap Kiri')
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buku[] $bukus
 */
class Rak extends Model
{
    /**
     * Kolom yang dapat diisi secara massal (mass assignment).
     *
     * @var array<string>
     */
    protected $fillable = ['kode_rak', 'lokasi'];

    // ─── Relasi ────────────────────────────────────────────────────────────

    /**
     * Mendapatkan semua buku yang berada di rak ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}
