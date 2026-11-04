@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page_title', 'Mata Pelajaran')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Mata Pelajaran</h5>
            <small class="text-muted">Master mata pelajaran dan nomor urutan kolom cetak leger/rapor</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Mapel
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Urutan Leger</th>
                    <th style="width: 140px;">Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th style="width: 120px;">Kelompok</th>
                    <th class="text-center" style="width: 140px;">Kelas Pengampu</th>
                    <th class="text-end" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapelList as $mapel)
                    <tr>
                        <td class="fw-bold text-secondary text-center">{{ $mapel->urutan_leger }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary font-monospace px-2 py-1">
                                {{ $mapel->kode_mapel }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ $mapel->nama_mapel }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                Kelompok {{ $mapel->kelompok }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info rounded-pill px-3">
                                {{ $mapel->pengampu_mapel_count }} Kelas
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $mapel->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $mapel->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade text-start" id="modalEdit{{ $mapel->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('mapel.update', $mapel->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Mata Pelajaran</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                                                        <input type="text" name="kode_mapel" class="form-control font-monospace" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Kelompok <span class="text-danger">*</span></label>
                                                        <select name="kelompok" class="form-select" required>
                                                            <option value="A" @selected($mapel->kelompok === 'A')>Kelompok A (Umum)</option>
                                                            <option value="B" @selected($mapel->kelompok === 'B')>Kelompok B (Umum)</option>
                                                            <option value="C" @selected($mapel->kelompok === 'C')>Kelompok C (Kejuruan)</option>
                                                            <option value="Muatan Lokal" @selected($mapel->kelompok === 'Muatan Lokal')>Muatan Lokal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_mapel" class="form-control" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Urutan Kolom Leger <span class="text-danger">*</span></label>
                                                    <input type="number" name="urutan_leger" class="form-control" value="{{ old('urutan_leger', $mapel->urutan_leger) }}" min="1" required>
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
                            <div class="modal fade text-start" id="modalHapus{{ $mapel->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('mapel.destroy', $mapel->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Mapel</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus mapel <strong>{{ $mapel->nama_mapel }}</strong>?</p>
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada mata pelajaran.</td>
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
            <form action="{{ route('mapel.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Mata Pelajaran Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                            <input type="text" name="kode_mapel" class="form-control font-monospace" placeholder="MTK, PAI, RPL" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kelompok <span class="text-danger">*</span></label>
                            <select name="kelompok" class="form-select" required>
                                <option value="A">Kelompok A (Umum)</option>
                                <option value="B">Kelompok B (Umum)</option>
                                <option value="C">Kelompok C (Kejuruan)</option>
                                <option value="Muatan Lokal">Muatan Lokal</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mapel" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Urutan Kolom Leger</label>
                        <input type="number" name="urutan_leger" class="form-control" value="{{ ($mapelList->max('urutan_leger') ?? 0) + 1 }}" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Mapel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

