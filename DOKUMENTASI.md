

# DOKUMENTASI PROYEK: LibraSpace
### Sistem Manajemen Perpustakaan — Berbasis Laravel 12

> **Dibuat**: 2026-04-06 | **Framework**: Laravel 12 | **PHP**: ≥ 8.2  
> **Database**: MySQL (via XAMPP) | **Frontend**: Bootstrap 5 (CDN)

---

## Daftar Isi
1. [Informasi Proyek](#informasi-proyek)
2. [Struktur Direktori](#struktur-direktori)
3. [Peta Lokasi File](#peta-lokasi-file)
4. [Sistem Role & Hak Akses](#sistem-role--hak-akses)
5. [Sistem Visibilitas Buku](#sistem-visibilitas-buku)
6. [Daftar Route](#daftar-route)
7. [Database & Migrasi](#database--migrasi)
8. [Cara Menjalankan](#cara-menjalankan)

---

## Informasi Proyek

| Properti       | Nilai                                         |
|----------------|-----------------------------------------------|
| Nama Proyek    | LibraSpace                                    |
| Deskripsi      | Sistem manajemen perpustakaan berbasis web    |
| Framework      | Laravel 12 (Blade + Bootstrap)                |
| Lokasi Proyek  | `c:\xampp\htdocs\UTS_web_lanjut_fian\`        |
| URL Lokal      | `http://localhost/UTS_web_lanjut_fian/public/` |
| Autentikasi    | Laravel Breeze (Blade stack)                  |

---

## Struktur Direktori

```
UTS_web_lanjut_fian/
├── app/
│   ├── Http/
│   │   ├── Controllers/       ← Controller HTTP
│   │   └── Middleware/        ← Middleware kustom
│   ├── Models/                ← Eloquent Models
│   └── Providers/
├── bootstrap/
│   └── app.php                ← Konfigurasi aplikasi & middleware alias
├── database/
│   ├── migrations/            ← Skema database
│   └── seeders/               ← Data awal database
├── public/                    ← Document root web server
│   ├── index.php
│   └── storage → (symlink ke storage/app/public)
├── resources/
│   └── views/                 ← Blade templates
│       ├── auth/              ← Halaman login & register
│       ├── buku/              ← Halaman CRUD buku
│       ├── dashboard/         ← Halaman dashboard per role
│       ├── layouts/           ← Template layout utama
│       └── profile/           ← Halaman profil pengguna
├── routes/
│   ├── web.php                ← Definisi rute web utama
│   └── auth.php               ← Rute autentikasi Breeze
└── storage/
    └── app/public/covers/     ← Gambar cover buku yang diupload
```

---

## Peta Lokasi File

### 🔵 Models (`app/Models/`)

| File | Kelas | Deskripsi |
|---|---|---|
| [`User.php`](app/Models/User.php) | `User` | Model pengguna dengan 4 role (super_admin, petugas, peminjam, pengunjung). Berisi konstanta role, helper method (isPetugas, canManageBuku, dll.), dan accessor label/warna. |
| [`Buku.php`](app/Models/Buku.php) | `Buku` | Model buku dengan soft deletes. Berisi konstanta visibilitas (VIS_HIDDEN, VIS_DATA_ONLY, VIS_DATA_SYNOPSIS, VIS_FULL), helper (canShowSynopsis, canDownload), accessor cover_url. |
| [`Kategori.php`](app/Models/Kategori.php) | `Kategori` | Model kategori buku (Fiksi, Non-Fiksi, dll.). Relasi hasMany ke Buku. |
| [`Rak.php`](app/Models/Rak.php) | `Rak` | Model rak/lokasi fisik buku di perpustakaan. Relasi hasMany ke Buku. |

---

### 🟢 Controllers (`app/Http/Controllers/`)

| File | Kelas | Deskripsi |
|---|---|---|
| [`BukuController.php`](app/Http/Controllers/BukuController.php) | `BukuController` | Menghandle CRUD buku: index (daftar+filter), create, store (simpan+upload cover), show (role-aware), edit, update, updateVisibility (Super Admin), destroy. |
| [`DashboardController.php`](app/Http/Controllers/DashboardController.php) | `DashboardController` | Entry point `/dashboard`. Deteksi role pengguna dan redirect ke 4 view dashboard berbeda: super_admin, petugas, peminjam, pengunjung. |
| [`ProfileController.php`](app/Http/Controllers/ProfileController.php) | `ProfileController` | Bawaan Laravel Breeze. Menangani tampilan, update, dan penghapusan profil pengguna. |

---

### 🟡 Middleware (`app/Http/Middleware/`)

| File | Kelas | Deskripsi |
|---|---|---|
| [`RoleMiddleware.php`](app/Http/Middleware/RoleMiddleware.php) | `RoleMiddleware` | Middleware RBAC (Role-Based Access Control). Memeriksa `auth()->user()->role` terhadap role yang diizinkan. Didaftarkan sebagai alias `role` di `bootstrap/app.php`. Mengembalikan 403 jika role tidak cocok. |

---

### 🔴 Migrations (`database/migrations/`)

| File | Aksi | Tabel | Deskripsi |
|---|---|---|---|
| `0001_01_01_000000_create_users_table.php` | CREATE | `users` | Tabel pengguna bawaan Laravel (id, name, email, password, timestamps) |
| `0001_01_01_000001_create_cache_table.php` | CREATE | `cache` | Tabel cache sesi bawaan Laravel |
| `0001_01_01_000002_create_jobs_table.php` | CREATE | `jobs` | Tabel antrian pekerjaan (queue) bawaan Laravel |
| `2026_04_06_000001_create_kategoris_table.php` | CREATE | `kategoris` | Tabel kategori buku: id, nama |
| `2026_04_06_000002_create_raks_table.php` | CREATE | `raks` | Tabel rak buku: id, kode_rak, lokasi |
| `2026_04_06_000003_create_bukus_table.php` | CREATE | `bukus` | Tabel buku utama: semua atribut buku + soft deletes |
| `2026_04_06_000004_add_role_to_users_table.php` | ALTER | `users` | Tambah kolom `role` enum ke tabel users |
| `2026_04_06_000005_add_visibility_file_to_bukus_table.php` | ALTER | `bukus` | Tambah kolom `visibility` dan `file_path` ke tabel bukus |

---

### 🟣 Seeders (`database/seeders/`)

| File | Kelas | Isi Data |
|---|---|---|
| [`DatabaseSeeder.php`](database/seeders/DatabaseSeeder.php) | `DatabaseSeeder` | Orkestrasi urutan seeder: KategoriSeeder → RakSeeder → UserSeeder → BukuSeeder |
| [`KategoriSeeder.php`](database/seeders/KategoriSeeder.php) | `KategoriSeeder` | 10 kategori: Fiksi, Non-Fiksi, Sains & Teknologi, Sejarah, Ekonomi & Bisnis, Pendidikan, Kesehatan, Filsafat, Agama & Spiritualitas, Seni & Budaya |
| [`RakSeeder.php`](database/seeders/RakSeeder.php) | `RakSeeder` | 5 rak: R-01 s/d R-05 di berbagai lokasi lantai |
| [`UserSeeder.php`](database/seeders/UserSeeder.php) | `UserSeeder` | 4 akun demo (satu per role): superadmin, petugas, peminjam, pengunjung — semua password: `password` |
| [`BukuSeeder.php`](database/seeders/BukuSeeder.php) | `BukuSeeder` | 12 buku nyata: Laskar Pelangi, Atomic Habits, Sapiens, Clean Code, Rich Dad Poor Dad, Filosofi Teras, Al-Quran, Sehat Tanpa Obat, Bumi Manusia, Introduction to Algorithms, Pendidikan Karakter, Batik |

---

### 🔵 Routes

| File | Deskripsi |
|---|---|
| [`routes/web.php`](routes/web.php) | Rute utama: landing publik, katalog publik, dashboard (auth), CRUD buku (petugas+super_admin), visibilitas (super_admin), profil |
| [`routes/auth.php`](routes/auth.php) | Rute autentikasi bawaan Breeze: login, register, logout, verifikasi email, reset password |

---

### 🟠 Views (`resources/views/`)

#### Layout Utama

| File | Deskripsi |
|---|---|
| [`layouts/app.blade.php`](resources/views/layouts/app.blade.php) | Layout utama Bootstrap 5 untuk semua halaman auth dan dashboard. Mendukung `@yield('content')` (CRUD Buku) dan `$slot` (komponen Breeze). Berisi navbar cerdas yang menyesuaikan role. |
| [`layouts/main.blade.php`](resources/views/layouts/main.blade.php) | Layout sederhana Bootstrap 5 untuk landing page publik. Tanpa navbar login-required. |

#### Halaman Publik

| File | URL | Deskripsi |
|---|---|---|
| [`landing.blade.php`](resources/views/landing.blade.php) | `/` | Landing page publik. Menampilkan jumlah koleksi buku dan CTA Login/Register. |

#### Autentikasi (Breeze + Bootstrap)

| File | URL | Deskripsi |
|---|---|---|
| [`auth/login.blade.php`](resources/views/auth/login.blade.php) | `/login` | Form login dengan gradient premium. Email, password, remember me, link forgot password. |
| [`auth/register.blade.php`](resources/views/auth/register.blade.php) | `/register` | Form registrasi. Akun baru otomatis diberi role `pengunjung`. |

#### Dashboard (4 View Berbeda per Role)

| File | Role | Konten Utama |
|---|---|---|
| [`dashboard/super_admin.blade.php`](resources/views/dashboard/super_admin.blade.php) | Super Admin | Stats, distribusi role user, legenda visibilitas, tabel buku dengan inline dropdown ubah visibilitas |
| [`dashboard/petugas.blade.php`](resources/views/dashboard/petugas.blade.php) | Petugas | Stats (total buku, kategori, rak, hidden), aksi cepat CRUD, tabel buku terbaru |
| [`dashboard/peminjam.blade.php`](resources/views/dashboard/peminjam.blade.php) | Peminjam | Welcome card, daftar buku yang bisa dibaca sinopsisnya, daftar buku yang bisa diunduh |
| [`dashboard/pengunjung.blade.php`](resources/views/dashboard/pengunjung.blade.php) | Pengunjung | Banner upgrade ke Peminjam, jumlah buku, kategori populer, tabel hak akses |

#### CRUD Buku

| File | URL | Deskripsi |
|---|---|---|
| [`buku/index.blade.php`](resources/views/buku/index.blade.php) | `/buku` | Daftar buku dengan filter kategori, pencarian, paginasi, dan badge visibilitas. Tombol aksi disesuaikan role. |
| [`buku/create.blade.php`](resources/views/buku/create.blade.php) | `/buku/create` | Form tambah buku baru dengan upload cover (JPG/PNG/WebP maks 2MB). |
| [`buku/edit.blade.php`](resources/views/buku/edit.blade.php) | `/buku/{id}/edit` | Form edit buku dengan pratinjau cover saat ini. |
| [`buku/show.blade.php`](resources/views/buku/show.blade.php) | `/buku/{id}` | Detail buku. Konten sinopsis dan tombol download tampil secara kondisional berdasarkan role dan visibilitas buku. |

#### Profil

| File | URL | Deskripsi |
|---|---|---|
| [`profile/edit.blade.php`](resources/views/profile/edit.blade.php) | `/profile` | Halaman edit profil: ubah nama/email, ganti password, hapus akun. |

---

## Sistem Role & Hak Akses

```
┌────────────────┬──────────────────┬──────────────────────────────────────────┐
│ Role           │ Kode             │ Hak Akses                               │
├────────────────┼──────────────────┼──────────────────────────────────────────┤
│ Super Admin    │ super_admin      │ Ubah visibilitas buku, akses semua CRUD  │
│ Petugas        │ petugas          │ CRUD buku (tambah, edit, hapus)          │
│ Peminjam       │ peminjam         │ Lihat buku + sinopsis/download (jz izin) │
│ Pengunjung     │ pengunjung       │ Hanya lihat data dasar buku (default)    │
└────────────────┴──────────────────┴──────────────────────────────────────────┘
```

Role default saat registrasi: **Pengunjung**  
Middleware: `RoleMiddleware` dengan alias `role` (didaftarkan di `bootstrap/app.php`)

---

## Sistem Visibilitas Buku

Visibilitas dikontrol oleh Super Admin per buku:

```
┌──────────────────┬──────────────────────┬──────────────────────────────────┐
│ Kode             │ Label                │ Akses Peminjam                   │
├──────────────────┼──────────────────────┼──────────────────────────────────┤
│ hidden           │ Tersembunyi          │ Tidak tampil untuk umum          │
│ data_only        │ Data Saja (default)  │ Hanya judul, penulis, dll.       │
│ data_synopsis    │ Data + Sinopsis      │ + Dapat membaca sinopsis         │
│ full             │ Lengkap (+ Download) │ + Dapat download softcopy        │
└──────────────────┴──────────────────────┴──────────────────────────────────┘
```

---

## Daftar Route

| Method | URL | Nama Route | Controller@Method | Akses |
|---|---|---|---|---|
| GET | `/` | `home` | Closure | Publik |
| GET | `/katalog` | `katalog` | `BukuController@index` | Publik |
| GET | `/katalog/{buku}` | `katalog.show` | `BukuController@show` | Publik |
| GET | `/login` | `login` | `AuthenticatedSessionController@create` | Publik |
| POST | `/login` | - | `AuthenticatedSessionController@store` | Publik |
| GET | `/register` | `register` | `RegisteredUserController@create` | Publik |
| POST | `/register` | - | `RegisteredUserController@store` | Publik |
| POST | `/logout` | `logout` | `AuthenticatedSessionController@destroy` | Auth |
| GET | `/dashboard` | `dashboard` | `DashboardController@index` | Auth |
| GET | `/profile` | `profile.edit` | `ProfileController@edit` | Auth |
| PATCH | `/profile` | `profile.update` | `ProfileController@update` | Auth |
| DELETE | `/profile` | `profile.destroy` | `ProfileController@destroy` | Auth |
| GET | `/buku` | `buku.index` | `BukuController@index` | Petugas/Admin |
| GET | `/buku/create` | `buku.create` | `BukuController@create` | Petugas/Admin |
| POST | `/buku` | `buku.store` | `BukuController@store` | Petugas/Admin |
| GET | `/buku/{id}` | `buku.show` | `BukuController@show` | Auth |
| GET | `/buku/{id}/edit` | `buku.edit` | `BukuController@edit` | Petugas/Admin |
| PUT/PATCH | `/buku/{id}` | `buku.update` | `BukuController@update` | Petugas/Admin |
| DELETE | `/buku/{id}` | `buku.destroy` | `BukuController@destroy` | Petugas/Admin |
| PATCH | `/buku/{id}/visibility` | `buku.visibility` | `BukuController@updateVisibility` | Super Admin |

---

## Database & Migrasi

**Skema Tabel Utama:**

```sql
-- Tabel users (dengan role)
users: id, name, email, role (enum), email_verified_at, password, remember_token, timestamps

-- Tabel kategoris
kategoris: id, nama, timestamps

-- Tabel raks
raks: id, kode_rak, lokasi, timestamps

-- Tabel bukus (dengan visibility & soft deletes)
bukus: id, judul, penulis, penerbit, tahun_terbit, edisi, jumlah_halaman,
       bahasa, kategori_id (FK), rak_id (FK), deskripsi, cover, stok, isbn,
       visibility (enum), file_path, timestamps, deleted_at
```

**Urutan Seeder:** `KategoriSeeder` → `RakSeeder` → `UserSeeder` → `BukuSeeder`

---

## Cara Menjalankan

```bash
# 1. Masuk ke direktori proyek
cd c:\xampp\htdocs\UTS_web_lanjut_fian

# 2. Install dependencies (jika belum)
composer install

# 3. Salin file environment
copy .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env:
#    DB_DATABASE=UTS_web_lanjut_fian

# 5. Jalankan migrasi + seeder
php artisan migrate:fresh --seed

# 6. Buat symlink storage (untuk akses gambar cover)
php artisan storage:link

# 7. Akses via XAMPP (pastikan Apache & MySQL aktif)
#    URL: http://localhost/UTS_web_lanjut_fian/public/

# Akun Demo (setelah seeder):
# superadmin@perpus.xyz / password  → Super Admin
# petugas@perpus.xyz    / password  → Petugas
# peminjam@perpus.xyz   / password  → Peminjam
# pengunjung@perpus.xyz / password  → Pengunjung
```
