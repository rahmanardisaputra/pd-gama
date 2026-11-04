@extends('layouts.app')

@section('title', 'Data Buku Induk Siswa')
@section('page_title', 'Buku Induk Siswa')

@section('content')
<!-- Filter & Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('siswa.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary">Cari Siswa</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama, NIS, NISN, atau NIK..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary">Status Siswa</label>
                <select name="status" class="form-select">
                    <option value="aktif" @selected($status === 'aktif')>Siswa Aktif</option>
                    <option value="keluar" @selected($status === 'keluar')>Keluar / Lulus / Mutasi</option>
                    <option value="semua" @selected($status === 'semua')>Semua Status</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Kelas / Rombel (Semester Ini)</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" @selected($kelasId == $k->id)>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Jurusan</label>
                <div class="d-flex gap-2">
                    <select name="jurusan_id" class="form-select">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($daftarJurusan as $j)
                            <option value="{{ $j->id }}" @selected($jurusanId == $j->id)>{{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-filter"></i>
                    </button>
                    @if($search || $status !== 'aktif' || $kelasId || $jurusanId)
                        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Induk Peserta Didik</h5>
            <small class="text-muted">Total ditemukan: {{ $siswaList->total() }} peserta didik</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                <i class="bi bi-file-earmark-arrow-up-fill me-1"></i> Import Excel Siswa
            </button>
            <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Lengkap</th>
                    <th>NIS / NISN</th>
                    <th>L/P</th>
                    <th>Kelas Saat Ini</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $idx => $s)
                    <tr>
                        <td>{{ $siswaList->firstItem() + $idx }}</td>
                        <td>
                            <a href="{{ route('siswa.show', $s->id) }}" class="fw-bold text-decoration-none">
                                {{ $s->nama_lengkap }}
                            </a>
                            <small class="text-muted d-block font-monospace">NIK: {{ $s->nik ?? '-' }}</small>
                        </td>
                        <td>
                            <div><strong class="text-primary font-monospace">{{ $s->nis ?? '-' }}</strong></div>
                            <small class="text-muted font-monospace">NISN: {{ $s->nisn ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $s->jenis_kelamin === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-circle p-2">
                                {{ $s->jenis_kelamin }}
                            </span>
                        </td>
                        <td>
                            @php
                                $rombelAktif = $s->anggotaRombel->first();
                            @endphp
                            @if($rombelAktif && $rombelAktif->kelas)
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold">
                                    {{ $rombelAktif->kelas->nama_kelas }} (No. {{ $rombelAktif->nomor_urut_absen ?? '-' }})
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">Belum ada kelas</span>
                            @endif
                        </td>
                        <td>{{ $s->kompetensiKeahlian->kode_jurusan ?? '-' }}</td>
                        <td>
                            @if($s->keluar_karena_id)
                                <span class="badge bg-danger px-2 py-1 rounded-pill">
                                    Keluar ({{ $s->keluarKarena->nama ?? 'Keluar' }})
                                </span>
                            @else
                                <span class="badge bg-success px-2 py-1 rounded-pill">Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <a href="{{ route('siswa.show', $s->id) }}" class="btn btn-sm btn-outline-primary" title="Detail Buku Induk">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.edit', $s->id) }}">
                                            <i class="bi bi-pencil-square text-warning me-2"></i> Edit Data
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.cetak.buku-induk', $s->id) }}" target="_blank">
                                            <i class="bi bi-printer text-primary me-2"></i> Cetak Lembar Buku Induk
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.cetak.dapodik', $s->id) }}" target="_blank">
                                            <i class="bi bi-file-earmark-pdf text-info me-2"></i> Cetak Form F-PESERTA DIDIK
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $s->id }}">
                                            <i class="bi bi-trash me-2"></i> Hapus Siswa
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade text-start" id="modalHapus{{ $s->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('siswa.destroy', $s->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Siswa</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus data siswa <strong>{{ $s->nama_lengkap }}</strong> dari Buku Induk?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-danger">Ya, Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                            Tidak ada data siswa yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($siswaList->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
            <small class="text-muted">Menampilkan {{ $siswaList->firstItem() }} - {{ $siswaList->lastItem() }} dari {{ $siswaList->total() }} data</small>
            {{ $siswaList->links() }}
        </div>
    @endif
</div>

<!-- Modal Import Siswa dari Excel -->
<div class="modal fade" id="modalImportSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Import Data Siswa dari File Excel</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="fw-semibold small text-primary mb-1">
                            <i class="bi bi-info-circle-fill me-1"></i> Langkah 1: Unduh Format Template
                        </div>
                        <p class="small text-muted mb-2">
                            Unduh template Excel resmi yang sudah disesuaikan dengan kolom formulir Dapodik & Buku Induk.
                        </p>
                        <a href="{{ route('siswa.template') }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Unduh Template Excel (.xlsx)
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Langkah 2: Pilih File Excel / CSV Hasil Pengisian <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Mendukung format .xlsx, .xls, atau .csv</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Langkah 3 (Opsional): Masukkan Langsung ke Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">-- Jangan Masukkan ke Kelas (Hanya ke Buku Induk) --</option>
                            @foreach($daftarKelas as $k)
                                <option value="{{ $k->id }}">
                                    Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Jika kolom <em>NAMA_KELAS</em> di file Excel diisi, sistem akan otomatis memprioritaskan kelas tersebut.</small>
                    </div>

                    <div class="alert alert-secondary small mb-0 py-2">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> <strong>Pencocokan Cerdas:</strong> Pilihan Agama, Pekerjaan, Pendidikan, dan Jurusan dicocokkan otomatis berdasarkan nama atau kode Dapodik.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Mulai Proses Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

