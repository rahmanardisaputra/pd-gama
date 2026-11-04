<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Buku Induk & Leger Siswa') - Dapodik</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #0f172a;
            color: #94a3b8;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .brand {
            padding: 1.25rem 1.5rem;
            color: #f8fafc;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.65rem 1.25rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            margin: 0.2rem 0.75rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #2563eb;
        }
        .sidebar .nav-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            padding: 1rem 1.5rem 0.4rem;
            letter-spacing: 0.8px;
        }
        .main-wrapper {
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        .content-body {
            padding: 2rem;
            flex: 1;
        }
        .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.04);
            border-radius: 0.75rem;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-book-half text-primary fs-4 me-2"></i>
            <div>
                <span class="d-block lh-1">BUKU INDUK</span>
                <span class="fs-7 fw-normal text-muted" style="font-size: 0.75rem;">& Leger Nilai Siswa</span>
            </div>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>

            <div class="nav-header">Buku Induk Siswa</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.index') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                    <i class="bi bi-people-fill"></i> Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.create') ? 'active' : '' }}" href="{{ route('siswa.create') }}">
                    <i class="bi bi-person-plus-fill"></i> Tambah Siswa
                </a>
            </li>

            <div class="nav-header">Leger & Penilaian</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leger.*') ? 'active' : '' }}" href="{{ route('leger.index') }}">
                    <i class="bi bi-table"></i> Leger & Nilai Rapor
                </a>
            </li>

            <div class="nav-header">Data Akademik</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelas.*') || request()->routeIs('rombel.*') ? 'active' : '' }}" href="{{ route('kelas.index') }}">
                    <i class="bi bi-diagram-3-fill"></i> Rombel / Kelas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mapel.*') ? 'active' : '' }}" href="{{ route('mapel.index') }}">
                    <i class="bi bi-journal-bookmark-fill"></i> Mata Pelajaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}" href="{{ route('guru.index') }}">
                    <i class="bi bi-person-workspace"></i> Data Guru
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}" href="{{ route('jurusan.index') }}">
                    <i class="bi bi-mortarboard-fill"></i> Jurusan / Keahlian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}" href="{{ route('tahun-ajaran.index') }}">
                    <i class="bi bi-calendar3"></i> Tahun Ajaran
                </a>
            </li>

            <div class="nav-header">Konfigurasi Dapodik</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('referensi.*') ? 'active' : '' }}" href="{{ route('referensi.index') }}">
                    <i class="bi bi-sliders"></i> Master Referensi
                </a>
            </li>
        </ul>

        <div class="p-3 mt-4 text-center">
            <small class="text-secondary" style="font-size: 0.7rem;">&copy; {{ date('Y') }} Sistem Buku Induk Dapodik</small>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-light border d-lg-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                @php
                    $headerTa = \App\Models\TahunAjaran::where('aktif', true)->first();
                @endphp
                @if($headerTa)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i> TA {{ $headerTa->nama_tahun }} ({{ $headerTa->semester }})
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border px-3 py-2 rounded-pill">
                        <i class="bi bi-exclamation-triangle me-1"></i> Belum ada TA Aktif
                    </span>
                @endif

                <div class="dropdown">
                    <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 text-secondary"></i>
                        <span class="fw-medium">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Body Content -->
        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-circle-fill me-1"></i> Perhatian:</strong> Terjadi kesalahan pengisian form.
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

