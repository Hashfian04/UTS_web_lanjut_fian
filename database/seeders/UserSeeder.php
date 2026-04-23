<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 *
 * Mengisi tabel `users` dengan 4 akun demo, satu untuk setiap role
 * yang tersedia dalam sistem LibraSpace.
 *
 * ─── Akun Demo yang Dibuat ──────────────────────────────────────────────────
 * | Nama                  | Email                    | Password | Role        |
 * |-----------------------|--------------------------|----------|-------------|
 * | Super Admin           | superadmin@perpus.xyz    | password | super_admin |
 * | Pak Budi (Petugas)    | petugas@perpus.xyz       | password | petugas     |
 * | Siti Aminah (Peminjam)| peminjam@perpus.xyz      | password | peminjam    |
 * | Andi (Pengunjung)     | pengunjung@perpus.xyz    | password | pengunjung  |
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * Menggunakan `firstOrCreate` agar tidak terjadi duplikasi
 * saat seeder dijalankan berulang kali tanpa migrate:fresh.
 *
 * @package Database\Seeders
 */
class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder — buat akun demo untuk setiap role.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@perpus.xyz',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_SUPER_ADMIN,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Pak Budi (Petugas)',
                'email'             => 'petugas@perpus.xyz',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_PETUGAS,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Siti Aminah (Peminjam)',
                'email'             => 'peminjam@perpus.xyz',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_PEMINJAM,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Andi (Pengunjung)',
                'email'             => 'pengunjung@perpus.xyz',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_PENGUNJUNG,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            // firstOrCreate: cek berdasarkan email, buat jika belum ada
            User::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}
