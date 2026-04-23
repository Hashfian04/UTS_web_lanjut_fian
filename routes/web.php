<?php

/**
 * routes/web.php — Definisi Rute Web LibraSpace
 *
 * File ini mendefinisikan seluruh rute HTTP untuk aplikasi perpustakaan.
 * Rute dibagi menjadi 3 kelompok berdasarkan tingkat aksesnya:
 *
 * ─── 1. Publik (Tanpa Login) ─────────────────────────────────────────────────
 *   GET  /           → Landing page dengan jumlah buku
 *   GET  /katalog    → Daftar buku publik (tanpa buku hidden)
 *   GET  /katalog/{id} → Detail buku publik (tanpa sinopsis/download)
 *
 * ─── 2. Auth Required (middleware: auth + verified) ──────────────────────────
 *   GET  /dashboard      → DashboardController@index → view sesuai role
 *   GET  /profile        → ProfileController@edit
 *   PATCH /profile       → ProfileController@update
 *   DELETE /profile      → ProfileController@destroy
 *   GET  /buku/{id}      → BukuController@show (semua role login)
 *
 * ─── 3. Role: Petugas & Super Admin (middleware: role:petugas,super_admin) ──
 *   GET  /buku           → index   (daftar buku + filter)
 *   GET  /buku/create    → create  (form tambah buku)
 *   POST /buku           → store   (simpan buku baru)
 *   GET  /buku/{id}/edit → edit    (form edit buku)
 *   PUT  /buku/{id}      → update  (simpan perubahan buku)
 *   DELETE /buku/{id}    → destroy (hapus buku + cover)
 *
 * ─── 4. Role: Super Admin Only (middleware: role:super_admin) ────────────────
 *   PATCH /buku/{id}/visibility → updateVisibility (ubah level visibilitas)
 *
 * @see bootstrap/app.php      Pendaftaran alias middleware 'role'
 * @see app/Http/Middleware/RoleMiddleware.php
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Buku;

// ─── PUBLIC: Landing Page ──────────────────────────────────────────────────
Route::get('/', function () {
    // Hitung buku yang tidak disembunyikan untuk ditampilkan di landing page
    $jumlahBuku = Buku::where('visibility', '!=', Buku::VIS_HIDDEN)->count();
    return view('landing', compact('jumlahBuku'));
})->name('home');

// ─── PUBLIC: Katalog buku (tanpa login) ────────────────────────────────────
Route::get('/katalog', [BukuController::class, 'index'])->name('katalog');
Route::get('/katalog/{buku}', [BukuController::class, 'show'])->name('katalog.show');

// ─── AUTH PROTECTED ────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard: redirect ke view sesuai role pengguna
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile: edit, perbarui, dan hapus akun sendiri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Tambah Buku (Anggota): route khusus yang menampilkan pesan error ──
    // Harus didefinisikan SEBELUM resource route agar tidak tertutup middleware.
    // Semua user login bisa akses, tapi controller menolak non-Admin.
    Route::get('/buku/create-anggota', [BukuController::class, 'createForbidden'])
         ->name('buku.create.anggota');

    // ─── CRUD Buku: khusus Petugas & Super Admin ───────────────────────────
    Route::middleware('role:petugas,super_admin')->group(function () {
        // Menghasilkan: index, create, store, edit, update, destroy
        Route::resource('buku', BukuController::class)->except(['show']);
    });

    // Ubah visibilitas buku: khusus Super Admin
    Route::patch('/buku/{buku}/visibility', [BukuController::class, 'updateVisibility'])
         ->middleware('role:super_admin')
         ->name('buku.visibility');

    // Detail buku: semua user login bisa akses (konten disesuaikan per role)
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
});

// Load rute autentikasi bawaan Laravel Breeze (login, register, logout, dll.)
require __DIR__.'/auth.php';
