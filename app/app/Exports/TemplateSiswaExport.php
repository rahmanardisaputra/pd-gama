<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateSiswaExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    public function collection(): Enumerable
    {
        // Berikan 2 contoh data baris untuk memandu pengguna
        return new Collection([
            [
                'nama_lengkap' => 'Ahmad Fajar Maulana',
                'jenis_kelamin' => 'L',
                'nis' => '20261011',
                'nisn' => '0089123451',
                'nik' => '3276010101080001',
                'no_kk' => '3276010101000001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-04-12',
                'agama' => 'Islam',
                'alamat_jalan' => 'Jl. Kenanga No. 12',
                'rt' => '003',
                'rw' => '007',
                'dusun' => 'Dusun 1',
                'kelurahan_desa' => 'Sukamaju',
                'kecamatan' => 'Cilodong',
                'kode_pos' => '16415',
                'tempat_tinggal' => 'Bersama orang tua',
                'moda_transportasi' => 'Kendaraan pribadi',
                'nama_ayah' => 'Bambang Maulana',
                'nik_ayah' => '3276010101700001',
                'pendidikan_ayah' => 'D4/S1',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'penghasilan_ayah' => 'Rp. 2.000.000 - Rp. 4.999.999',
                'nama_ibu' => 'Siti Aminah',
                'nik_ibu' => '3276010101750002',
                'pendidikan_ibu' => 'SMA Sederajat',
                'pekerjaan_ibu' => 'PNS/TNI/POLRI',
                'penghasilan_ibu' => 'Rp. 1.000.000 - Rp. 1.999.999',
                'nama_wali' => '',
                'nomor_hp' => '081298765432',
                'email' => 'ahmad.fajar@example.com',
                'sekolah_asal' => 'SMP Negeri 1 Depok',
                'kode_jurusan' => 'RPL',
                'nama_kelas' => 'X-RPL-1',
            ],
            [
                'nama_lengkap' => 'Nabila Putri Azzahra',
                'jenis_kelamin' => 'P',
                'nis' => '20261012',
                'nisn' => '0089123452',
                'nik' => '3276010101080002',
                'no_kk' => '3276010101000002',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2008-07-25',
                'agama' => 'Islam',
                'alamat_jalan' => 'Jl. Mawar Indah Blok B2',
                'rt' => '001',
                'rw' => '002',
                'dusun' => 'Dusun 2',
                'kelurahan_desa' => 'Mekarjaya',
                'kecamatan' => 'Sukmajaya',
                'kode_pos' => '16411',
                'tempat_tinggal' => 'Bersama orang tua',
                'moda_transportasi' => 'Jalan kaki',
                'nama_ayah' => 'Rahmat Hidayat',
                'nik_ayah' => '3276010101680003',
                'pendidikan_ayah' => 'SMA Sederajat',
                'pekerjaan_ayah' => 'Wiraswasta',
                'penghasilan_ayah' => 'Rp. 2.000.000 - Rp. 4.999.999',
                'nama_ibu' => 'Nurhalimah',
                'nik_ibu' => '3276010101720004',
                'pendidikan_ibu' => 'D3',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga / Tidak Bekerja',
                'penghasilan_ibu' => 'Tidak Berpenghasilan',
                'nama_wali' => '',
                'nomor_hp' => '085712345678',
                'email' => 'nabila.putri@example.com',
                'sekolah_asal' => 'SMP Negeri 2 Depok',
                'kode_jurusan' => 'RPL',
                'nama_kelas' => 'X-RPL-1',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'NAMA_LENGKAP*',
            'JENIS_KELAMIN (L/P)*',
            'NIS',
            'NISN',
            'NIK',
            'NO_KK',
            'TEMPAT_LAHIR*',
            'TANGGAL_LAHIR (YYYY-MM-DD)*',
            'AGAMA',
            'ALAMAT_JALAN',
            'RT',
            'RW',
            'DUSUN',
            'KELURAHAN_DESA',
            'KECAMATAN',
            'KODE_POS',
            'TEMPAT_TINGGAL',
            'MODA_TRANSPORTASI',
            'NAMA_AYAH',
            'NIK_AYAH',
            'PENDIDIKAN_AYAH',
            'PEKERJAAN_AYAH',
            'PENGHASILAN_AYAH',
            'NAMA_IBU',
            'NIK_IBU',
            'PENDIDIKAN_IBU',
            'PEKERJAAN_IBU',
            'PENGHASILAN_IBU',
            'NAMA_WALI',
            'NOMOR_HP',
            'EMAIL',
            'SEKOLAH_ASAL',
            'KODE_JURUSAN',
            'NAMA_KELAS (Opsional)',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1D4ED8'],
                ],
            ],
        ];
    }
}
