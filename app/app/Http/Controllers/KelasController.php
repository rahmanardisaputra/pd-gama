<?php

namespace App\Http\Controllers;

use App\Models\AnggotaRombel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\Mapel;
use App\Models\PengampuMapel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $taAktif = TahunAjaran::where('aktif', true)->first();
        $taId = $request->get('tahun_ajaran_id', $taAktif?->id);

        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama_tahun')->orderBy('semester')->get();
        $daftarJurusan = KompetensiKeahlian::orderBy('nama_jurusan')->get();
        $daftarGuru = Guru::where('aktif', true)->orderBy('nama_guru')->get();

        $kelasList = Kelas::with(['tahunAjaran', 'kompetensiKeahlian', 'waliKelas'])
            ->withCount(['anggotaRombel', 'pengampuMapel'])
            ->when($taId, fn ($q) => $q->where('tahun_ajaran_id', $taId))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('akademik.kelas.index', compact(
            'kelasList',
            'daftarTahunAjaran',
            'daftarJurusan',
            'daftarGuru',
            'taId',
            'taAktif'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'nama_kelas' => ['required', 'string', 'max:30'],
            'tingkat' => ['required', 'integer', 'between:1,13'],
            'kompetensi_keahlian_id' => ['nullable', 'exists:kompetensi_keahlian,id'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index', ['tahun_ajaran_id' => $validated['tahun_ajaran_id']])
            ->with('success', "Kelas {$validated['nama_kelas']} berhasil ditambahkan.");
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:30'],
            'tingkat' => ['required', 'integer', 'between:1,13'],
            'kompetensi_keahlian_id' => ['nullable', 'exists:kompetensi_keahlian,id'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index', ['tahun_ajaran_id' => $kela->tahun_ajaran_id])
            ->with('success', "Data kelas {$kela->nama_kelas} berhasil diperbarui.");
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $taId = $kela->tahun_ajaran_id;
        $nama = $kela->nama_kelas;

        try {
            $kela->delete();

            return redirect()->route('kelas.index', ['tahun_ajaran_id' => $taId])
                ->with('success', "Kelas {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('kelas.index', ['tahun_ajaran_id' => $taId])
                ->with('error', "Gagal menghapus kelas {$nama} karena masih terdapat anggota atau data nilai.");
        }
    }

    /**
     * Halaman Kelola Anggota Rombel & Pengampu Mapel
     */
    public function kelolaRombel(Kelas $kelas): View
    {
        $kelas->load([
            'tahunAjaran',
            'kompetensiKeahlian',
            'waliKelas',
            'anggotaRombel.siswa',
            'pengampuMapel.mapel',
            'pengampuMapel.guru',
        ]);

        // Siswa yang belum masuk ke kelas manapun di tahun ajaran ini dan belum keluar
        $siswaTerdaftarIds = AnggotaRombel::whereHas('kelas', function ($q) use ($kelas) {
            $q->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
        })->pluck('siswa_id');

        $siswaTersedia = Siswa::whereNull('keluar_karena_id')
            ->whereNotIn('id', $siswaTerdaftarIds)
            ->orderBy('nama_lengkap')
            ->get();

        // Daftar kelas lain untuk opsi Salin Rombel
        $kelasListLain = Kelas::where('id', '!=', $kelas->id)
            ->with('tahunAjaran')
            ->orderByDesc('id')
            ->get();

        // Mapel yang belum ada di kelas ini
        $mapelTerdaftarIds = $kelas->pengampuMapel->pluck('mapel_id');
        $mapelTersedia = Mapel::whereNotIn('id', $mapelTerdaftarIds)
            ->orderBy('urutan_leger')
            ->get();

        $daftarGuru = Guru::where('aktif', true)->orderBy('nama_guru')->get();

        return view('rombel.kelola', compact(
            'kelas',
            'siswaTersedia',
            'kelasListLain',
            'mapelTersedia',
            'daftarGuru'
        ));
    }

    public function tambahSiswa(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_ids' => ['required', 'array'],
            'siswa_ids.*' => ['exists:siswa,id'],
        ]);

        $maxAbsen = $kelas->anggotaRombel()->max('nomor_urut_absen') ?? 0;

        foreach ($validated['siswa_ids'] as $siswaId) {
            $maxAbsen++;
            AnggotaRombel::firstOrCreate(
                [
                    'kelas_id' => $kelas->id,
                    'siswa_id' => $siswaId,
                ],
                [
                    'nomor_urut_absen' => $maxAbsen,
                    'jumlah_sakit' => 0,
                    'jumlah_izin' => 0,
                    'jumlah_alpa' => 0,
                ]
            );
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', count($validated['siswa_ids']).' siswa berhasil dimasukkan ke kelas '.$kelas->nama_kelas.'.');
    }

    public function hapusSiswa(Kelas $kelas, AnggotaRombel $anggota): RedirectResponse
    {
        if ($anggota->kelas_id === $kelas->id) {
            $anggota->delete();
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    public function salinRombel(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_asal_id' => ['required', 'exists:kelas,id'],
        ]);

        $kelasAsal = Kelas::findOrFail($validated['kelas_asal_id']);
        $siswaAsalIds = $kelasAsal->anggotaRombel()->pluck('siswa_id');

        $maxAbsen = $kelas->anggotaRombel()->max('nomor_urut_absen') ?? 0;
        $count = 0;

        foreach ($siswaAsalIds as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if ($siswa && ! $siswa->keluar_karena_id) {
                $exists = AnggotaRombel::where('kelas_id', $kelas->id)->where('siswa_id', $siswaId)->exists();
                if (! $exists) {
                    $maxAbsen++;
                    AnggotaRombel::create([
                        'kelas_id' => $kelas->id,
                        'siswa_id' => $siswaId,
                        'nomor_urut_absen' => $maxAbsen,
                    ]);
                    $count++;
                }
            }
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', "Berhasil menyalin {$count} siswa dari kelas {$kelasAsal->nama_kelas}.");
    }

    public function updateKehadiran(Request $request, Kelas $kelas): RedirectResponse
    {
        $kehadiran = $request->input('kehadiran', []);

        foreach ($kehadiran as $anggotaId => $data) {
            $anggota = AnggotaRombel::where('id', $anggotaId)->where('kelas_id', $kelas->id)->first();
            if ($anggota) {
                $anggota->update([
                    'nomor_urut_absen' => $data['nomor_urut_absen'] ?? $anggota->nomor_urut_absen,
                    'jumlah_sakit' => (int) ($data['jumlah_sakit'] ?? 0),
                    'jumlah_izin' => (int) ($data['jumlah_izin'] ?? 0),
                    'jumlah_alpa' => (int) ($data['jumlah_alpa'] ?? 0),
                    'catatan_wali_kelas' => $data['catatan_wali_kelas'] ?? null,
                    'status_kenaikan' => $data['status_kenaikan'] ?? null,
                ]);
            }
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', 'Data absensi & catatan wali kelas berhasil diperbarui.');
    }

    public function tambahMapel(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'mapel_id' => ['required', 'exists:mapel,id'],
            'guru_id' => ['nullable', 'exists:guru,id'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        $validated['kelas_id'] = $kelas->id;

        PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelas->id, 'mapel_id' => $validated['mapel_id']],
            ['guru_id' => $validated['guru_id'], 'kkm' => $validated['kkm']]
        );

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', 'Mata pelajaran berhasil ditambahkan ke kelas.');
    }

    public function updatePengampu(Request $request, Kelas $kelas, PengampuMapel $pengampu): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id' => ['nullable', 'exists:guru,id'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        if ($pengampu->kelas_id === $kelas->id) {
            $pengampu->update($validated);
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', 'Pengampu mata pelajaran berhasil diperbarui.');
    }

    public function hapusMapel(Kelas $kelas, PengampuMapel $pengampu): RedirectResponse
    {
        if ($pengampu->kelas_id === $kelas->id) {
            $pengampu->delete();
        }

        return redirect()->route('kelas.kelola', $kelas->id)
            ->with('success', 'Mata pelajaran berhasil dihapus dari kelas.');
    }
}
