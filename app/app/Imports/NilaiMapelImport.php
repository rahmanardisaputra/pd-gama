<?php

namespace App\Imports;

use App\Models\AnggotaRombel;
use App\Models\NilaiSiswa;
use App\Models\PengampuMapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiMapelImport implements ToCollection, WithHeadingRow
{
    public function __construct(
        protected PengampuMapel $pengampu
    ) {}

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            $rombelId = $row['id_rombel_jangan_ubah'] ?? $row['id_rombel'] ?? null;
            $nilaiAkhir = $row['nilai_akhir_0_100'] ?? $row['nilai_akhir'] ?? null;
            $predikat = $row['predikat_abcd'] ?? $row['predikat'] ?? null;
            $deskripsi = $row['deskripsi_capaian'] ?? null;

            if (! $rombelId) {
                $nis = $row['nis'] ?? null;
                $nisn = $row['nisn'] ?? null;

                $anggota = AnggotaRombel::where('kelas_id', $this->pengampu->kelas_id)
                    ->whereHas('siswa', function ($q) use ($nis, $nisn) {
                        if ($nis) {
                            $q->where('nis', $nis);
                        } elseif ($nisn) {
                            $q->where('nisn', $nisn);
                        }
                    })->first();

                if ($anggota) {
                    $rombelId = $anggota->id;
                }
            }

            if ($rombelId && is_numeric($nilaiAkhir)) {
                $nilaiAngka = floatval($nilaiAkhir);

                if (empty($predikat)) {
                    if ($nilaiAngka >= 90) {
                        $predikat = 'A';
                    } elseif ($nilaiAngka >= 80) {
                        $predikat = 'B';
                    } elseif ($nilaiAngka >= 70) {
                        $predikat = 'C';
                    } else {
                        $predikat = 'D';
                    }
                }

                NilaiSiswa::updateOrCreate(
                    [
                        'anggota_rombel_id' => $rombelId,
                        'pengampu_mapel_id' => $this->pengampu->id,
                    ],
                    [
                        'nilai_akhir' => $nilaiAngka,
                        'predikat' => strtoupper(trim((string) $predikat)),
                        'deskripsi_capaian' => $deskripsi,
                    ]
                );
            }
        }
    }
}
