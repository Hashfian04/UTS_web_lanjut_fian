<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController
 *
 * Menghandle routing dan persiapan data untuk 4 tampilan dashboard
 * yang berbeda sesuai role pengguna yang sedang login.
 *
 * ─── Routing Dashboard ───────────────────────────────────────────────────────
 * GET /dashboard → DashboardController@index
 *     ↓ match role
 *     ├── super_admin → superAdminDashboard() → view: dashboard.super_admin
 *     ├── petugas     → petugasDashboard()     → view: dashboard.petugas
 *     ├── peminjam    → peminjamDashboard()    → view: dashboard.peminjam
 *     └── pengunjung  → pengunjungDashboard()  → view: dashboard.pengunjung
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Entry point dashboard — deteksi role dan arahkan ke view yang sesuai.
     *
     * Menggunakan `match` expression PHP 8 untuk routing berbasis role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            User::ROLE_SUPER_ADMIN => $this->superAdminDashboard(),
            User::ROLE_PETUGAS     => $this->petugasDashboard(),
            User::ROLE_PEMINJAM    => $this->peminjamDashboard(),
            default                => $this->pengunjungDashboard(),
        };
    }

    // ─── Dashboard per Role ────────────────────────────────────────────────

    /**
     * Persiapkan data untuk dashboard Super Admin (Staf IT Kampus).
     *
     * Data yang disiapkan:
     * - totalBuku  : jumlah seluruh buku
     * - totalUser  : jumlah seluruh pengguna
     * - bukus      : daftar buku dengan paginasi (15/hal) untuk kontrol visibilitas
     * - visOptions : opsi visibilitas (Buku::VIS_LABELS) untuk dropdown
     * - roleStats  : distribusi jumlah user per role
     *
     * @return \Illuminate\View\View
     */
    private function superAdminDashboard()
    {
        $totalBuku  = Buku::count();
        $totalUser  = User::count();
        $bukus      = Buku::with('kategori')->latest()->paginate(15);
        $visOptions = Buku::VIS_LABELS;
        $roleStats  = User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');

        return view('dashboard.super_admin', compact('totalBuku', 'totalUser', 'bukus', 'visOptions', 'roleStats'));
    }

    /**
     * Persiapkan data untuk dashboard Petugas (Admin Perpustakaan).
     *
     * Data yang disiapkan:
     * - totalBuku     : jumlah seluruh buku
     * - totalKategori : jumlah kategori yang tersedia
     * - totalRak      : jumlah rak yang tersedia
     * - bukuTerbaru   : 8 buku yang terakhir ditambahkan
     * - hiddenCount   : jumlah buku yang saat ini tersembunyi
     *
     * @return \Illuminate\View\View
     */
    private function petugasDashboard()
    {
        $totalBuku      = Buku::count();
        $totalKategori  = \App\Models\Kategori::count();
        $totalRak       = \App\Models\Rak::count();
        $bukuTerbaru    = Buku::with('kategori')->latest()->take(8)->get();
        $hiddenCount    = Buku::where('visibility', Buku::VIS_HIDDEN)->count();

        return view('dashboard.petugas', compact(
            'totalBuku', 'totalKategori', 'totalRak', 'bukuTerbaru', 'hiddenCount'
        ));
    }

    /**
     * Persiapkan data untuk dashboard Peminjam (Anggota Terdaftar).
     *
     * Data yang disiapkan:
     * - booksWithSynopsis : buku dengan visibilitas data_synopsis atau full (maks 6)
     * - booksWithDownload : buku dengan visibilitas full dan file tersedia (maks 6)
     * - totalTersedia     : total buku yang tidak hidden
     *
     * @return \Illuminate\View\View
     */
    private function peminjamDashboard()
    {
        $booksWithSynopsis = Buku::whereIn('visibility', [Buku::VIS_DATA_SYNOPSIS, Buku::VIS_FULL])
                                 ->with('kategori')->latest()->take(6)->get();
        $booksWithDownload = Buku::where('visibility', Buku::VIS_FULL)
                                 ->whereNotNull('file_path')
                                 ->with('kategori')->latest()->take(6)->get();
        $totalTersedia = Buku::where('visibility', '!=', Buku::VIS_HIDDEN)->count();

        return view('dashboard.peminjam', compact('booksWithSynopsis', 'booksWithDownload', 'totalTersedia'));
    }

    /**
     * Persiapkan data untuk dashboard Pengunjung (Pengguna Umum).
     *
     * Data yang disiapkan:
     * - totalBuku  : jumlah buku yang tidak hidden
     * - kategoris  : semua kategori beserta jumlah buku per kategori (tidak hidden)
     * - bukuTerbaru: 6 buku terbaru yang tidak hidden
     *
     * @return \Illuminate\View\View
     */
    private function pengunjungDashboard()
    {
        $totalBuku  = Buku::where('visibility', '!=', Buku::VIS_HIDDEN)->count();
        $kategoris  = \App\Models\Kategori::withCount(['bukus' => function ($q) {
            $q->where('visibility', '!=', Buku::VIS_HIDDEN);
        }])->orderByDesc('bukus_count')->get();
        $bukuTerbaru = Buku::where('visibility', '!=', Buku::VIS_HIDDEN)
                           ->with('kategori')->latest()->take(6)->get();

        return view('dashboard.pengunjung', compact('totalBuku', 'kategoris', 'bukuTerbaru'));
    }
}
