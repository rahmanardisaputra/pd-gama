@extends('layouts.app')

@section('title', 'Data Kompetensi Keahlian')
@section('page_title', 'Kompetensi Keahlian / Jurusan')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Jurusan / Program Keahlian</h5>
            <small class="text-muted">Master jurusan untuk penempatan siswa SMK/SMA</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jurusan
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 160px;">Kode Jurusan</th>
                    <th>Nama Kompetensi Keahlian</th>
                    <th class="text-center" style="width: 140px;">Jumlah Kelas</th>
                    <th class="text-center" style="width: 140px;">Jumlah Siswa</th>
                    <th class="text-end" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurusanList as $idx => $jurusan)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><span class="badge bg-primary-subtle text-primary font-monospace fs-7 px-2 py-1">{{ $jurusan->kode_jurusan }}</span></td>
                        <td class="fw-semibold">{{ $jurusan->nama_jurusan }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">{{ $jurusan->kelas_count }} Rombel</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info rounded-pill px-3">{{ $jurusan->siswa_count }} Siswa</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $jurusan->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $jurusan->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade text-start" id="modalEdit{{ $jurusan->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('jurusan.update', $jurusan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Jurusan</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Kode Jurusan <span class="text-danger">*</span></label>
                                                    <input type="text" name="kode_jurusan" class="form-control" value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Kompetensi Keahlian <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_jurusan" class="form-control" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required>
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
                            <div class="modal fade text-start" id="modalHapus{{ $jurusan->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Hapus Jurusan</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small mb-0">Hapus jurusan <strong>{{ $jurusan->nama_jurusan }}</strong>?</p>
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data jurusan.</td>
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
            <form action="{{ route('jurusan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Jurusan Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kode Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_jurusan" class="form-control" placeholder="Contoh: RPL, TKJ, DKV" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kompetensi Keahlian <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jurusan" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Jurusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

