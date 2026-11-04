<?php

namespace App\Exports;

use App\Models\NilaiSiswa;
use App\Models\PengampuMapel;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateNilaiMapelExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    public function __construct(
        protected PengampuMapel $pengampu
    ) {}

    public function collection(): Enumerable
    {
        $anggotaList = $this->pengampu->kelas->anggotaRombel()->with('siswa')->get();

        return $anggotaList->map(function ($anggota) {
            $nilai = NilaiSiswa::where('anggota_rombel_id', $anggota->id)
                ->where('pengampu_mapel_id', $this->pengampu->id)
                ->first();

            return [
                'id_rombel' => $anggota->id,
                'nis' => $anggota->siswa->nis ?? '-',
                'nisn' => $anggota->siswa->nisn ?? '-',
                'nama_siswa' => $anggota->siswa->nama_lengkap,
                'nilai_akhir' => $nilai ? $nilai->nilai_akhir : '',
                'predikat' => $nilai ? $nilai->predikat : '',
                'deskripsi_capaian' => $nilai ? $nilai->deskripsi_capaian : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID_ROMBEL (Jangan Ubah)',
            'NIS',
            'NISN',
            'NAMA_SISWA',
            'NILAI_AKHIR (0-100)',
            'PREDIKAT (A/B/C/D)',
            'DESKRIPSI_CAPAIAN',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
