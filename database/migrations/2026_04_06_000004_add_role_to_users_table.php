<?php

namespace App\Models;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: add_role_to_users_table
 *
 * Menambahkan kolom `role` ke tabel `users` untuk mendukung
 * sistem autentikasi berbasis peran (Role-Based Access Control).
 *
 * Nilai yang diizinkan:
 *  - 'super_admin' : Staf IT kampus, mengatur visibilitas buku dan kelola user
 *  - 'petugas'     : Admin perpustakaan, CRUD data buku
 *  - 'peminjam'    : Anggota terdaftar, akses sinopsis & download sesuai izin
 *  - 'pengunjung'  : Pengguna umum, hanya lihat data buku (default saat registrasi)
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Jalankan migration (tambah kolom role).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'petugas', 'peminjam', 'pengunjung'])
                  ->default('pengunjung')
                  ->after('email');
        });
    }

    /**
     * Rollback migration (hapus kolom role).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
