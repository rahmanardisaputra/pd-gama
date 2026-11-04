@extends('layouts.app')

@section('title', 'Dashboard - Sistem Buku Induk')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat: Siswa Aktif -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold text-uppercase">Siswa Aktif</span>
                    <div class="bg-primary-subtle text-primary p-2 rounded-circle">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalSiswa) }}</h3>
                <small class="text-muted">Terdaftar di Buku Induk</small>
            </div>
        </div>
    </div>

    <!-- Stat: Laki-laki & Perempuan -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold text-uppercase">Gender Siswa</span>
                    <div class="bg-info-subtle text-info p-2 rounded-circle">
                        <i class="bi bi-gender-ambiguous fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-3">
                    <div>
                        <span class="fs-4 fw-bold text-dark">{{ $siswaLaki }}</span>
                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-gender-male text-primary"></i> Laki-laki</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <span class="fs-4 fw-bold text-dark">{{ $siswaPerempuan }}</span>
                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-gender-female text-danger"></i> Perempuan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat: Guru & Tenaga Pengajar -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold text-uppercase">Guru Aktif</span>
                    <div class="bg-success-subtle text-success p-2 rounded-circle">
                        <i class="bi bi-person-workspace fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalGuru) }}</h3>
                <small class="text-muted">Wali Kelas & Pengampu Mapel</small>
            </div>
        </div>
    </div>

    <!-- Stat: Rombel / Kelas -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold text-uppercase">Rombel Aktif</span>
                    <div class="bg-warning-subtle text-warning p-2 rounded-circle">
                        <i class="bi bi-diagram-3-fill fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalKelas) }}</h3>
                <small class="text-muted">{{ $taAktif ? 'TA ' . $taAktif->nama_tahun . ' ' . $taAktif->semester : 'Belum diset' }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Tabel Kelas & Rombel Aktif -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard text-primary"></i>
                    <span>Rombongan Belajar Semester Ini</span>
                </div>
                <a href="{{ route('kelas.index') }}" class="btn btn-sm btn-outline-primary">
                    Kelola Semua Kelas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan / Peminatan</th>
                            <th>Wali Kelas</th>
                            <th class="text-center">Jumlah Siswa</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelasList as $kelas)
                            <tr>
                                <td class="fw-bold text-primary">{{ $kelas->nama_kelas }}</td>
                                <td>Tingkat {{ $kelas->tingkat }}</td>
                                <td>{{ $kelas->kompetensiKeahlian->nama_jurusan ?? '-' }}</td>
                                <td>{{ $kelas->waliKelas->nama_guru ?? '<span class="text-muted">Belum Ditunjuk</span>' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-1 fs-7 rounded-pill">
                                        {{ $kelas->anggota_rombel_count }} Siswa
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('kelas.kelola', $kelas->id) }}" class="btn btn-outline-secondary" title="Kelola Siswa & Mapel">
                                            <i class="bi bi-gear"></i>
                                        </a>
                                        <a href="{{ route('leger.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary" title="Lihat Leger">
                                            <i class="bi bi-table"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                    Belum ada data kelas pada tahun ajaran aktif ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Distribusi Siswa & Menu Cepat -->
    <div class="col-lg-4">
        <!-- Menu Pintas Cepat -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Aksi Cepat
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('siswa.create') }}" class="btn btn-primary d-flex align-items-center justify-content-between p-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-plus-fill fs-5"></i> Input Siswa Baru (Buku Induk)
                    </span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('leger.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i> Leger & Import Nilai Excel
                    </span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('referensi.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-between p-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-sliders fs-5"></i> Kelola Master Pilihan Dapodik
                    </span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Sebaran Siswa per Jurusan -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill text-info me-1"></i> Siswa per Jurusan
            </div>
            <div class="card-body">
                @forelse($siswaPerJurusan as $j)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span class="fw-semibold">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</span>
                            <span class="text-muted">{{ $j->siswa_count }} siswa</span>
                        </div>
                        @php
                            $persen = $totalSiswa > 0 ? round(($j->siswa_count / $totalSiswa) * 100) : 0;
                        @endphp
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada jurusan yang terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

