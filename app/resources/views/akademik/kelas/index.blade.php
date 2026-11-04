@extends('layouts.app')

@section('title', 'Data Rombongan Belajar (Kelas)')
@section('page_title', 'Rombongan Belajar (Kelas)')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('kelas.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="form-label mb-0 fw-semibold text-secondary small">Filter Tahun Ajaran:</label>
            </div>
            <div class="col-auto">
                <select name="tahun_ajaran_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($daftarTahunAjaran as $t)
                        <option value="{{ $t->id }}" @selected($taId == $t->id)>
                            TA {{ $t->nama_tahun }} ({{ $t->semester }}) {{ $t->aktif ? '- [Aktif]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Kelas / Rombel</h5>
            <small class="text-muted">Kelola nama rombel, wali kelas, serta ploting siswa dan mapel</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kelas Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Wali Kelas</th>
                    <th class="text-center">Jumlah Siswa</th>
                    <th class="text-center">Jumlah Mapel</th>
                    <th class="text-end" style="width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasList as $idx => $k)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-bold text-primary">{{ $k->nama_kelas }}</td>
                        <td>Tingkat {{ $k->tingkat }}</td>
                        <td>{{ $k->kompetensiKeahlian->nama_jurusan ?? '<span class="text-muted">Umum</span>' }}</td>
                        <td>{{ $k->waliKelas->nama_guru ?? '<span class="text-muted fst-italic">Belum Ditentukan</span>' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">
                                {{ $k->anggota_rombel_count }} Siswa
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info px-3 py-1 rounded-pill">
                                {{ $k->pengampu_mapel_count }} Mapel
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('kelas.kelola', $k->id) }}" class="btn btn-primary" title="Kelola Siswa & Pengampu Mapel">
                                    <i class="bi bi-gear-fill me-1"></i> Kelola
                                </a>
                                <a href="{{ route('leger.index', ['kelas_id' => $k->id]) }}" class="btn btn-outline-primary" title="Buka Leger Nilai">
                                    <i class="bi bi-table"></i>
                                </a>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $k->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade text-start" id="modalEdit{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Kelas {{ $k->nama_kelas }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-8">
                                                        <label class="form-label small fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $k->nama_kelas) }}" required>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label small fw-semibold">Tingkat <span class="text-danger">*</span></label>
                                                        <input type="number" name="tingkat" class="form-control" value="{{ old('tingkat', $k->tingkat) }}" min="1" max="13" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Kompetensi Keahlian / Jurusan</label>
                                                    <select name="kompetensi_keahlian_id" class="form-select">
                                                        <option value="">-- Umum / Tanpa Jurusan --</option>
                                                        @foreach($daftarJurusan as $j)
                                                            <option value="{{ $j->id }}" @selected($k->kompetensi_keahlian_id == $j->id)>
                                                                {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Wali Kelas</label>
                                                    <select name="wali_kelas_id" class="form-select">
                                                        <option value="">-- Pilih Wali Kelas --</option>
                                                        @foreach($daftarGuru as $g)
                                                            <option value="{{ $g->id }}" @selected($k->wali_kelas_id == $g->id)>
                                                                {{ $g->nama_guru }} {{ $g->nip ? '(' . $g->nip . ')' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade text-start" id="modalHapus{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('kelas.destroy', $k->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Kelas</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus kelas <strong>{{ $k->nama_kelas }}</strong>?</p>
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
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada kelas pada tahun ajaran ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun_ajaran_id" value="{{ $taId }}">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Kelas Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: X-RPL-1, VII-A" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Tingkat <span class="text-danger">*</span></label>
                            <input type="number" name="tingkat" class="form-control" placeholder="10" min="1" max="13" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kompetensi Keahlian / Jurusan</label>
                        <select name="kompetensi_keahlian_id" class="form-select">
                            <option value="">-- Umum / Tanpa Jurusan --</option>
                            @foreach($daftarJurusan as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Wali Kelas</label>
                        <select name="wali_kelas_id" class="form-select">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($daftarGuru as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_guru }} {{ $g->nip ? '(' . $g->nip . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

