<?php

namespace App\Http\Controllers;

use App\Exports\LegerKelasExport;
use App\Exports\TemplateNilaiMapelExport;
use App\Imports\NilaiMapelImport;
use App\Models\AnggotaRombel;
use App\Models\Kelas;
use App\Models\PengampuMapel;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LegerRaporController extends Controller
{
    public function index(Request $request): View
    {
        $taAktif = TahunAjaran::where('aktif', true)->first();
        $taId = $request->get('tahun_ajaran_id', $taAktif?->id);

        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama_tahun')->orderBy('semester')->get();
        $daftarKelas = $taId ? Kelas::where('tahun_ajaran_id', $taId)->orderBy('tingkat')->orderBy('nama_kelas')->get() : collect();

        $selectedKelasId = $request->get('kelas_id', $daftarKelas->first()?->id);
        $kelas = $selectedKelasId ? Kelas::with([
            'tahunAjaran',
            'waliKelas',
            'kompetensiKeahlian',
            'pengampuMapel.mapel',
            'pengampuMapel.guru',
            'anggotaRombel.siswa',
            'anggotaRombel.nilaiSiswa',
        ])->find($selectedKelasId) : null;

        $matriksLeger = [];
        $rankMap = [];

        if ($kelas) {
            $pengampuList = $kelas->pengampuMapel;
            $anggotaList = $kelas->anggotaRombel;

            $dataTemp = [];
            foreach ($anggotaList as $anggota) {
                $nilaiMap = $anggota->nilaiSiswa->keyBy('pengampu_mapel_id');
                $total = 0;
                $count = 0;
                $nilaiDetail = [];

                foreach ($pengampuList as $p) {
                    $itemNilai = $nilaiMap->get($p->id);
                    $angka = $itemNilai?->nilai_akhir;
                    $nilaiDetail[$p->id] = [
                        'angka' => $angka,
                        'predikat' => $itemNilai?->predikat,
                        'kkm' => $p->kkm,
                        'tuntas' => $angka !== null ? ($angka >= $p->kkm) : null,
                    ];

                    if ($angka !== null) {
                        $total += (float) $angka;
                        $count++;
                    }
                }

                $rata = $count > 0 ? round($total / $count, 2) : 0;
                $dataTemp[] = [
                    'anggota' => $anggota,
                    'total' => $total,
                    'rata' => $rata,
                    'nilai_detail' => $nilaiDetail,
                ];
            }

            // Hitung ranking
            $sorted = collect($dataTemp)->sortByDesc('total')->values();
            foreach ($sorted as $idx => $s) {
                $rankMap[$s['anggota']->id] = $idx + 1;
            }

            $matriksLeger = $dataTemp;
        }

        return view('leger.index', compact(
            'daftarTahunAjaran',
            'daftarKelas',
            'taId',
            'taAktif',
            'selectedKelasId',
            'kelas',
            'matriksLeger',
            'rankMap'
        ));
    }

    public function downloadTemplate(PengampuMapel $pengampu): BinaryFileResponse
    {
        $pengampu->load(['kelas', 'mapel']);
        $filename = "Format_Nilai_{$pengampu->kelas->nama_kelas}_{$pengampu->mapel->kode_mapel}.xlsx";

        return Excel::download(new TemplateNilaiMapelExport($pengampu), $filename);
    }

    public function importNilai(Request $request, PengampuMapel $pengampu): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        try {
            Excel::import(new NilaiMapelImport($pengampu), $request->file('file_excel'));

            return redirect()->route('leger.index', [
                'tahun_ajaran_id' => $pengampu->kelas->tahun_ajaran_id,
                'kelas_id' => $pengampu->kelas_id,
            ])->with('success', "Nilai mata pelajaran {$pengampu->mapel->nama_mapel} berhasil diimport.");
        } catch (\Exception $e) {
            return redirect()->route('leger.index', [
                'tahun_ajaran_id' => $pengampu->kelas->tahun_ajaran_id,
                'kelas_id' => $pengampu->kelas_id,
            ])->with('error', 'Gagal membaca file Excel. Pastikan format kolom sesuai dengan template.');
        }
    }

    public function exportLeger(Kelas $kelas): BinaryFileResponse
    {
        $kelas->load('tahunAjaran');
        $namaTa = str_replace('/', '-', $kelas->tahunAjaran->nama_tahun);
        $filename = "Leger_Nilai_{$kelas->nama_kelas}_{$namaTa}_{$kelas->tahunAjaran->semester}.xlsx";

        return Excel::download(new LegerKelasExport($kelas), $filename);
    }

    public function cetakLeger(Kelas $kelas): View
    {
        $kelas->load([
            'tahunAjaran',
            'waliKelas',
            'kompetensiKeahlian',
            'pengampuMapel.mapel',
            'pengampuMapel.guru',
            'anggotaRombel.siswa',
            'anggotaRombel.nilaiSiswa',
        ]);

        $pengampuList = $kelas->pengampuMapel;
        $anggotaList = $kelas->anggotaRombel;

        $dataTemp = [];
        foreach ($anggotaList as $anggota) {
            $nilaiMap = $anggota->nilaiSiswa->keyBy('pengampu_mapel_id');
            $total = 0;
            $count = 0;
            $nilaiDetail = [];

            foreach ($pengampuList as $p) {
                $val = $nilaiMap->get($p->id)?->nilai_akhir;
                $nilaiDetail[$p->id] = $val;
                if ($val !== null) {
                    $total += (float) $val;
                    $count++;
                }
            }

            $rata = $count > 0 ? round($total / $count, 2) : 0;
            $dataTemp[] = [
                'anggota' => $anggota,
                'total' => $total,
                'rata' => $rata,
                'nilai_detail' => $nilaiDetail,
            ];
        }

        $sorted = collect($dataTemp)->sortByDesc('total')->values();
        $rankMap = [];
        foreach ($sorted as $idx => $s) {
            $rankMap[$s['anggota']->id] = $idx + 1;
        }

        return view('leger.cetak_leger', compact('kelas', 'dataTemp', 'rankMap', 'pengampuList'));
    }

    public function cetakRapor(Kelas $kelas, ?AnggotaRombel $anggota = null): View
    {
        $kelas->load([
            'tahunAjaran',
            'waliKelas',
            'kompetensiKeahlian',
            'pengampuMapel.mapel',
            'pengampuMapel.guru',
        ]);

        if ($anggota) {
            $anggotaList = collect([$anggota->load(['siswa', 'nilaiSiswa.pengampuMapel.mapel'])]);
        } else {
            $anggotaList = $kelas->anggotaRombel()->with(['siswa', 'nilaiSiswa.pengampuMapel.mapel'])->get();
        }

        return view('leger.cetak_rapor', compact('kelas', 'anggotaList'));
    }
}
