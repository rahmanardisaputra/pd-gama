@extends('layouts.app')

@section('title', 'Leger Nilai & Cetak Rapor')
@section('page_title', 'Leger Nilai & Cetak Rapor Siswa')

@section('content')
<!-- Header Filter Tahun Ajaran & Kelas -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('leger.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary mb-1">Tahun Ajaran & Semester</label>
                <select name="tahun_ajaran_id" class="form-select" onchange="this.form.submit()">
                    @foreach($daftarTahunAjaran as $t)
                        <option value="{{ $t->id }}" @selected($taId == $t->id)>
                            TA {{ $t->nama_tahun }} ({{ $t->semester }}) {{ $t->aktif ? '- [Aktif]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary mb-1">Pilih Rombongan Belajar (Kelas)</label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    @forelse($daftarKelas as $k)
                        <option value="{{ $k->id }}" @selected($selectedKelasId == $k->id)>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @empty
                        <option value="">-- Tidak ada kelas pada semester ini --</option>
                    @endforelse
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat me-1"></i> Muat Leger
                </button>
                @if($kelas)
                    <a href="{{ route('kelas.kelola', $kelas->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i> Kelola Siswa/Mapel
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($kelas)
    <!-- Info Bar Kelas & Tombol Aksi Excel / Cetak -->
    <div class="card mb-4 border-primary" style="position: relative; z-index: 20;">
        <div class="card-body bg-light-subtle d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-primary">LEGER NILAI: {{ $kelas->nama_kelas }}</h5>
                <div class="small text-secondary">
                    <span>Wali Kelas: <strong>{{ $kelas->waliKelas->nama_guru ?? 'Belum ditentukan' }}</strong></span> &bull;
                    <span>Jurusan: <strong>{{ $kelas->kompetensiKeahlian->nama_jurusan ?? 'Umum' }}</strong></span> &bull;
                    <span>Total Siswa: <strong>{{ count($matriksLeger) }} orang</strong></span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <!-- Tombol Upload Excel Guru -->
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalImportNilai">
                    <i class="bi bi-file-earmark-arrow-up-fill me-1"></i> Upload Nilai Excel Guru
                </button>

                <!-- Tombol Download Format Excel per Mapel -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-earmark-excel me-1"></i> Download Format Nilai
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg py-2" style="max-height: 380px; overflow-y: auto; z-index: 1060; min-width: 290px;">
                        <li class="dropdown-header text-uppercase fw-bold small text-muted">Pilih Format Mapel</li>
                        @foreach($kelas->pengampuMapel as $p)
                            <li>
                                <a class="dropdown-item d-flex align-items-center justify-content-between py-2" href="{{ route('leger.template', $p->id) }}">
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary font-monospace me-1">[{{ $p->mapel->kode_mapel }}]</span>
                                        <span class="fw-medium">{{ $p->mapel->nama_mapel }}</span>
                                    </div>
                                    <i class="bi bi-download text-muted ms-2"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tombol Export Leger Utuh ke Excel -->
                <a href="{{ route('leger.export', $kelas->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-table me-1"></i> Export Leger (.xlsx)
                </a>

                <!-- Tombol Cetak Leger Landscape -->
                <a href="{{ route('leger.cetak-leger', $kelas->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-printer me-1"></i> Cetak Leger
                </a>

                <!-- Tombol Cetak Rapor Seluruh Siswa -->
                <a href="{{ route('leger.rapor', $kelas->id) }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="bi bi-journal-text me-1"></i> Cetak Rapor (1 Kelas)
                </a>
            </div>
        </div>
    </div>

    <!-- Matriks Leger Nilai Siswa -->
    <div class="card" style="position: relative; z-index: 10;">
        <div class="table-responsive" style="max-height: 70vh; overflow-x: auto;">
            <table class="table table-bordered table-hover align-middle mb-0 text-nowrap" style="font-size: 0.85rem;">
                <thead class="table-dark text-center align-middle sticky-top" style="z-index: 5;">
                    <tr>
                        <th rowspan="2" style="width: 40px;">No</th>
                        <th rowspan="2" style="width: 50px;">Abs</th>
                        <th rowspan="2" style="width: 90px;">NIS</th>
                        <th rowspan="2" style="min-width: 180px;" class="text-start">Nama Peserta Didik</th>
                        <!-- Kolom Mapel -->
                        @foreach($kelas->pengampuMapel as $p)
                            <th title="{{ $p->mapel->nama_mapel }} (Guru: {{ $p->guru->nama_guru ?? '-' }})">
                                {{ $p->mapel->kode_mapel }}
                            </th>
                        @endforeach
                        <!-- Kolom Rekapitulasi Nilai & Peringkat -->
                        <th rowspan="2" style="width: 80px;" class="bg-primary text-white">Jumlah</th>
                        <th rowspan="2" style="width: 80px;" class="bg-primary text-white">Rata2</th>
                        <th rowspan="2" style="width: 60px;" class="bg-warning text-dark">Rank</th>
                        <th colspan="3" class="bg-secondary text-white">Absensi</th>
                        <th rowspan="2" style="width: 100px;">Aksi</th>
                    </tr>
                    <tr>
                        <!-- Baris KKM per Mapel -->
                        @foreach($kelas->pengampuMapel as $p)
                            <th class="small fw-normal text-warning" style="font-size: 0.72rem;">
                                KKM {{ $p->kkm }}
                            </th>
                        @endforeach
                        <th style="width: 40px;" class="bg-secondary text-white small">S</th>
                        <th style="width: 40px;" class="bg-secondary text-white small">I</th>
                        <th style="width: 40px;" class="bg-secondary text-white small">A</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matriksLeger as $idx => $row)
                        @php
                            $anggota = $row['anggota'];
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td class="text-center fw-bold">{{ $anggota->nomor_urut_absen ?? '-' }}</td>
                            <td class="font-monospace text-muted">{{ $anggota->siswa->nis ?? '-' }}</td>
                            <td class="text-start">
                                <a href="{{ route('siswa.show', $anggota->siswa->id) }}" class="fw-bold text-decoration-none text-dark">
                                    {{ $anggota->siswa->nama_lengkap }}
                                </a>
                                <small class="text-muted d-block font-monospace" style="font-size: 0.72rem;">NISN: {{ $anggota->siswa->nisn ?? '-' }}</small>
                            </td>

                            <!-- Nilai Tiap Mapel -->
                            @foreach($kelas->pengampuMapel as $p)
                                @php
                                    $detail = $row['nilai_detail'][$p->id] ?? null;
                                    $val = $detail['angka'] ?? null;
                                @endphp
                                <td class="text-center font-monospace {{ $val !== null ? ($detail['tuntas'] ? 'text-dark' : 'text-danger fw-bold bg-danger-subtle') : 'text-muted' }}">
                                    @if($val !== null)
                                        {{ number_format($val, 0) }}
                                        <small class="text-muted" style="font-size: 0.7rem;">({{ $detail['predikat'] }})</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach

                            <!-- Total, Rata-rata, Ranking -->
                            <td class="text-center fw-bold font-monospace bg-light">{{ number_format($row['total'], 0) }}</td>
                            <td class="text-center fw-bold font-monospace bg-light text-primary">{{ number_format($row['rata'], 1) }}</td>
                            <td class="text-center fw-bold font-monospace bg-warning-subtle text-dark">
                                {{ $rankMap[$anggota->id] ?? '-' }}
                            </td>

                            <!-- Absensi -->
                            <td class="text-center small">{{ $anggota->jumlah_sakit }}</td>
                            <td class="text-center small">{{ $anggota->jumlah_izin }}</td>
                            <td class="text-center small {{ $anggota->jumlah_alpa > 0 ? 'text-danger fw-bold' : '' }}">{{ $anggota->jumlah_alpa }}</td>

                            <!-- Aksi Cetak Rapor -->
                            <td class="text-center">
                                <a href="{{ route('leger.rapor', [$kelas->id, $anggota->id]) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 rounded" title="Cetak Rapor Siswa Ini" style="font-size: 0.75rem;">
                                    <i class="bi bi-printer"></i> Rapor
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 10 + $kelas->pengampuMapel->count() }}" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                Belum ada siswa yang dimasukkan ke kelas ini.
                                <div class="mt-2">
                                    <a href="{{ route('kelas.kelola', $kelas->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-person-plus me-1"></i> Masukkan Siswa Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Upload Excel Guru -->
    <div class="modal fade" id="modalImportNilai" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formImportNilai" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">Upload Nilai Excel dari Guru Mapel</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Mata Pelajaran yang Diimport <span class="text-danger">*</span></label>
                            <select id="selectPengampuImport" class="form-select" required onchange="updateImportAction(this.value)">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($kelas->pengampuMapel as $p)
                                    <option value="{{ $p->id }}" data-url="{{ route('leger.import', $p->id) }}">
                                        [{{ $p->mapel->kode_mapel }}] {{ $p->mapel->nama_mapel }} (Guru: {{ $p->guru->nama_guru ?? 'Wali' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih File Excel / CSV dari Guru <span class="text-danger">*</span></label>
                            <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Format file harus sesuai dengan template hasil unduh dari sistem.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Mulai Import Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateImportAction(pengampuId) {
            const select = document.getElementById('selectPengampuImport');
            const selectedOption = select.options[select.selectedIndex];
            const url = selectedOption.getAttribute('data-url');
            if (url) {
                document.getElementById('formImportNilai').action = url;
            }
        }
    </script>
    @endpush

@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i> Silakan pilih tahun ajaran dan kelas di atas untuk melihat leger nilai.
    </div>
@endif
@endsection

