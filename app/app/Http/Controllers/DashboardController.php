<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $taAktif = TahunAjaran::where('aktif', true)->first();

        $totalSiswa = Siswa::whereNull('keluar_karena_id')->count();
        $siswaLaki = Siswa::whereNull('keluar_karena_id')->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = Siswa::whereNull('keluar_karena_id')->where('jenis_kelamin', 'P')->count();
        $totalSiswaKeluar = Siswa::whereNotNull('keluar_karena_id')->count();

        $totalGuru = Guru::where('aktif', true)->count();
        $totalMapel = Mapel::count();
        $totalJurusan = KompetensiKeahlian::count();

        $kelasList = $taAktif ? Kelas::where('tahun_ajaran_id', $taAktif->id)
            ->with(['waliKelas', 'kompetensiKeahlian'])
            ->withCount('anggotaRombel')
            ->get() : collect();

        $totalKelas = $kelasList->count();

        $siswaPerJurusan = KompetensiKeahlian::withCount(['siswa' => function ($q) {
            $q->whereNull('keluar_karena_id');
        }])->get();

        return view('dashboard', compact(
            'taAktif',
            'totalSiswa',
            'siswaLaki',
            'siswaPerempuan',
            'totalSiswaKeluar',
            'totalGuru',
            'totalMapel',
            'totalJurusan',
            'kelasList',
            'totalKelas',
            'siswaPerJurusan'
        ));
    }
}
