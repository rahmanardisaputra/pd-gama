@extends('layouts.app')

@section('title', 'Kelola Rombel: ' . $kelas->nama_kelas)
@section('page_title', 'Kelola Kelas: ' . $kelas->nama_kelas)

@section('content')
<div class="card mb-4 border-primary">
    <div class="card-body bg-light-subtle">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-primary">{{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})</h4>
                <div class="d-flex flex-wrap gap-3 small text-secondary">
                    <span><i class="bi bi-calendar3 me-1"></i> Tahun Ajaran: <strong>{{ $kelas->tahunAjaran->nama_tahun }} ({{ $kelas->tahunAjaran->semester }})</strong></span>
                    <span><i class="bi bi-mortarboard me-1"></i> Jurusan: <strong>{{ $kelas->kompetensiKeahlian->nama_jurusan ?? 'Umum' }}</strong></span>
                    <span><i class="bi bi-person-badge me-1"></i> Wali Kelas: <strong>{{ $kelas->waliKelas->nama_guru ?? 'Belum Ditentukan' }}</strong></span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('leger.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-table me-1"></i> Buka Leger Nilai
                </a>
                <a href="{{ route('kelas.index', ['tahun_ajaran_id' => $kelas->tahun_ajaran_id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- PANEL 1: ANGGOTA ROMBEL (SISWA) -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary fs-5"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">Panel 1: Anggota Rombel Siswa ({{ $kelas->anggotaRombel->count() }} Siswa)</h6>
                        <small class="text-muted">Daftar siswa di kelas ini beserta nomor absen, kehadiran, dan catatan wali kelas</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <!-- Tombol Masukkan Siswa -->
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                        <i class="bi bi-person-plus-fill me-1"></i> Masukkan Siswa
                    </button>
                    <!-- Tombol Salin dari Kelas Lain -->
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSalinRombel">
                        <i class="bi bi-copy me-1"></i> Salin Rombel / Kenaikan Kelas
                    </button>
                </div>
            </div>

            <form action="{{ route('kelas.kehadiran', $kelas->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">No Absen</th>
                                <th style="width: 110px;">NIS</th>
                                <th style="width: 120px;">NISN</th>
                                <th>Nama Lengkap Siswa</th>
                                <th style="width: 70px;" class="text-center">Sakit</th>
                                <th style="width: 70px;" class="text-center">Izin</th>
                                <th style="width: 70px;" class="text-center">Alpa</th>
                                <th>Catatan Wali Kelas</th>
                                <th style="width: 140px;">Status Kenaikan</th>
                                <th class="text-end" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas->anggotaRombel as $anggota)
                                <tr>
                                    <td>
                                        <input type="number" name="kehadiran[{{ $anggota->id }}][nomor_urut_absen]" value="{{ $anggota->nomor_urut_absen }}" class="form-control form-control-sm text-center" style="width: 65px;">
                                    </td>
                                    <td><span class="font-monospace text-muted">{{ $anggota->siswa->nis ?? '-' }}</span></td>
                                    <td><span class="font-monospace text-muted">{{ $anggota->siswa->nisn ?? '-' }}</span></td>
                                    <td>
                                        <a href="{{ route('siswa.show', $anggota->siswa->id) }}" class="fw-semibold text-decoration-none">
                                            {{ $anggota->siswa->nama_lengkap }}
                                        </a>
                                        <small class="text-muted d-block">{{ $anggota->siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                                    </td>
                                    <td>
                                        <input type="number" name="kehadiran[{{ $anggota->id }}][jumlah_sakit]" value="{{ $anggota->jumlah_sakit }}" class="form-control form-control-sm text-center" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="kehadiran[{{ $anggota->id }}][jumlah_izin]" value="{{ $anggota->jumlah_izin }}" class="form-control form-control-sm text-center" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="kehadiran[{{ $anggota->id }}][jumlah_alpa]" value="{{ $anggota->jumlah_alpa }}" class="form-control form-control-sm text-center" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="kehadiran[{{ $anggota->id }}][catatan_wali_kelas]" value="{{ $anggota->catatan_wali_kelas }}" class="form-control form-control-sm" placeholder="Catatan untuk rapor...">
                                    </td>
                                    <td>
                                        <select name="kehadiran[{{ $anggota->id }}][status_kenaikan]" class="form-select form-select-sm">
                                            <option value="">-- Status --</option>
                                            <option value="Naik Kelas" @selected($anggota->status_kenaikan === 'Naik Kelas')>Naik Kelas</option>
                                            <option value="Lulus" @selected($anggota->status_kenaikan === 'Lulus')>Lulus</option>
                                            <option value="Tinggal Kelas" @selected($anggota->status_kenaikan === 'Tinggal Kelas')>Tinggal Kelas</option>
                                        </select>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalKeluarkan{{ $anggota->id }}" title="Keluarkan dari kelas">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        Belum ada siswa di kelas ini. Klik <strong>"Masukkan Siswa"</strong> atau <strong>"Salin Rombel"</strong> di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($kelas->anggotaRombel->isNotEmpty())
                    <div class="card-footer bg-white text-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-floppy me-1"></i> Simpan Nomor Absen, Kehadiran & Catatan
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- PANEL 2: MATA PELAJARAN & GURU PENGAMPU -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-book-half text-primary fs-5"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">Panel 2: Mata Pelajaran & Guru Pengampu ({{ $kelas->pengampuMapel->count() }} Mapel)</h6>
                        <small class="text-muted">Atur mata pelajaran yang diajarkan, guru pengampu, serta KKM kelas</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMapel">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Mapel ke Kelas
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">Urutan</th>
                            <th style="width: 140px;">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kelompok</th>
                            <th>Guru Pengampu</th>
                            <th style="width: 100px;" class="text-center">KKM</th>
                            <th class="text-end" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->pengampuMapel as $p)
                            <tr>
                                <td class="text-center fw-semibold text-muted">{{ $p->mapel->urutan_leger }}</td>
                                <td><span class="badge bg-primary-subtle text-primary font-monospace">{{ $p->mapel->kode_mapel }}</span></td>
                                <td class="fw-semibold">{{ $p->mapel->nama_mapel }}</td>
                                <td>Kelompok {{ $p->mapel->kelompok }}</td>
                                <td>
                                    {{ $p->guru->nama_guru ?? '<span class="text-muted fst-italic">Belum Ditentukan</span>' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-dark px-3 py-1 font-monospace fs-7">
                                        {{ $p->kkm }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditPengampu{{ $p->id }}" title="Edit Guru / KKM">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="{{ route('leger.template', $p->id) }}" class="btn btn-outline-success" title="Download Format Excel">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapusMapel{{ $p->id }}" title="Hapus Mapel dari Kelas">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Edit Pengampu -->
                                    <div class="modal fade text-start" id="modalEditPengampu{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('kelas.pengampu.update', [$kelas->id, $p->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">Edit Pengampu: {{ $p->mapel->nama_mapel }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Guru Pengampu</label>
                                                            <select name="guru_id" class="form-select">
                                                                <option value="">-- Belum Ditentukan --</option>
                                                                @foreach($daftarGuru as $g)
                                                                    <option value="{{ $g->id }}" @selected($p->guru_id == $g->id)>
                                                                        {{ $g->nama_guru }} {{ $g->nip ? '(' . $g->nip . ')' : '' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">KKM (Kriteria Ketuntasan Minimal)</label>
                                                            <input type="number" name="kkm" class="form-control" value="{{ old('kkm', $p->kkm) }}" min="0" max="100" required>
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

                                    <!-- Modal Hapus Mapel -->
                                    <div class="modal fade text-start" id="modalHapusMapel{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <form action="{{ route('kelas.pengampu.destroy', [$kelas->id, $p->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold text-danger">Hapus Mapel</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="small mb-0">Hapus mapel <strong>{{ $p->mapel->nama_mapel }}</strong> dari kelas {{ $kelas->nama_kelas }}?</p>
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
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada mata pelajaran di kelas ini. Klik <strong>"Tambah Mapel ke Kelas"</strong>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa ke Kelas -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kelas.siswa.tambah', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Pilih Siswa untuk Dimasukkan ke Kelas {{ $kelas->nama_kelas }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">Hanya menampilkan siswa aktif yang belum terdaftar di kelas manapun pada semester ini.</p>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" class="form-check-input" onclick="document.querySelectorAll('.chk-siswa').forEach(c => c.checked = this.checked)">
                                    </th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>L/P</th>
                                    <th>Jurusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaTersedia as $s)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input chk-siswa">
                                        </td>
                                        <td>{{ $s->nis ?? '-' }}</td>
                                        <td>{{ $s->nisn ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $s->nama_lengkap }}</td>
                                        <td>{{ $s->jenis_kelamin }}</td>
                                        <td>{{ $s->kompetensiKeahlian->kode_jurusan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada siswa yang tersedia. Semua siswa aktif telah terdaftar dalam kelas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Masukkan Siswa Terpilih</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Salin Rombel / Kenaikan Kelas -->
<div class="modal fade" id="modalSalinRombel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.salin-rombel', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Salin Rombel / Kenaikan Kelas Cepat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Fitur ini menduplikasi seluruh siswa dari kelas asal ke dalam kelas ini tanpa mengubah data kelas asal.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Kelas Asal (Tahun Lalu / Semester Lalu) <span class="text-danger">*</span></label>
                        <select name="kelas_asal_id" class="form-select" required>
                            <option value="">-- Pilih Kelas Asal --</option>
                            @foreach($kelasListLain as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kelas }} (TA {{ $k->tahunAjaran->nama_tahun }} {{ $k->tahunAjaran->semester }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Salin Seluruh Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Mapel ke Kelas -->
<div class="modal fade" id="modalTambahMapel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.pengampu.tambah', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Mata Pelajaran ke Kelas {{ $kelas->nama_kelas }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapelTersedia as $m)
                                <option value="{{ $m->id }}">
                                    [{{ $m->kode_mapel }}] {{ $m->nama_mapel }} (Kelompok {{ $m->kelompok }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Guru Pengampu</label>
                        <select name="guru_id" class="form-select">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($daftarGuru as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_guru }} {{ $g->nip ? '(' . $g->nip . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">KKM (Kriteria Ketuntasan Minimal) <span class="text-danger">*</span></label>
                        <input type="number" name="kkm" class="form-control" value="75" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Tambahkan Mapel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals Keluarkan Siswa -->
@foreach($kelas->anggotaRombel as $anggota)
    <div class="modal fade" id="modalKeluarkan{{ $anggota->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ route('kelas.siswa.hapus', [$kelas->id, $anggota->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold text-danger">Keluarkan Siswa</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small mb-0">Keluarkan <strong>{{ $anggota->siswa->nama_lengkap }}</strong> dari kelas {{ $kelas->nama_kelas }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger">Ya, Keluarkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

