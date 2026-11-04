@extends('layouts.app')

@section('title', 'Master Referensi Dapodik')
@section('page_title', 'Kelola Master Referensi Dinamis (Dapodik)')

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Navigasi Kategori Referensi -->
    <div class="col-lg-3">
        <div class="card sticky-top" style="top: 85px;">
            <div class="card-header bg-light">
                <span class="fw-bold small text-uppercase text-secondary">
                    <i class="bi bi-list-nested me-1"></i> Kategori Pilihan Dapodik
                </span>
            </div>
            <div class="list-group list-group-flush" style="max-height: 75vh; overflow-y: auto;">
                @foreach($kategoriList as $key => $label)
                    <a href="{{ route('referensi.index', ['kategori' => $key]) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2.5 {{ $activeKategori === $key ? 'active fw-bold' : '' }}">
                        <span>
                            <i class="bi bi-tag-fill me-2 {{ $activeKategori === $key ? 'text-white' : 'text-primary' }}" style="font-size: 0.85rem;"></i>
                            {{ $label }}
                        </span>
                        <span class="badge {{ $activeKategori === $key ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }} rounded-pill">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Referensi & Tombol Tambah Data -->
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="mb-0 fw-bold">Kategori: {{ $activeLabel }}</h5>
                    <small class="text-muted">Kelola kode dan label opsi yang muncul pada formulir Dapodik & Buku Induk</small>
                </div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Opsi Baru
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">Urutan</th>
                            <th style="width: 100px;">Kode Dapodik</th>
                            <th>Nama Pilihan / Opsi</th>
                            <th style="width: 120px;" class="text-center">Status</th>
                            <th style="width: 130px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="{{ ! $item->aktif ? 'table-light opacity-75' : '' }}">
                                <td class="text-secondary fw-semibold">{{ $item->urutan }}</td>
                                <td>
                                    <span class="badge bg-dark-subtle text-dark font-monospace px-2 py-1">
                                        {{ $item->kode ?? '-' }}
                                    </span>
                                </td>
                                <td class="fw-medium">
                                    {{ $item->nama }}
                                    @if(! $item->aktif)
                                        <small class="badge bg-secondary ms-1">Nonaktif</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('referensi.toggle', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->aktif ? 'btn-success' : 'btn-outline-secondary' }} py-0 px-2 rounded-pill" style="font-size: 0.75rem;" title="Klik untuk ubah status">
                                            <i class="bi {{ $item->aktif ? 'bi-check-circle-fill' : 'bi-dash-circle' }}"></i>
                                            {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Edit Item -->
                                    <div class="modal fade text-start" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('referensi.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">Edit Opsi {{ $activeLabel }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Kode Dapodik</label>
                                                            <input type="text" name="kode" class="form-control" value="{{ old('kode', $item->kode) }}" placeholder="Contoh: 01, 02, 99">
                                                            <small class="text-muted">Sesuai kode formulir Dapodik (boleh dikosongkan jika tidak ada)</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nama Pilihan <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nomor Urutan Tampilan</label>
                                                            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan) }}" min="0">
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifEdit{{ $item->id }}" {{ $item->aktif ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="aktifEdit{{ $item->id }}">Opsi Aktif (Tampil di formulir)</label>
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

                                    <!-- Modal Hapus Item -->
                                    <div class="modal fade text-start" id="modalHapus{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <form action="{{ route('referensi.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold text-danger">Hapus Opsi</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="small mb-0">Apakah Anda yakin ingin menghapus opsi <strong>{{ $item->nama }}</strong>?</p>
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
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    Belum ada data referensi untuk kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Baru -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('referensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kategori" value="{{ $activeKategori }}">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Opsi Baru: {{ $activeLabel }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kode Dapodik</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: 01, 02, 99">
                        <small class="text-muted">Sesuai kode formulir Dapodik (boleh dikosongkan jika tidak ada)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Pilihan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pegawai Negeri Sipil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Urutan Tampilan</label>
                        <input type="number" name="urutan" class="form-control" value="{{ ($items->max('urutan') ?? 0) + 1 }}" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifTambah" checked>
                        <label class="form-check-label small" for="aktifTambah">Opsi Aktif (Tampil di formulir)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Opsi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

