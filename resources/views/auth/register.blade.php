<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register — LibraSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *{font-family:'Inter',sans-serif;}
        body{background:linear-gradient(135deg,#4f46e5 0%,#818cf8 50%,#c7d2fe 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;}
        .auth-card{background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(79,70,229,.25);width:100%;max-width:440px;overflow:hidden;}
        .auth-header{background:linear-gradient(135deg,#4f46e5,#6366f1);padding:2rem;text-align:center;color:#fff;}
        .auth-logo{width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;}
        .auth-body{padding:2rem;}
        .form-control{border-radius:8px;border:1.5px solid #e2e8f0;font-size:.9rem;padding:.65rem .9rem;transition:border-color .2s,box-shadow .2s;}
        .form-control:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.12);}
        .form-label{font-weight:600;font-size:.85rem;color:#374151;margin-bottom:.4rem;}
        .btn-primary{background:#4f46e5;border-color:#4f46e5;border-radius:10px;font-weight:600;padding:.75rem;font-size:.95rem;}
        .btn-primary:hover{background:#3730a3;border-color:#3730a3;transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,.35);}
        .input-icon-group{position:relative;}
        .input-icon-group .bi{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#94a3b8;}
        .input-icon-group .form-control{padding-left:2.5rem;}
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">
            <i class="bi bi-book-half text-white fs-4"></i>
        </div>
        <h4 class="fw-bold mb-1">LibraSpace</h4>
        <p class="mb-0 opacity-75" style="font-size:.88rem;">Buat akun baru</p>
    </div>
    <div class="auth-body">
        @if($errors->any())
            <div class="alert alert-danger mb-3" style="border-radius:10px;font-size:.88rem;">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-icon-group">
                    <i class="bi bi-person"></i>
                    <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus placeholder="Nama Anda">
                </div>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-icon-group">
                    <i class="bi bi-envelope"></i>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required placeholder="nama@email.com">
                </div>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock"></i>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           required placeholder="Min. 8 karakter">
                </div>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock-fill"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control" required placeholder="Ulangi password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-person-check me-2"></i>Daftar Sekarang
            </button>
        </form>

        <hr class="my-4">
        <p class="text-center mb-0" style="font-size:.88rem;color:#64748b;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#4f46e5;font-weight:600;">Masuk di sini</a>
        </p>
        <p class="text-center mt-3 mb-0">
            <a href="{{ route('home') }}" style="font-size:.85rem;color:#64748b;">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
