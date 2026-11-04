<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KompetensiKeahlianController;
use App\Http\Controllers\LegerRaporController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\ReferensiDapodikController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use Illuminate\Support\Facades\Route;

// Otentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Area Terproteksi (Login Admin)
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Referensi Dinamis Dapodik
    Route::prefix('referensi')->name('referensi.')->group(function () {
        Route::get('/', [ReferensiDapodikController::class, 'index'])->name('index');
        Route::post('/', [ReferensiDapodikController::class, 'store'])->name('store');
        Route::put('/{referensi}', [ReferensiDapodikController::class, 'update'])->name('update');
        Route::patch('/{referensi}/toggle', [ReferensiDapodikController::class, 'toggle'])->name('toggle');
        Route::delete('/{referensi}', [ReferensiDapodikController::class, 'destroy'])->name('destroy');
    });

    // Akademik: Kompetensi Keahlian / Jurusan
    Route::resource('jurusan', KompetensiKeahlianController::class)->except(['show', 'create', 'edit']);

    // Akademik: Tahun Ajaran & Semester
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show', 'create', 'edit']);
    Route::patch('/tahun-ajaran/{tahunAjaran}/aktif', [TahunAjaranController::class, 'setAktif'])->name('tahun-ajaran.aktif');

    // Akademik: Guru & Wali Kelas
    Route::resource('guru', GuruController::class)->except(['show', 'create', 'edit']);

    // Akademik: Mata Pelajaran
    Route::resource('mapel', MapelController::class)->except(['show', 'create', 'edit']);

    // Akademik: Kelas & Ploting Rombel
    Route::resource('kelas', KelasController::class)->except(['show', 'create', 'edit']);
    Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
        Route::get('/kelola', [KelasController::class, 'kelolaRombel'])->name('kelola');
        Route::post('/siswa', [KelasController::class, 'tambahSiswa'])->name('siswa.tambah');
        Route::delete('/siswa/{anggota}', [KelasController::class, 'hapusSiswa'])->name('siswa.hapus');
        Route::post('/salin-rombel', [KelasController::class, 'salinRombel'])->name('salin-rombel');
        Route::post('/kehadiran', [KelasController::class, 'updateKehadiran'])->name('kehadiran');
        Route::post('/pengampu', [KelasController::class, 'tambahMapel'])->name('pengampu.tambah');
        Route::put('/pengampu/{pengampu}', [KelasController::class, 'updatePengampu'])->name('pengampu.update');
        Route::delete('/pengampu/{pengampu}', [KelasController::class, 'hapusMapel'])->name('pengampu.destroy');
    });

    // Buku Induk Siswa
    Route::get('/siswa/template/download', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/{siswa}/cetak-dapodik', [SiswaController::class, 'cetakDapodik'])->name('siswa.cetak.dapodik');
    Route::get('/siswa/{siswa}/cetak-buku-induk', [SiswaController::class, 'cetakBukuInduk'])->name('siswa.cetak.buku-induk');

    // Leger Nilai & Cetak Rapor
    Route::prefix('leger')->name('leger.')->group(function () {
        Route::get('/', [LegerRaporController::class, 'index'])->name('index');
        Route::get('/template/{pengampu}', [LegerRaporController::class, 'downloadTemplate'])->name('template');
        Route::post('/import/{pengampu}', [LegerRaporController::class, 'importNilai'])->name('import');
        Route::get('/{kelas}/export', [LegerRaporController::class, 'exportLeger'])->name('export');
        Route::get('/{kelas}/cetak-leger', [LegerRaporController::class, 'cetakLeger'])->name('cetak-leger');
        Route::get('/{kelas}/rapor/{anggota?}', [LegerRaporController::class, 'cetakRapor'])->name('rapor');
    });
});
