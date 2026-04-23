<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — LibraSpace</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; color: var(--text); min-height: 100vh; }

        /* NAVBAR */
        .navbar { background: #fff !important; border-bottom: 1px solid var(--border); box-shadow: 0 2px 12px rgba(79,70,229,.06); }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; color: var(--text) !important; }
        .navbar-brand-logo { width:36px;height:36px;background:linear-gradient(135deg,var(--primary),#818cf8);border-radius:10px;display:flex;align-items:center;justify-content:center; }
        .nav-link { font-weight: 500; color: var(--text-muted) !important; padding:.45rem 1rem !important; border-radius:8px; transition:all .2s; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; background: var(--primary-light); }
        .nav-link-btn { background:var(--primary);color:#fff !important;border-radius:8px; }
        .nav-link-btn:hover { background:var(--primary-dark) !important;color:#fff !important; }

        /* BUTTONS */
        .btn { border-radius:8px; font-weight:500; transition:all .2s; }
        .btn-primary { background:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { background:var(--primary-dark); border-color:var(--primary-dark); transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.3); }
        .btn-outline-primary { color:var(--primary); border-color:var(--primary); }
        .btn-outline-primary:hover { background:var(--primary); border-color:var(--primary); }

        .fw-black { font-weight: 900 !important; }

        footer { border-top: 1px solid var(--border); padding: 1.5rem; font-size: .82rem; color: var(--text-muted); }

        @keyframes fadeIn { from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:none;} }
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
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
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;min-width:180px;">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2 text-muted"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
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
                        <a class="nav-link nav-link-btn px-3" href="{{ route('register') }}">
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
        <div class="alert d-flex align-items-center gap-2 mb-4" style="background:#ecfdf5;color:#065f46;border:none;border-radius:12px;" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i><span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert d-flex align-items-center gap-2 mb-4" style="background:#fef2f2;color:#991b1b;border:none;border-radius:12px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i><span>{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
</main>

<footer class="text-center">
    &copy; {{ date('Y') }} LibraSpace &mdash; Sistem Manajemen Perpustakaan
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
