<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — LibraSpace</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(79,70,229,.07);
            --shadow-hover: 0 8px 30px rgba(79,70,229,.18);
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; color: var(--text); min-height: 100vh; }

        /* NAVBAR */
        .navbar { background: #fff !important; border-bottom: 1px solid var(--border); box-shadow: 0 2px 12px rgba(79,70,229,.06); }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; color: var(--text) !important; }
        .navbar-brand-logo { width:36px;height:36px;background:linear-gradient(135deg,var(--primary),#818cf8);border-radius:10px;display:flex;align-items:center;justify-content:center; }
        .nav-link { font-weight: 500; color: var(--text-muted) !important; padding:.45rem 1rem !important; border-radius:8px; transition:all .2s; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; background: var(--primary-light); }

        /* CARDS */
        .card { border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); transition: box-shadow .25s; }
        .card:hover { box-shadow: var(--shadow-hover); }
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            border-radius: var(--radius) var(--radius) 0 0 !important;
            padding: 1.15rem 1.5rem; border: none;
        }
        .card-header h5 { font-weight: 700; margin: 0; color: #fff; }

        /* BUTTONS */
        .btn { border-radius: 8px; font-weight: 500; transition: all .2s; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,.3); }
        .btn-sm { padding: .3rem .65rem; font-size: .8rem; }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* TABLE */
        .table thead th { background: var(--primary-light); color: var(--primary); font-weight: 600; font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; border: none; padding: .85rem 1rem; }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover { background: #f8f7ff; }
        .table td { vertical-align: middle; border-color: var(--border); padding: .75rem 1rem; font-size: .9rem; }

        /* BADGES */
        .badge-kategori { background: #ede9fe; color: #5b21b6; font-weight: 500; border-radius: 20px; padding: .3em .9em; font-size: .75rem; }
        .badge-rak { background: #ecfdf5; color: #065f46; font-weight: 500; border-radius: 20px; padding: .3em .9em; font-size: .75rem; }
        .badge-stok { font-size: .75rem; border-radius: 20px; padding: .3em .9em; }

        /* FORM */
        .form-control, .form-select { border-radius: 8px; border: 1px solid var(--border); font-size: .9rem; padding: .6rem .9rem; transition: border-color .2s, box-shadow .2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .form-label { font-weight: 500; font-size: .88rem; color: var(--text); margin-bottom: .4rem; }

        /* COVER */
        .cover-preview { width: 120px; height: 160px; object-fit: cover; border-radius: var(--radius); border: 2px solid var(--border); box-shadow: var(--shadow); }
        .cover-thumb { width: 48px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }

        /* ALERTS */
        .alert { border-radius: var(--radius); border: none; font-size: .9rem; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-danger { background: #fef2f2; color: #991b1b; }

        footer { border-top: 1px solid var(--border); padding: 1.5rem; font-size: .82rem; color: var(--text-muted); }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        main { animation: fadeIn .35s ease; }
    </style>

    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="navbar-brand-logo">
                <i class="bi bi-book-half text-white fs-5"></i>
            </div>
            LibraSpace
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Beranda
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                        <i class="bi bi-journals me-1"></i>Data Buku
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;min-width:180px;padding:.5rem;">
                        <li>
                            <a class="dropdown-item py-2 rounded" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2 text-muted"></i>Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger rounded">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="background:var(--primary);color:#fff !important;border-radius:8px;" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
    {{ $slot ?? '' }}
</main>

<footer class="text-center">
    &copy; {{ date('Y') }} LibraSpace &mdash; Sistem Manajemen Perpustakaan
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
