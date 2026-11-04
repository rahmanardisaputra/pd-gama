<?php

namespace App\Http\Controllers;

use App\Exports\TemplateSiswaExport;
use App\Imports\SiswaImport;
use App\Models\BeasiswaSiswa;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\PrestasiSiswa;
use App\Models\ReferensiDapodik;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiswaController extends Controller
{
    /**
     * Ambil seluruh master referensi dapodik dalam satu associative array.
     */
    protected function getMasterReferensi(): array
    {
        $kategoriList = [
            'agama',
            'kebutuhan_khusus',
            'tempat_tinggal',
            'moda_transportasi',
            'pendidikan',
            'pekerjaan',
            'penghasilan',
            'alasan_menolak_pip',
            'jenis_prestasi',
            'tingkat_prestasi',
            'jenis_beasiswa',
            'jenis_pendaftaran',
            'alasan_keluar',
        ];

        $master = [];
        foreach ($kategoriList as $kat) {
            $master[$kat] = ReferensiDapodik::kategori($kat)->get();
        }

        return $master;
    }

    public function index(Request $request): View
    {
        $taAktif = TahunAjaran::where('aktif', true)->first();

        $search = $request->get('search');
        $status = $request->get('status', 'aktif'); // aktif, keluar, semua
        $kelasId = $request->get('kelas_id');
        $jurusanId = $request->get('jurusan_id');

        $query = Siswa::with([
            'kompetensiKeahlian',
            'agama',
            'anggotaRombel' => function ($q) use ($taAktif) {
                if ($taAktif) {
                    $q->whereHas('kelas', fn ($k) => $k->where('tahun_ajaran_id', $taAktif->id))->with('kelas');
                }
            },
        ]);

        if ($status === 'aktif') {
            $query->whereNull('keluar_karena_id');
        } elseif ($status === 'keluar') {
            $query->whereNotNull('keluar_karena_id');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($jurusanId) {
            $query->where('kompetensi_keahlian_id', $jurusanId);
        }

        if ($kelasId) {
            $query->whereHas('anggotaRombel', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $siswaList = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $daftarKelas = $taAktif ? Kelas::where('tahun_ajaran_id', $taAktif->id)->orderBy('nama_kelas')->get() : collect();
        $daftarJurusan = KompetensiKeahlian::orderBy('nama_jurusan')->get();

        return view('siswa.index', compact(
            'siswaList',
            'daftarKelas',
            'daftarJurusan',
            'search',
            'status',
            'kelasId',
            'jurusanId',
            'taAktif'
        ));
    }

    public function create(): View
    {
        $master = $this->getMasterReferensi();
        $jurusanList = KompetensiKeahlian::orderBy('nama_jurusan')->get();
        $siswa = new Siswa;

        return view('siswa.form', compact('master', 'jurusanList', 'siswa'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSiswa($request);

        $siswa = Siswa::create($validated);

        $this->syncPrestasiDanBeasiswa($request, $siswa);

        return redirect()->route('siswa.show', $siswa->id)
            ->with('success', "Data siswa {$siswa->nama_lengkap} berhasil disimpan ke Buku Induk.");
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load([
            'kompetensiKeahlian',
            'agama',
            'berkebutuhanKhusus',
            'tempatTinggal',
            'modaTransportasi',
            'pekerjaanSiswa',
            'alasanMenolakPip',
            'pendidikanAyah',
            'pekerjaanAyah',
            'penghasilanAyah',
            'kebutuhanKhususAyah',
            'pendidikanIbu',
            'pekerjaanIbu',
            'penghasilanIbu',
            'kebutuhanKhususIbu',
            'pendidikanWali',
            'pekerjaanWali',
            'penghasilanWali',
            'jenisPendaftaran',
            'keluarKarena',
            'prestasi.jenisPrestasi',
            'prestasi.tingkatPrestasi',
            'beasiswa.jenisBeasiswa',
            'anggotaRombel.kelas.tahunAjaran',
            'anggotaRombel.kelas.waliKelas',
            'anggotaRombel.nilaiSiswa.pengampuMapel.mapel',
        ]);

        return view('siswa.detail', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        $siswa->load(['prestasi', 'beasiswa']);
        $master = $this->getMasterReferensi();
        $jurusanList = KompetensiKeahlian::orderBy('nama_jurusan')->get();

        return view('siswa.form', compact('master', 'jurusanList', 'siswa'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $this->validateSiswa($request, $siswa->id);

        $siswa->update($validated);

        $this->syncPrestasiDanBeasiswa($request, $siswa);

        return redirect()->route('siswa.show', $siswa->id)
            ->with('success', "Data siswa {$siswa->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $nama = $siswa->nama_lengkap;
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', "Data siswa {$nama} berhasil dihapus dari Buku Induk.");
    }

    public function cetakDapodik(Siswa $siswa): View
    {
        $siswa->load([
            'kompetensiKeahlian',
            'agama',
            'berkebutuhanKhusus',
            'tempatTinggal',
            'modaTransportasi',
            'pekerjaanSiswa',
            'alasanMenolakPip',
            'pendidikanAyah',
            'pekerjaanAyah',
            'penghasilanAyah',
            'kebutuhanKhususAyah',
            'pendidikanIbu',
            'pekerjaanIbu',
            'penghasilanIbu',
            'kebutuhanKhususIbu',
            'pendidikanWali',
            'pekerjaanWali',
            'penghasilanWali',
            'jenisPendaftaran',
            'keluarKarena',
            'prestasi.jenisPrestasi',
            'prestasi.tingkatPrestasi',
            'beasiswa.jenisBeasiswa',
        ]);

        return view('siswa.cetak_dapodik', compact('siswa'));
    }

    public function cetakBukuInduk(Siswa $siswa): View
    {
        $siswa->load([
            'kompetensiKeahlian',
            'agama',
            'berkebutuhanKhusus',
            'tempatTinggal',
            'modaTransportasi',
            'pekerjaanSiswa',
            'alasanMenolakPip',
            'pendidikanAyah',
            'pekerjaanAyah',
            'penghasilanAyah',
            'kebutuhanKhususAyah',
            'pendidikanIbu',
            'pekerjaanIbu',
            'penghasilanIbu',
            'kebutuhanKhususIbu',
            'pendidikanWali',
            'pekerjaanWali',
            'penghasilanWali',
            'jenisPendaftaran',
            'keluarKarena',
            'prestasi.jenisPrestasi',
            'prestasi.tingkatPrestasi',
            'beasiswa.jenisBeasiswa',
            'anggotaRombel.kelas.tahunAjaran',
            'anggotaRombel.kelas.waliKelas',
            'anggotaRombel.nilaiSiswa.pengampuMapel.mapel',
        ]);

        $taAktif = TahunAjaran::where('aktif', true)->first();

        return view('siswa.cetak_buku_induk', compact('siswa', 'taAktif'));
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new TemplateSiswaExport, 'Format_Import_Buku_Induk_Siswa.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ]);

        try {
            $import = new SiswaImport($request->input('kelas_id') ? (int) $request->input('kelas_id') : null);
            Excel::import($import, $request->file('file_excel'));

            $pesan = "Import berhasil! {$import->importedCount} siswa baru ditambahkan";
            if ($import->updatedCount > 0) {
                $pesan .= ", dan {$import->updatedCount} siswa diperbarui datanya.";
            } else {
                $pesan .= '.';
            }

            return redirect()->route('siswa.index')->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')
                ->with('error', 'Gagal memproses file Excel: '.$e->getMessage());
        }
    }

    protected function validateSiswa(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'tanggal_formulir' => ['nullable', 'date'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nisn' => ['nullable', 'string', 'max:10', 'unique:siswa,nisn,'.$id],
            'nik' => ['nullable', 'string', 'max:16', 'unique:siswa,nik,'.$id],
            'no_kk' => ['nullable', 'string', 'max:16'],
            'tempat_lahir' => ['required', 'string', 'max:60'],
            'tanggal_lahir' => ['required', 'date'],
            'no_registrasi_akta_lahir' => ['nullable', 'string', 'max:60'],
            'agama_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'kewarganegaraan' => ['required', 'in:WNI,WNA'],
            'nama_negara' => ['nullable', 'string', 'max:60'],
            'berkebutuhan_khusus_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'alamat_jalan' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'nama_dusun' => ['nullable', 'string', 'max:60'],
            'nama_kelurahan_desa' => ['nullable', 'string', 'max:60'],
            'kecamatan' => ['nullable', 'string', 'max:60'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'lintang' => ['nullable', 'string', 'max:30'],
            'bujur' => ['nullable', 'string', 'max:30'],
            'tempat_tinggal_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'moda_transportasi_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'anak_keberapa' => ['nullable', 'integer', 'min:1'],
            'pekerjaan_siswa_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'punya_kip' => ['nullable', 'boolean'],
            'tetap_terima_kip' => ['nullable', 'boolean'],
            'alasan_menolak_pip_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'nama_ayah' => ['nullable', 'string', 'max:150'],
            'nik_ayah' => ['nullable', 'string', 'max:16'],
            'tahun_lahir_ayah' => ['nullable', 'integer'],
            'pendidikan_ayah_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'pekerjaan_ayah_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'penghasilan_ayah_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'kebutuhan_khusus_ayah_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'nama_ibu' => ['nullable', 'string', 'max:150'],
            'nik_ibu' => ['nullable', 'string', 'max:16'],
            'tahun_lahir_ibu' => ['nullable', 'integer'],
            'pendidikan_ibu_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'pekerjaan_ibu_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'penghasilan_ibu_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'kebutuhan_khusus_ibu_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'nama_wali' => ['nullable', 'string', 'max:150'],
            'nik_wali' => ['nullable', 'string', 'max:16'],
            'tahun_lahir_wali' => ['nullable', 'integer'],
            'pendidikan_wali_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'pekerjaan_wali_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'penghasilan_wali_id' => ['nullable', 'exists:referensi_dapodik,id'],

            'nomor_telepon_rumah' => ['nullable', 'string', 'max:20'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],

            'tinggi_badan_cm' => ['nullable', 'integer'],
            'berat_badan_kg' => ['nullable', 'integer'],
            'lingkar_kepala_cm' => ['nullable', 'integer'],
            'kategori_jarak_sekolah' => ['nullable', 'in:kurang_1_km,lebih_1_km'],
            'jarak_sekolah_km' => ['nullable', 'integer'],
            'waktu_tempuh_jam' => ['nullable', 'integer'],
            'waktu_tempuh_menit' => ['nullable', 'integer'],
            'jumlah_saudara_kandung' => ['nullable', 'integer'],

            'kompetensi_keahlian_id' => ['nullable', 'exists:kompetensi_keahlian,id'],
            'jenis_pendaftaran_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'nis' => ['nullable', 'string', 'max:30'],
            'tanggal_masuk_sekolah' => ['nullable', 'date'],
            'sekolah_asal' => ['nullable', 'string', 'max:100'],
            'nomor_peserta_un_sebelumnya' => ['nullable', 'string', 'max:30'],
            'no_seri_ijazah_sebelumnya' => ['nullable', 'string', 'max:40'],
            'no_skhun_sebelumnya' => ['nullable', 'string', 'max:40'],

            'keluar_karena_id' => ['nullable', 'exists:referensi_dapodik,id'],
            'tanggal_keluar' => ['nullable', 'date'],
            'alasan_keluar' => ['nullable', 'string'],
        ]);
    }

    protected function syncPrestasiDanBeasiswa(Request $request, Siswa $siswa): void
    {
        // Sync Prestasi
        $siswa->prestasi()->delete();
        $prestasiList = $request->input('prestasi', []);
        foreach ($prestasiList as $p) {
            if (! empty($p['nama_prestasi']) && ! empty($p['jenis_prestasi_id']) && ! empty($p['tingkat_prestasi_id'])) {
                PrestasiSiswa::create([
                    'siswa_id' => $siswa->id,
                    'jenis_prestasi_id' => $p['jenis_prestasi_id'],
                    'tingkat_prestasi_id' => $p['tingkat_prestasi_id'],
                    'nama_prestasi' => $p['nama_prestasi'],
                    'tahun_prestasi' => $p['tahun_prestasi'] ?? date('Y'),
                    'penyelenggara' => $p['penyelenggara'] ?? '-',
                    'peringkat' => $p['peringkat'] ?? null,
                ]);
            }
        }

        // Sync Beasiswa
        $siswa->beasiswa()->delete();
        $beasiswaList = $request->input('beasiswa', []);
        foreach ($beasiswaList as $b) {
            if (! empty($b['keterangan']) && ! empty($b['jenis_beasiswa_id'])) {
                BeasiswaSiswa::create([
                    'siswa_id' => $siswa->id,
                    'jenis_beasiswa_id' => $b['jenis_beasiswa_id'],
                    'keterangan' => $b['keterangan'],
                    'tahun_mulai' => $b['tahun_mulai'] ?? date('Y'),
                    'tahun_selesai' => $b['tahun_selesai'] ?? date('Y'),
                ]);
            }
        }
    }
}
