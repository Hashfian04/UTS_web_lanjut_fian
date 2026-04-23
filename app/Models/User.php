<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User (Pengguna)
 *
 * Merepresentasikan pengguna sistem perpustakaan LibraSpace.
 * Setiap pengguna memiliki role yang menentukan hak aksesnya.
 *
 * ─── Sistem Role ────────────────────────────────────────────────────────────
 * | Role         | Kode          | Hak Akses                                  |
 * |--------------|---------------|--------------------------------------------|
 * | Super Admin  | super_admin   | Atur visibilitas buku, kelola user          |
 * | Petugas      | petugas       | CRUD Data Buku                             |
 * | Peminjam     | peminjam      | Baca sinopsis & download sesuai izin       |
 * | Pengunjung   | pengunjung    | Hanya lihat data dasar buku (default)      |
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package App\Models
 *
 * @property int                      $id
 * @property string                   $name
 * @property string                   $email
 * @property string                   $role              Salah satu dari konstanta ROLE_*
 * @property string|null              $email_verified_at
 * @property string                   $password
 * @property string|null              $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read string              $role_label        Label terbaca dari role (accessor)
 * @property-read string              $role_color        Warna Bootstrap badge (accessor)
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ─── Role Constants ────────────────────────────────────────────────────
    /** Staf IT Kampus — mengatur visibilitas dan kelola user */
    const ROLE_SUPER_ADMIN = 'super_admin';

    /** Admin Perpustakaan — full CRUD data buku */
    const ROLE_PETUGAS     = 'petugas';

    /** Anggota terdaftar — baca sinopsis & download sesuai izin */
    const ROLE_PEMINJAM    = 'peminjam';

    /** Pengunjung umum — hanya lihat data dasar buku (role default) */
    const ROLE_PENGUNJUNG  = 'pengunjung';

    /** Label tampilan untuk setiap role */
    const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_PETUGAS     => 'Petugas',
        self::ROLE_PEMINJAM    => 'Peminjam',
        self::ROLE_PENGUNJUNG  => 'Pengunjung',
    ];

    /** Warna Bootstrap badge untuk setiap role */
    const ROLE_COLORS = [
        self::ROLE_SUPER_ADMIN => 'danger',
        self::ROLE_PETUGAS     => 'primary',
        self::ROLE_PEMINJAM    => 'success',
        self::ROLE_PENGUNJUNG  => 'secondary',
    ];

    /**
     * Kolom yang dapat diisi secara massal (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan dari serialisasi (mis. response API).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Definisikan tipe casting untuk atribut tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Role Helpers ──────────────────────────────────────────────────────

    /**
     * Cek apakah user memiliki role Super Admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Cek apakah user memiliki role Petugas.
     *
     * @return bool
     */
    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    /**
     * Cek apakah user memiliki role Peminjam.
     *
     * @return bool
     */
    public function isPeminjam(): bool
    {
        return $this->role === self::ROLE_PEMINJAM;
    }

    /**
     * Cek apakah user memiliki role Pengunjung.
     *
     * @return bool
     */
    public function isPengunjung(): bool
    {
        return $this->role === self::ROLE_PENGUNJUNG;
    }

    /**
     * Cek apakah user memiliki salah satu dari role yang diberikan.
     *
     * Contoh: $user->hasRole(['petugas', 'super_admin'])
     *
     * @param  string|array<string> $roles
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles);
    }

    /**
     * Accessor: Mengembalikan label terbaca untuk role user.
     * Contoh: 'super_admin' → 'Super Admin'
     *
     * @return string
     */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Accessor: Mengembalikan nama kelas warna Bootstrap untuk role user.
     * Contoh: 'petugas' → 'primary' (digunakan sebagai `badge bg-primary`)
     *
     * @return string
     */
    public function getRoleColorAttribute(): string
    {
        return self::ROLE_COLORS[$this->role] ?? 'secondary';
    }

    /**
     * Cek apakah user berhak mengelola (CRUD) data buku.
     * Hanya Petugas dan Super Admin yang bisa.
     *
     * @return bool
     */
    public function canManageBuku(): bool
    {
        return $this->hasRole([self::ROLE_SUPER_ADMIN, self::ROLE_PETUGAS]);
    }
}
