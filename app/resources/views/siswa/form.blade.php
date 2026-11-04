@extends('layouts.app')

@section('title', $siswa->exists ? 'Edit Siswa: ' . $siswa->nama_lengkap : 'Tambah Siswa Baru')
@section('page_title', $siswa->exists ? 'Edit Data Buku Induk Siswa' : 'Tambah Peserta Didik Baru (Buku Induk)')

@section('content')
<form action="{{ $siswa->exists ? route('siswa.update', $siswa->id) : route('siswa.store') }}" method="POST">
    @csrf
    @if($siswa->exists)
        @method('PUT')
    @endif

    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 pb-0">
            <!-- Nav-Tabs 5 Tab Sesuai Dokumen Dapodik -->
            <ul class="nav nav-tabs card-header-tabs" id="siswaTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab">
                        <i class="bi bi-person-badge me-1"></i> 1. Pribadi & Alamat
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab">
                        <i class="bi bi-people me-1"></i> 2. Orang Tua & Wali
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab">
                        <i class="bi bi-telephone me-1"></i> 3. Kontak & Periodik
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab4-tab" data-bs-toggle="tab" data-bs-target="#tab4" type="button" role="tab">
                        <i class="bi bi-trophy me-1"></i> 4. Prestasi & Beasiswa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab5-tab" data-bs-toggle="tab" data-bs-target="#tab5" type="button" role="tab">
                        <i class="bi bi-box-arrow-in-right me-1"></i> 5. Registrasi & Keluar
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="siswaTabContent">

                <!-- TAB 1: DATA PRIBADI & ALAMAT (Poin 1 - 27) -->
                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 1: Data Pribadi Peserta Didik</h6>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Tanggal Formulir</label>
                            <input type="date" name="tanggal_formulir" class="form-control" value="{{ old('tanggal_formulir', $siswa->tanggal_formulir?->format('Y-m-d') ?? date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">1. Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" placeholder="Sesuai akta kelahiran / ijazah" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">2. Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'L')>Laki-laki (L)</option>
                                <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'P')>Perempuan (P)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">3. NISN</label>
                            <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}" placeholder="10 digit NISN">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">4. NIK / No. KITAS</label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik', $siswa->nik) }}" placeholder="16 digit NIK">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">5. No. KK</label>
                            <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk', $siswa->no_kk) }}" placeholder="16 digit Nomor Kartu Keluarga">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">6. Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">7. Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">8. No. Registrasi Akta Lahir</label>
                            <input type="text" name="no_registrasi_akta_lahir" class="form-control" value="{{ old('no_registrasi_akta_lahir', $siswa->no_registrasi_akta_lahir) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">9. Agama & Kepercayaan</label>
                            <select name="agama_id" class="form-select">
                                <option value="">-- Pilih Agama --</option>
                                @foreach($master['agama'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('agama_id', $siswa->agama_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">10. Kewarganegaraan</label>
                            <select name="kewarganegaraan" class="form-select">
                                <option value="WNI" @selected(old('kewarganegaraan', $siswa->kewarganegaraan ?? 'WNI') === 'WNI')>WNI</option>
                                <option value="WNA" @selected(old('kewarganegaraan', $siswa->kewarganegaraan) === 'WNA')>WNA</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Nama Negara</label>
                            <input type="text" name="nama_negara" class="form-control" value="{{ old('nama_negara', $siswa->nama_negara ?? 'Indonesia') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">11. Kebutuhan Khusus</label>
                            <select name="berkebutuhan_khusus_id" class="form-select">
                                <option value="">-- Tidak Berkebutuhan Khusus --</option>
                                @foreach($master['kebutuhan_khusus'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('berkebutuhan_khusus_id', $siswa->berkebutuhan_khusus_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">Alamat Tempat Tinggal & Domisili</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">12. Alamat Jalan</label>
                            <input type="text" name="alamat_jalan" class="form-control" value="{{ old('alamat_jalan', $siswa->alamat_jalan) }}" placeholder="Nama jalan, gang, nomor rumah">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">13. RT</label>
                            <input type="text" name="rt" class="form-control" value="{{ old('rt', $siswa->rt) }}" placeholder="001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">14. RW</label>
                            <input type="text" name="rw" class="form-control" value="{{ old('rw', $siswa->rw) }}" placeholder="005">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">15. Nama Dusun</label>
                            <input type="text" name="nama_dusun" class="form-control" value="{{ old('nama_dusun', $siswa->nama_dusun) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">16. Nama Kelurahan/Desa</label>
                            <input type="text" name="nama_kelurahan_desa" class="form-control" value="{{ old('nama_kelurahan_desa', $siswa->nama_kelurahan_desa) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">17. Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $siswa->kecamatan) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">18. Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $siswa->kode_pos) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">19. Titik Lintang (Latitude)</label>
                            <input type="text" name="lintang" class="form-control" value="{{ old('lintang', $siswa->lintang) }}" placeholder="-6.2088">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">20. Titik Bujur (Longitude)</label>
                            <input type="text" name="bujur" class="form-control" value="{{ old('bujur', $siswa->bujur) }}" placeholder="106.8456">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">21. Tempat Tinggal</label>
                            <select name="tempat_tinggal_id" class="form-select">
                                <option value="">-- Pilih Tempat Tinggal --</option>
                                @foreach($master['tempat_tinggal'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('tempat_tinggal_id', $siswa->tempat_tinggal_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">22. Moda Transportasi</label>
                            <select name="moda_transportasi_id" class="form-select">
                                <option value="">-- Pilih Transportasi --</option>
                                @foreach($master['moda_transportasi'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('moda_transportasi_id', $siswa->moda_transportasi_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">23. Anak ke-berapa</label>
                            <input type="number" name="anak_keberapa" class="form-control" value="{{ old('anak_keberapa', $siswa->anak_keberapa) }}" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">24. Pekerjaan Siswa (Warga Belajar)</label>
                            <select name="pekerjaan_siswa_id" class="form-select">
                                <option value="">-- Tidak Bekerja --</option>
                                @foreach($master['pekerjaan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pekerjaan_siswa_id', $siswa->pekerjaan_siswa_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">25 & 26. KIP / PIP</label>
                            <div class="form-check">
                                <input type="checkbox" name="punya_kip" value="1" class="form-check-input" id="punyaKip" @checked(old('punya_kip', $siswa->punya_kip))>
                                <label class="form-check-label small" for="punyaKip">Penerima KIP / PIP</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="tetap_terima_kip" value="1" class="form-check-input" id="tetapTerimaKip" @checked(old('tetap_terima_kip', $siswa->tetap_terima_kip))>
                                <label class="form-check-label small" for="tetapTerimaKip">Tetap Menerima KIP</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">27. Alasan Menolak PIP</label>
                            <select name="alasan_menolak_pip_id" class="form-select">
                                <option value="">-- Tidak Menolak PIP --</option>
                                @foreach($master['alasan_menolak_pip'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('alasan_menolak_pip_id', $siswa->alasan_menolak_pip_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: DATA ORANG TUA & WALI (Poin 28 - 47) -->
                <div class="tab-pane fade" id="tab2" role="tabpanel">
                    <!-- AYAH KANDUNG -->
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 2: Data Ayah Kandung (Poin 28 - 34)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">28. Nama Ayah Kandung</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">29. NIK Ayah</label>
                            <input type="text" name="nik_ayah" class="form-control" value="{{ old('nik_ayah', $siswa->nik_ayah) }}" placeholder="16 digit">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">30. Tahun Lahir Ayah</label>
                            <input type="number" name="tahun_lahir_ayah" class="form-control" value="{{ old('tahun_lahir_ayah', $siswa->tahun_lahir_ayah) }}" placeholder="Contoh: 1975">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">31. Pendidikan Ayah</label>
                            <select name="pendidikan_ayah_id" class="form-select">
                                <option value="">-- Pilih Pendidikan --</option>
                                @foreach($master['pendidikan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pendidikan_ayah_id', $siswa->pendidikan_ayah_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">32. Pekerjaan Ayah</label>
                            <select name="pekerjaan_ayah_id" class="form-select">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($master['pekerjaan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pekerjaan_ayah_id', $siswa->pekerjaan_ayah_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">33. Penghasilan Bulanan Ayah</label>
                            <select name="penghasilan_ayah_id" class="form-select">
                                <option value="">-- Pilih Penghasilan --</option>
                                @foreach($master['penghasilan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('penghasilan_ayah_id', $siswa->penghasilan_ayah_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">34. Kebutuhan Khusus Ayah</label>
                            <select name="kebutuhan_khusus_ayah_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($master['kebutuhan_khusus'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('kebutuhan_khusus_ayah_id', $siswa->kebutuhan_khusus_ayah_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- IBU KANDUNG -->
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 3: Data Ibu Kandung (Poin 35 - 41)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">35. Nama Ibu Kandung</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">36. NIK Ibu</label>
                            <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu', $siswa->nik_ibu) }}" placeholder="16 digit">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">37. Tahun Lahir Ibu</label>
                            <input type="number" name="tahun_lahir_ibu" class="form-control" value="{{ old('tahun_lahir_ibu', $siswa->tahun_lahir_ibu) }}" placeholder="Contoh: 1980">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">38. Pendidikan Ibu</label>
                            <select name="pendidikan_ibu_id" class="form-select">
                                <option value="">-- Pilih Pendidikan --</option>
                                @foreach($master['pendidikan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pendidikan_ibu_id', $siswa->pendidikan_ibu_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">39. Pekerjaan Ibu</label>
                            <select name="pekerjaan_ibu_id" class="form-select">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($master['pekerjaan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pekerjaan_ibu_id', $siswa->pekerjaan_ibu_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">40. Penghasilan Bulanan Ibu</label>
                            <select name="penghasilan_ibu_id" class="form-select">
                                <option value="">-- Pilih Penghasilan --</option>
                                @foreach($master['penghasilan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('penghasilan_ibu_id', $siswa->penghasilan_ibu_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">41. Kebutuhan Khusus Ibu</label>
                            <select name="kebutuhan_khusus_ibu_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($master['kebutuhan_khusus'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('kebutuhan_khusus_ibu_id', $siswa->kebutuhan_khusus_ibu_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- WALI -->
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 4: Data Wali (Poin 42 - 47)</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">42. Nama Wali</label>
                            <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $siswa->nama_wali) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">43. NIK Wali</label>
                            <input type="text" name="nik_wali" class="form-control" value="{{ old('nik_wali', $siswa->nik_wali) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">44. Tahun Lahir Wali</label>
                            <input type="number" name="tahun_lahir_wali" class="form-control" value="{{ old('tahun_lahir_wali', $siswa->tahun_lahir_wali) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">45. Pendidikan Wali</label>
                            <select name="pendidikan_wali_id" class="form-select">
                                <option value="">-- Pilih Pendidikan --</option>
                                @foreach($master['pendidikan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pendidikan_wali_id', $siswa->pendidikan_wali_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">46. Pekerjaan Wali</label>
                            <select name="pekerjaan_wali_id" class="form-select">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($master['pekerjaan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('pekerjaan_wali_id', $siswa->pekerjaan_wali_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">47. Penghasilan Bulanan Wali</label>
                            <select name="penghasilan_wali_id" class="form-select">
                                <option value="">-- Pilih Penghasilan --</option>
                                @foreach($master['penghasilan'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('penghasilan_wali_id', $siswa->penghasilan_wali_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: KONTAK & DATA PERIODIK (Poin 48-50 & Periodik 1-7) -->
                <div class="tab-pane fade" id="tab3" role="tabpanel">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 5: Kontak Peserta Didik (Poin 48 - 50)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">48. Nomor Telepon Rumah</label>
                            <input type="text" name="nomor_telepon_rumah" class="form-control" value="{{ old('nomor_telepon_rumah', $siswa->nomor_telepon_rumah) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">49. Nomor HP / WhatsApp</label>
                            <input type="text" name="nomor_hp" class="form-control" value="{{ old('nomor_hp', $siswa->nomor_hp) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">50. Email Pribadi</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $siswa->email) }}" placeholder="nama@email.com">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 6: Data Periodik Peserta Didik</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">1. Tinggi Badan (cm)</label>
                            <input type="number" name="tinggi_badan_cm" class="form-control" value="{{ old('tinggi_badan_cm', $siswa->tinggi_badan_cm) }}" placeholder="cm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">2. Berat Badan (kg)</label>
                            <input type="number" name="berat_badan_kg" class="form-control" value="{{ old('berat_badan_kg', $siswa->berat_badan_kg) }}" placeholder="kg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">3. Lingkar Kepala (cm)</label>
                            <input type="number" name="lingkar_kepala_cm" class="form-control" value="{{ old('lingkar_kepala_cm', $siswa->lingkar_kepala_cm) }}" placeholder="cm">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">4. Kategori Jarak ke Sekolah</label>
                            <select name="kategori_jarak_sekolah" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="kurang_1_km" @selected(old('kategori_jarak_sekolah', $siswa->kategori_jarak_sekolah) === 'kurang_1_km')>Kurang dari 1 km</option>
                                <option value="lebih_1_km" @selected(old('kategori_jarak_sekolah', $siswa->kategori_jarak_sekolah) === 'lebih_1_km')>Lebih dari 1 km</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Jarak ke Sekolah (km)</label>
                            <input type="number" name="jarak_sekolah_km" class="form-control" value="{{ old('jarak_sekolah_km', $siswa->jarak_sekolah_km) }}" placeholder="km">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">5. Waktu Tempuh</label>
                            <div class="input-group">
                                <input type="number" name="waktu_tempuh_jam" class="form-control" value="{{ old('waktu_tempuh_jam', $siswa->waktu_tempuh_jam ?? 0) }}" placeholder="0" min="0">
                                <span class="input-group-text small">Jam</span>
                                <input type="number" name="waktu_tempuh_menit" class="form-control" value="{{ old('waktu_tempuh_menit', $siswa->waktu_tempuh_menit ?? 0) }}" placeholder="15" min="0">
                                <span class="input-group-text small">Mnt</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">6. Jumlah Saudara Kandung</label>
                            <input type="number" name="jumlah_saudara_kandung" class="form-control" value="{{ old('jumlah_saudara_kandung', $siswa->jumlah_saudara_kandung ?? 0) }}" min="0">
                        </div>
                    </div>
                </div>

                <!-- TAB 4: PRESTASI & BEASISWA (BARIS DINAMIS JS) -->
                <div class="tab-pane fade" id="tab4" role="tabpanel">
                    <!-- PRESTASI -->
                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                        <h6 class="fw-bold text-primary mb-0">Prestasi Siswa</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahPrestasi">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris Prestasi
                        </button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle" id="tablePrestasi">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Prestasi</th>
                                    <th>Tingkat</th>
                                    <th>Nama Prestasi</th>
                                    <th style="width: 100px;">Tahun</th>
                                    <th>Penyelenggara</th>
                                    <th style="width: 110px;">Peringkat</th>
                                    <th style="width: 50px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPrestasi">
                                @php
                                    $prestasiList = old('prestasi', $siswa->prestasi ?? []);
                                @endphp
                                @forelse($prestasiList as $idx => $p)
                                    <tr>
                                        <td>
                                            <select name="prestasi[{{ $idx }}][jenis_prestasi_id]" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($master['jenis_prestasi'] as $item)
                                                    <option value="{{ $item->id }}" @selected(($p['jenis_prestasi_id'] ?? $p->jenis_prestasi_id ?? '') == $item->id)>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="prestasi[{{ $idx }}][tingkat_prestasi_id]" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($master['tingkat_prestasi'] as $item)
                                                    <option value="{{ $item->id }}" @selected(($p['tingkat_prestasi_id'] ?? $p->tingkat_prestasi_id ?? '') == $item->id)>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="prestasi[{{ $idx }}][nama_prestasi]" class="form-control form-control-sm" value="{{ $p['nama_prestasi'] ?? $p->nama_prestasi ?? '' }}" placeholder="Nama lomba/prestasi" required></td>
                                        <td><input type="number" name="prestasi[{{ $idx }}][tahun_prestasi]" class="form-control form-control-sm" value="{{ $p['tahun_prestasi'] ?? $p->tahun_prestasi ?? date('Y') }}" required></td>
                                        <td><input type="text" name="prestasi[{{ $idx }}][penyelenggara]" class="form-control form-control-sm" value="{{ $p['penyelenggara'] ?? $p->penyelenggara ?? '' }}" placeholder="Instansi/Penyelenggara"></td>
                                        <td><input type="text" name="prestasi[{{ $idx }}][peringkat]" class="form-control form-control-sm" value="{{ $p['peringkat'] ?? $p->peringkat ?? '' }}" placeholder="Juara 1"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Baris Kosong Awal -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- BEASISWA -->
                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                        <h6 class="fw-bold text-primary mb-0">Riwayat Beasiswa Siswa</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahBeasiswa">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris Beasiswa
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle" id="tableBeasiswa">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Beasiswa</th>
                                    <th>Keterangan / Sumber Beasiswa</th>
                                    <th style="width: 120px;">Tahun Mulai</th>
                                    <th style="width: 120px;">Tahun Selesai</th>
                                    <th style="width: 50px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyBeasiswa">
                                @php
                                    $beasiswaList = old('beasiswa', $siswa->beasiswa ?? []);
                                @endphp
                                @forelse($beasiswaList as $idx => $b)
                                    <tr>
                                        <td>
                                            <select name="beasiswa[{{ $idx }}][jenis_beasiswa_id]" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($master['jenis_beasiswa'] as $item)
                                                    <option value="{{ $item->id }}" @selected(($b['jenis_beasiswa_id'] ?? $b->jenis_beasiswa_id ?? '') == $item->id)>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="beasiswa[{{ $idx }}][keterangan]" class="form-control form-control-sm" value="{{ $b['keterangan'] ?? $b->keterangan ?? '' }}" placeholder="Beasiswa Prestasi Pemda" required></td>
                                        <td><input type="number" name="beasiswa[{{ $idx }}][tahun_mulai]" class="form-control form-control-sm" value="{{ $b['tahun_mulai'] ?? $b->tahun_mulai ?? date('Y') }}" required></td>
                                        <td><input type="number" name="beasiswa[{{ $idx }}][tahun_selesai]" class="form-control form-control-sm" value="{{ $b['tahun_selesai'] ?? $b->tahun_selesai ?? date('Y') }}" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Baris Kosong Awal -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 5: REGISTRASI MASUK & KELUAR -->
                <div class="tab-pane fade" id="tab5" role="tabpanel">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Bagian 7: Registrasi Peserta Didik (Masuk)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Kompetensi Keahlian / Jurusan</label>
                            <select name="kompetensi_keahlian_id" class="form-select">
                                <option value="">-- Tanpa Jurusan / Umum --</option>
                                @foreach($jurusanList as $j)
                                    <option value="{{ $j->id }}" @selected(old('kompetensi_keahlian_id', $siswa->kompetensi_keahlian_id) == $j->id)>
                                        {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">1. Jenis Pendaftaran</label>
                            <select name="jenis_pendaftaran_id" class="form-select">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($master['jenis_pendaftaran'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('jenis_pendaftaran_id', $siswa->jenis_pendaftaran_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">2. NIS / Nomor Induk PD</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}" placeholder="Nomor Induk Siswa">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">3. Tanggal Masuk Sekolah</label>
                            <input type="date" name="tanggal_masuk_sekolah" class="form-control" value="{{ old('tanggal_masuk_sekolah', $siswa->tanggal_masuk_sekolah?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">4. Asal Sekolah</label>
                            <input type="text" name="sekolah_asal" class="form-control" value="{{ old('sekolah_asal', $siswa->sekolah_asal) }}" placeholder="SMP / MTs Asal">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">5. Nomor Peserta UN Sebelumnya</label>
                            <input type="text" name="nomor_peserta_un_sebelumnya" class="form-control" value="{{ old('nomor_peserta_un_sebelumnya', $siswa->nomor_peserta_un_sebelumnya) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">6. No. Seri Ijazah Sebelumnya</label>
                            <input type="text" name="no_seri_ijazah_sebelumnya" class="form-control" value="{{ old('no_seri_ijazah_sebelumnya', $siswa->no_seri_ijazah_sebelumnya) }}">
                        </div>
                    </div>

                    <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Bagian 8: Status Keluar / Alumni (Jika Siswa Sudah Tidak Aktif)</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">1. Keluar Karena</label>
                            <select name="keluar_karena_id" class="form-select">
                                <option value="">-- Masih Bersekolah Aktif --</option>
                                @foreach($master['alasan_keluar'] as $item)
                                    <option value="{{ $item->id }}" @selected(old('keluar_karena_id', $siswa->keluar_karena_id) == $item->id)>
                                        {{ $item->kode ? $item->kode . ') ' : '' }}{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">2. Tanggal Keluar</label>
                            <input type="date" name="tanggal_keluar" class="form-control" value="{{ old('tanggal_keluar', $siswa->tanggal_keluar?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">3. Alasan / Keterangan Keluar</label>
                            <input type="text" name="alasan_keluar" class="form-control" value="{{ old('alasan_keluar', $siswa->alasan_keluar) }}" placeholder="Keterangan mutasi/lulus/alasan lainnya">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3">
            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                <i class="bi bi-floppy-fill me-1"></i> {{ $siswa->exists ? 'Perbarui Data Siswa' : 'Simpan ke Buku Induk' }}
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Dinamis Tambah/Hapus Baris Prestasi
    let prestasiIdx = {{ count($prestasiList ?? []) }};
    document.getElementById('btnTambahPrestasi').addEventListener('click', function() {
        const tbody = document.getElementById('tbodyPrestasi');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="prestasi[${prestasiIdx}][jenis_prestasi_id]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($master['jenis_prestasi'] as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="prestasi[${prestasiIdx}][tingkat_prestasi_id]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($master['tingkat_prestasi'] as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="prestasi[${prestasiIdx}][nama_prestasi]" class="form-control form-control-sm" placeholder="Nama lomba/prestasi" required></td>
            <td><input type="number" name="prestasi[${prestasiIdx}][tahun_prestasi]" class="form-control form-control-sm" value="{{ date('Y') }}" required></td>
            <td><input type="text" name="prestasi[${prestasiIdx}][penyelenggara]" class="form-control form-control-sm" placeholder="Instansi/Penyelenggara"></td>
            <td><input type="text" name="prestasi[${prestasiIdx}][peringkat]" class="form-control form-control-sm" placeholder="Juara 1"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        prestasiIdx++;
    });

    // Dinamis Tambah/Hapus Baris Beasiswa
    let beasiswaIdx = {{ count($beasiswaList ?? []) }};
    document.getElementById('btnTambahBeasiswa').addEventListener('click', function() {
        const tbody = document.getElementById('tbodyBeasiswa');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="beasiswa[${beasiswaIdx}][jenis_beasiswa_id]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($master['jenis_beasiswa'] as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="beasiswa[${beasiswaIdx}][keterangan]" class="form-control form-control-sm" placeholder="Nama program beasiswa" required></td>
            <td><input type="number" name="beasiswa[${beasiswaIdx}][tahun_mulai]" class="form-control form-control-sm" value="{{ date('Y') }}" required></td>
            <td><input type="number" name="beasiswa[${beasiswaIdx}][tahun_selesai]" class="form-control form-control-sm" value="{{ date('Y') }}" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        beasiswaIdx++;
    });

    // Event delegation untuk tombol hapus baris
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endpush
@endsection

