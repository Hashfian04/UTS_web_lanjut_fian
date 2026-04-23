<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware RoleMiddleware
 *
 * Middleware ini digunakan untuk membatasi akses ke route berdasarkan
 * role yang dimiliki oleh pengguna yang sedang login.
 *
 * ─── Cara Penggunaan di Route ──────────────────────────────────────────────
 *
 *   // Satu role:
 *   Route::middleware('role:petugas')->group(function () { ... });
 *
 *   // Beberapa role (OR — salah satunya terpenuhi):
 *   Route::middleware('role:petugas,super_admin')->group(function () { ... });
 *
 * ─── Alur Kerja ────────────────────────────────────────────────────────────
 *  1. Cek apakah pengguna sudah login → jika tidak, redirect ke /login
 *  2. Cek apakah role pengguna cocok dengan role yang diizinkan
 *  3. Jika tidak cocok → abort 403 (Forbidden)
 *  4. Jika cocok → lanjutkan ke handler berikutnya
 *
 * Middleware ini didaftarkan di bootstrap/app.php dengan alias 'role'.
 *
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    /**
     * Tangani request yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request   Objek HTTP request
     * @param  \Closure                  $next      Handler berikutnya dalam pipeline
     * @param  string                    ...$roles  Daftar role yang diizinkan (variadic)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pastikan role pengguna ada dalam daftar role yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Role Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
