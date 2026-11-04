<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Buku Induk & Leger Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: #2563eb;
            color: #ffffff;
            padding: 2.25rem 2rem 1.75rem;
            text-align: center;
        }
        .login-body {
            padding: 2.25rem 2rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-book-half fs-1 mb-2 d-inline-block"></i>
            <h4 class="fw-bold mb-1">BUKU INDUK SISWA</h4>
            <p class="mb-0 text-white-50 fs-6">Sistem Informasi Leger & Referensi Dapodik</p>
        </div>

        <div class="login-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">EMAIL ADMINISTRATOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="admin@sekolah.sch.id" value="{{ old('email', 'admin@sekolah.sch.id') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">KATA SANDI</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" value="password" required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" checked>
                    <label class="form-check-label text-muted small" for="remember">Ingat Saya di Perangkat Ini</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="mt-4 pt-3 text-center border-top">
                <small class="text-muted">
                    Default login: <strong>admin@sekolah.sch.id</strong> / <strong>password</strong>
                </small>
            </div>
        </div>
    </div>
</body>
</html>

