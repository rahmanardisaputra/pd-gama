<?php

namespace App\Exports;

use App\Models\Kelas;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LegerKelasExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    public function __construct(
        protected Kelas $kelas
    ) {}

    public function collection(): Enumerable
    {
        $pengampuList = $this->kelas->pengampuMapel()->with('mapel')->get();
        $anggotaList = $this->kelas->anggotaRombel()->with(['siswa', 'nilaiSiswa'])->get();

        // Hitung total nilai untuk perankingan
        $dataList = $anggotaList->map(function ($anggota) use ($pengampuList) {
            $nilaiMap = $anggota->nilaiSiswa->keyBy('pengampu_mapel_id');
            $totalNilai = 0;
            $countNilai = 0;
            $nilaiPerMapel = [];

            foreach ($pengampuList as $p) {
                $val = $nilaiMap->get($p->id)?->nilai_akhir;
                $nilaiPerMapel[$p->id] = $val !== null ? (float) $val : '-';
                if ($val !== null) {
                    $totalNilai += (float) $val;
                    $countNilai++;
                }
            }

            $rataRata = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : 0;

            return [
                'anggota' => $anggota,
                'total_nilai' => $totalNilai,
                'rata_rata' => $rataRata,
                'nilai_per_mapel' => $nilaiPerMapel,
            ];
        });

        // Hitung ranking
        $sorted = $dataList->sortByDesc('total_nilai')->values();
        $rankMap = [];
        foreach ($sorted as $idx => $item) {
            $rankMap[$item['anggota']->id] = $idx + 1;
        }

        $no = 1;

        return $dataList->map(function ($item) use (&$no, $pengampuList, $rankMap) {
            $anggota = $item['anggota'];
            $row = [
                'no' => $no++,
                'nis' => $anggota->siswa->nis ?? '-',
                'nisn' => $anggota->siswa->nisn ?? '-',
                'nama_siswa' => $anggota->siswa->nama_lengkap,
            ];

            foreach ($pengampuList as $p) {
                $row['mapel_'.$p->id] = $item['nilai_per_mapel'][$p->id];
            }

            $row['total_nilai'] = $item['total_nilai'];
            $row['rata_rata'] = $item['rata_rata'];
            $row['peringkat'] = $rankMap[$anggota->id] ?? '-';
            $row['sakit'] = $anggota->jumlah_sakit;
            $row['izin'] = $anggota->jumlah_izin;
            $row['alpa'] = $anggota->jumlah_alpa;
            $row['catatan'] = $anggota->catatan_wali_kelas ?? '-';

            return $row;
        });
    }

    public function headings(): array
    {
        $pengampuList = $this->kelas->pengampuMapel()->with('mapel')->get();

        $headings = [
            'NO',
            'NIS',
            'NISN',
            'NAMA SISWA',
        ];

        foreach ($pengampuList as $p) {
            $headings[] = $p->mapel->kode_mapel.' (KKM: '.$p->kkm.')';
        }

        $headings[] = 'JUMLAH NILAI';
        $headings[] = 'RATA-RATA';
        $headings[] = 'PERINGKAT';
        $headings[] = 'SAKIT';
        $headings[] = 'IZIN';
        $headings[] = 'ALPA';
        $headings[] = 'CATATAN WALI KELAS';

        return $headings;
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
