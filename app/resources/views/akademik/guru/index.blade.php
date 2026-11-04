@extends('layouts.app')

@section('title', 'Data Guru & Wali Kelas')
@section('page_title', 'Data Guru & Wali Kelas')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Guru & Tenaga Pengajar</h5>
            <small class="text-muted">Master data pendidik untuk penetapan Wali Kelas dan Guru Pengampu Mapel</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus me-1"></i> Tambah Guru Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">No</th>
                    <th>Nama Lengkap Guru</th>
                    <th>NIP</th>
                    <th>L/P</th>
                    <th>Nomor WhatsApp / HP</th>
                    <th class="text-center">Peran & Penugasan</th>
                    <th class="text-center" style="width: 100px;">Status</th>
                    <th class="text-end" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guruList as $idx => $guru)
                    <tr class="{{ ! $guru->aktif ? 'table-light opacity-75' : '' }}">
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-semibold">
                            {{ $guru->nama_guru }}
                        </td>
                        <td><span class="font-monospace text-muted">{{ $guru->nip ?? '-' }}</span></td>
                        <td>
                            <span class="badge {{ $guru->jenis_kelamin === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-circle p-2">
                                {{ $guru->jenis_kelamin }}
                            </span>
                        </td>
                        <td>{{ $guru->nomor_hp ?? '-' }}</td>
                        <td class="text-center">
                            @if($guru->kelas_wali_count > 0)
                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill me-1">
                                    <i class="bi bi-shield-check"></i> Wali {{ $guru->kelas_wali_count }} Kelas
                                </span>
                            @endif
                            @if($guru->pengampu_mapel_count > 0)
                                <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill">
                                    <i class="bi bi-book"></i> {{ $guru->pengampu_mapel_count }} Mapel
                                </span>
                            @endif
                            @if($guru->kelas_wali_count === 0 && $guru->pengampu_mapel_count === 0)
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($guru->aktif)
                                <span class="badge bg-success px-2 py-1 rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1 rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $guru->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $guru->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade text-start" id="modalEdit{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Data Guru</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_guru" class="form-control" value="{{ old('nama_guru', $guru->nama_guru) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">NIP</label>
                                                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}">
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                                        <select name="jenis_kelamin" class="form-select" required>
                                                            <option value="L" @selected($guru->jenis_kelamin === 'L')>Laki-laki (L)</option>
                                                            <option value="P" @selected($guru->jenis_kelamin === 'P')>Perempuan (P)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Nomor WhatsApp / HP</label>
                                                        <input type="text" name="nomor_hp" class="form-control" value="{{ old('nomor_hp', $guru->nomor_hp) }}">
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifEdit{{ $guru->id }}" {{ $guru->aktif ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="aktifEdit{{ $guru->id }}">Guru Masih Aktif Mengajar</label>
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
                            <div class="modal fade text-start" id="modalHapus{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('guru.destroy', $guru->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Guru</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus data guru <strong>{{ $guru->nama_guru }}</strong>?</p>
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
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Guru Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_guru" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">NIP</label>
                        <input type="text" name="nip" class="form-control" placeholder="Contoh: 198002152005011002">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L">Laki-laki (L)</option>
                                <option value="P">Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nomor WhatsApp / HP</label>
                            <input type="text" name="nomor_hp" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifTambah" checked>
                        <label class="form-check-label small" for="aktifTambah">Guru Aktif Mengajar</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

