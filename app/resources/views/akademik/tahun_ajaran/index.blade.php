@extends('layouts.app')

@section('title', 'Tahun Ajaran & Semester')
@section('page_title', 'Pengaturan Tahun Ajaran & Semester')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Tahun Ajaran</h5>
            <small class="text-muted">Tentukan tahun ajaran dan semester aktif serta identitas kepala sekolah untuk cetak rapor</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Tahun Ajaran
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Kepala Sekolah & NIP</th>
                    <th>Tempat & Tgl Cetak Rapor</th>
                    <th class="text-center" style="width: 140px;">Status</th>
                    <th class="text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjaranList as $ta)
                    <tr class="{{ $ta->aktif ? 'table-primary bg-opacity-25' : '' }}">
                        <td class="fw-bold fs-6">
                            {{ $ta->nama_tahun }}
                            @if($ta->aktif)
                                <span class="badge bg-primary ms-1">Sedang Aktif</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $ta->semester === 'Ganjil' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }} px-3 py-1 rounded-pill">
                                Semester {{ $ta->semester }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $ta->nama_kepala_sekolah ?? '-' }}</div>
                            <small class="text-muted font-monospace">NIP: {{ $ta->nip_kepala_sekolah ?? '-' }}</small>
                        </td>
                        <td>
                            <div>{{ $ta->tempat_cetak_rapor ?? '-' }}</div>
                            <small class="text-muted">{{ $ta->tanggal_cetak_rapor ? $ta->tanggal_cetak_rapor->translatedFormat('d F Y') : '-' }}</small>
                        </td>
                        <td class="text-center">
                            @if($ta->aktif)
                                <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                            @else
                                <form action="{{ route('tahun-ajaran.aktif', $ta->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-pill" style="font-size: 0.75rem;">
                                        Set Aktifkan
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $ta->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @if(! $ta->aktif)
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $ta->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade text-start" id="modalEdit{{ $ta->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('tahun-ajaran.update', $ta->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Tahun Ajaran</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-8">
                                                        <label class="form-label small fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_tahun" class="form-control" value="{{ old('nama_tahun', $ta->nama_tahun) }}" required>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label small fw-semibold">Semester <span class="text-danger">*</span></label>
                                                        <select name="semester" class="form-select" required>
                                                            <option value="Ganjil" @selected($ta->semester === 'Ganjil')>Ganjil</option>
                                                            <option value="Genap" @selected($ta->semester === 'Genap')>Genap</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Kepala Sekolah</label>
                                                    <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $ta->nama_kepala_sekolah) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">NIP Kepala Sekolah</label>
                                                    <input type="text" name="nip_kepala_sekolah" class="form-control" value="{{ old('nip_kepala_sekolah', $ta->nip_kepala_sekolah) }}">
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tempat Cetak Rapor</label>
                                                        <input type="text" name="tempat_cetak_rapor" class="form-control" value="{{ old('tempat_cetak_rapor', $ta->tempat_cetak_rapor) }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tanggal Cetak Rapor</label>
                                                        <input type="date" name="tanggal_cetak_rapor" class="form-control" value="{{ old('tanggal_cetak_rapor', $ta->tanggal_cetak_rapor ? $ta->tanggal_cetak_rapor->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifEdit{{ $ta->id }}" {{ $ta->aktif ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="aktifEdit{{ $ta->id }}">Jadikan Tahun Ajaran Aktif Saat Ini</label>
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
                            <div class="modal fade text-start" id="modalHapus{{ $ta->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Tahun Ajaran</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus Tahun Ajaran <strong>{{ $ta->nama_tahun }} ({{ $ta->semester }})</strong>?</p>
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data tahun ajaran.</td>
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
            <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Tahun Ajaran Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="nama_tahun" class="form-control" placeholder="Contoh: 2026/2027" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepala_sekolah" class="form-control" placeholder="Contoh: Drs. H. Mulyono, M.Pd.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">NIP Kepala Sekolah</label>
                        <input type="text" name="nip_kepala_sekolah" class="form-control" placeholder="Contoh: 197001011995011001">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tempat Cetak Rapor</label>
                            <input type="text" name="tempat_cetak_rapor" class="form-control" placeholder="Contoh: Jakarta">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Cetak Rapor</label>
                            <input type="date" name="tanggal_cetak_rapor" class="form-control">
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifTambah">
                        <label class="form-check-label small" for="aktifTambah">Langsung Jadikan Tahun Ajaran Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Tahun Ajaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

