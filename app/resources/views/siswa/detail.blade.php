@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $siswa->nama_lengkap)
@section('page_title', 'Buku Induk Peserta Didik')

@section('content')
<!-- Header Kartu Siswa -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4 bg-white rounded-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 72px; height: 72px; font-size: 1.8rem; font-weight: 700;">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">{{ $siswa->nama_lengkap }}</h4>
                        @if($siswa->keluar_karena_id)
                            <span class="badge bg-danger">Keluar: {{ $siswa->keluarKarena->nama ?? 'Keluar' }}</span>
                        @else
                            <span class="badge bg-success">Siswa Aktif</span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-1 text-secondary small">
                        <span><i class="bi bi-person-vcard me-1"></i> NIS: <strong class="font-monospace text-primary">{{ $siswa->nis ?? '-' }}</strong></span>
                        <span><i class="bi bi-card-text me-1"></i> NISN: <strong class="font-monospace">{{ $siswa->nisn ?? '-' }}</strong></span>
                        <span><i class="bi bi-gender-ambiguous me-1"></i> {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span><i class="bi bi-mortarboard me-1"></i> Jurusan: <strong>{{ $siswa->kompetensiKeahlian->nama_jurusan ?? 'Umum' }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Data
                </a>
                <a href="{{ route('siswa.cetak.buku-induk', $siswa->id) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i> Cetak Lembar Buku Induk
                </a>
                <a href="{{ route('siswa.cetak.dapodik', $siswa->id) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Formulir Dapodik
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Nav-Tabs Detail -->
<div class="card">
    <div class="card-header bg-white border-bottom-0 pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="detailTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-semibold" id="tab-biodata-tab" data-bs-toggle="tab" data-bs-target="#tab-biodata" type="button">
                    <i class="bi bi-person-lines-fill me-1"></i> Biodata Lengkap
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="tab-orangtua-tab" data-bs-toggle="tab" data-bs-target="#tab-orangtua" type="button">
                    <i class="bi bi-people me-1"></i> Orang Tua & Wali
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="tab-periodik-tab" data-bs-toggle="tab" data-bs-target="#tab-periodik" type="button">
                    <i class="bi bi-activity me-1"></i> Kontak & Periodik
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="tab-prestasi-tab" data-bs-toggle="tab" data-bs-target="#tab-prestasi" type="button">
                    <i class="bi bi-trophy me-1"></i> Prestasi & Beasiswa
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold text-primary" id="tab-leger-tab" data-bs-toggle="tab" data-bs-target="#tab-leger" type="button">
                    <i class="bi bi-table me-1"></i> Histori Leger & Rapor Nilai
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="detailTabContent">

            <!-- TAB 1: BIODATA LENGKAP -->
            <div class="tab-pane fade show active" id="tab-biodata">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Identitas Peserta Didik</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width: 180px;" class="text-secondary">Nama Lengkap</th><td>: <strong>{{ $siswa->nama_lengkap }}</strong></td></tr>
                            <tr><th class="text-secondary">Jenis Kelamin</th><td>: {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</td></tr>
                            <tr><th class="text-secondary">NIS / NISN</th><td>: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">NIK / No. KITAS</th><td>: {{ $siswa->nik ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">No. Kartu Keluarga</th><td>: {{ $siswa->no_kk ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Tempat, Tgl Lahir</th><td>: {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir?->translatedFormat('d F Y') }}</td></tr>
                            <tr><th class="text-secondary">No. Akta Lahir</th><td>: {{ $siswa->no_registrasi_akta_lahir ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Agama</th><td>: {{ $siswa->agama->nama ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Kewarganegaraan</th><td>: {{ $siswa->kewarganegaraan }} ({{ $siswa->nama_negara ?? 'Indonesia' }})</td></tr>
                            <tr><th class="text-secondary">Kebutuhan Khusus</th><td>: {{ $siswa->berkebutuhanKhusus->nama ?? 'Tidak' }}</td></tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Domisili & Registrasi Masuk</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width: 180px;" class="text-secondary">Alamat Jalan</th><td>: {{ $siswa->alamat_jalan ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">RT / RW</th><td>: {{ $siswa->rt ?? '-' }} / {{ $siswa->rw ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Dusun / Kelurahan</th><td>: {{ $siswa->nama_dusun ?? '-' }} / {{ $siswa->nama_kelurahan_desa ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Kecamatan / Kode Pos</th><td>: {{ $siswa->kecamatan ?? '-' }} / {{ $siswa->kode_pos ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Tempat Tinggal</th><td>: {{ $siswa->tempatTinggal->nama ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Moda Transportasi</th><td>: {{ $siswa->modaTransportasi->nama ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Jenis Pendaftaran</th><td>: {{ $siswa->jenisPendaftaran->nama ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Sekolah Asal</th><td>: {{ $siswa->sekolah_asal ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Tanggal Masuk</th><td>: {{ $siswa->tanggal_masuk_sekolah ? $siswa->tanggal_masuk_sekolah->translatedFormat('d F Y') : '-' }}</td></tr>
                            <tr><th class="text-secondary">KIP / PIP</th><td>: {{ $siswa->punya_kip ? 'Ya, Penerima' : 'Bukan Penerima' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ORANG TUA & WALI -->
            <div class="tab-pane fade" id="tab-orangtua">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-fill"></i> Data Ayah Kandung</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th class="text-secondary small">Nama</th><td class="fw-semibold">: {{ $siswa->nama_ayah ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">NIK</th><td>: {{ $siswa->nik_ayah ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Tahun Lahir</th><td>: {{ $siswa->tahun_lahir_ayah ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pendidikan</th><td>: {{ $siswa->pendidikanAyah->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pekerjaan</th><td>: {{ $siswa->pekerjaanAyah->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Penghasilan</th><td>: {{ $siswa->penghasilanAyah->nama ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-heart"></i> Data Ibu Kandung</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th class="text-secondary small">Nama</th><td class="fw-semibold">: {{ $siswa->nama_ibu ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">NIK</th><td>: {{ $siswa->nik_ibu ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Tahun Lahir</th><td>: {{ $siswa->tahun_lahir_ibu ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pendidikan</th><td>: {{ $siswa->pendidikanIbu->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pekerjaan</th><td>: {{ $siswa->pekerjaanIbu->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Penghasilan</th><td>: {{ $siswa->penghasilanIbu->nama ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-check"></i> Data Wali</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th class="text-secondary small">Nama</th><td class="fw-semibold">: {{ $siswa->nama_wali ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">NIK</th><td>: {{ $siswa->nik_wali ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Tahun Lahir</th><td>: {{ $siswa->tahun_lahir_wali ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pendidikan</th><td>: {{ $siswa->pendidikanWali->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Pekerjaan</th><td>: {{ $siswa->pekerjaanWali->nama ?? '-' }}</td></tr>
                                <tr><th class="text-secondary small">Penghasilan</th><td>: {{ $siswa->penghasilanWali->nama ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: KONTAK & PERIODIK -->
            <div class="tab-pane fade" id="tab-periodik">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Kontak</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width: 180px;" class="text-secondary">Telepon Rumah</th><td>: {{ $siswa->nomor_telepon_rumah ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Nomor HP / WA</th><td>: {{ $siswa->nomor_hp ?? '-' }}</td></tr>
                            <tr><th class="text-secondary">Email Siswa</th><td>: {{ $siswa->email ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Data Fisik & Jarak</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width: 180px;" class="text-secondary">Tinggi / Berat Badan</th><td>: {{ $siswa->tinggi_badan_cm ? $siswa->tinggi_badan_cm . ' cm' : '-' }} / {{ $siswa->berat_badan_kg ? $siswa->berat_badan_kg . ' kg' : '-' }}</td></tr>
                            <tr><th class="text-secondary">Lingkar Kepala</th><td>: {{ $siswa->lingkar_kepala_cm ? $siswa->lingkar_kepala_cm . ' cm' : '-' }}</td></tr>
                            <tr><th class="text-secondary">Jarak ke Sekolah</th><td>: {{ $siswa->jarak_sekolah_km ? $siswa->jarak_sekolah_km . ' km' : '-' }} ({{ $siswa->kategori_jarak_sekolah === 'kurang_1_km' ? '< 1 km' : '> 1 km' }})</td></tr>
                            <tr><th class="text-secondary">Waktu Tempuh</th><td>: {{ $siswa->waktu_tempuh_jam }} Jam {{ $siswa->waktu_tempuh_menit }} Menit</td></tr>
                            <tr><th class="text-secondary">Jumlah Saudara</th><td>: {{ $siswa->jumlah_saudara_kandung }} orang</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: PRESTASI & BEASISWA -->
            <div class="tab-pane fade" id="tab-prestasi">
                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Prestasi yang Diraih</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Prestasi</th>
                                <th>Jenis</th>
                                <th>Tingkat</th>
                                <th>Tahun</th>
                                <th>Penyelenggara</th>
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->prestasi as $idx => $p)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-semibold">{{ $p->nama_prestasi }}</td>
                                    <td>{{ $p->jenisPrestasi->nama ?? '-' }}</td>
                                    <td>{{ $p->tingkatPrestasi->nama ?? '-' }}</td>
                                    <td>{{ $p->tahun_prestasi }}</td>
                                    <td>{{ $p->penyelenggara }}</td>
                                    <td><span class="badge bg-warning-subtle text-dark">{{ $p->peringkat ?? '-' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat prestasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Riwayat Penerimaan Beasiswa</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Beasiswa</th>
                                <th>Keterangan / Sumber</th>
                                <th>Tahun Mulai</th>
                                <th>Tahun Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->beasiswa as $idx => $b)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-semibold">{{ $b->jenisBeasiswa->nama ?? '-' }}</td>
                                    <td>{{ $b->keterangan }}</td>
                                    <td>{{ $b->tahun_mulai }}</td>
                                    <td>{{ $b->tahun_selesai }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat beasiswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: HISTORI LEGER & RAPOR NILAI -->
            <div class="tab-pane fade" id="tab-leger">
                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Histori Akademik & Rekap Nilai Leger Lintas Semester</h6>
                
                @forelse($siswa->anggotaRombel as $rombel)
                    <div class="card mb-4 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-dark fs-6">{{ $rombel->kelas->nama_kelas }}</span>
                                <span class="badge bg-primary ms-2">TA {{ $rombel->kelas->tahunAjaran->nama_tahun }} ({{ $rombel->kelas->tahunAjaran->semester }})</span>
                                <small class="text-muted ms-2">Wali Kelas: {{ $rombel->kelas->waliKelas->nama_guru ?? '-' }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary-subtle text-dark">No. Absen: {{ $rombel->nomor_urut_absen ?? '-' }}</span>
                                <a href="{{ route('leger.rapor', [$rombel->kelas_id, $rombel->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-printer me-1"></i> Cetak Rapor Ini
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Mata Pelajaran</th>
                                            <th style="width: 90px;" class="text-center">KKM</th>
                                            <th style="width: 100px;" class="text-center">Nilai Akhir</th>
                                            <th style="width: 90px;" class="text-center">Predikat</th>
                                            <th>Capaian Kompetensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rombel->nilaiSiswa as $nIdx => $nilai)
                                            <tr>
                                                <td class="text-center">{{ $nIdx + 1 }}</td>
                                                <td class="fw-semibold">{{ $nilai->pengampuMapel->mapel->nama_mapel ?? '-' }}</td>
                                                <td class="text-center font-monospace">{{ $nilai->pengampuMapel->kkm ?? 75 }}</td>
                                                <td class="text-center fw-bold font-monospace {{ $nilai->nilai_akhir < ($nilai->pengampuMapel->kkm ?? 75) ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($nilai->nilai_akhir, 2) }}
                                                </td>
                                                <td class="text-center font-monospace">{{ $nilai->predikat ?? '-' }}</td>
                                                <td class="small">{{ $nilai->deskripsi_capaian ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center py-2 text-muted">Belum ada nilai diinput untuk semester ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light-subtle d-flex flex-wrap justify-content-between align-items-center small py-2">
                            <div>
                                <span class="me-3">Ketidakhadiran: Sakit: <strong>{{ $rombel->jumlah_sakit }}</strong>, Izin: <strong>{{ $rombel->jumlah_izin }}</strong>, Alpa: <strong>{{ $rombel->jumlah_alpa }}</strong></span>
                                <span>Status Kenaikan: <strong>{{ $rombel->status_kenaikan ?? '-' }}</strong></span>
                            </div>
                            <div>
                                <span>Catatan Wali Kelas: <em>"{{ $rombel->catatan_wali_kelas ?? '-' }}"</em></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-x fs-2 d-block mb-1"></i>
                        Siswa ini belum pernah terdaftar di rombongan belajar manapun.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection

