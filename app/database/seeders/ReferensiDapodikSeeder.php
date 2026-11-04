<?php

namespace Database\Seeders;

use App\Models\ReferensiDapodik;
use Illuminate\Database\Seeder;

class ReferensiDapodikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 1. Agama
            'agama' => [
                ['kode' => '01', 'nama' => 'Islam'],
                ['kode' => '02', 'nama' => 'Kristen/Protestan'],
                ['kode' => '03', 'nama' => 'Katolik'],
                ['kode' => '04', 'nama' => 'Hindu'],
                ['kode' => '05', 'nama' => 'Buddha'],
                ['kode' => '06', 'nama' => 'Khonghucu'],
                ['kode' => '99', 'nama' => 'Lainnya'],
            ],

            // 2. Kebutuhan Khusus
            'kebutuhan_khusus' => [
                ['kode' => '01', 'nama' => 'Tidak'],
                ['kode' => '02', 'nama' => 'Netra (A)'],
                ['kode' => '03', 'nama' => 'Rungu (B)'],
                ['kode' => '04', 'nama' => 'Grahita Ringan (C)'],
                ['kode' => '05', 'nama' => 'Grahita Sedang (C1)'],
                ['kode' => '06', 'nama' => 'Daksa Ringan (D)'],
                ['kode' => '07', 'nama' => 'Daksa Sedang (D1)'],
                ['kode' => '08', 'nama' => 'Laras (E)'],
                ['kode' => '09', 'nama' => 'Wicara (F)'],
                ['kode' => '10', 'nama' => 'Tuna Ganda (G)'],
                ['kode' => '11', 'nama' => 'Hiperaktif (H)'],
                ['kode' => '12', 'nama' => 'Cerdas Istimewa (I)'],
                ['kode' => '13', 'nama' => 'Bakat Istimewa (J)'],
                ['kode' => '14', 'nama' => 'Kesulitan Belajar (K)'],
                ['kode' => '15', 'nama' => 'Narkoba (N)'],
                ['kode' => '16', 'nama' => 'Indigo (O)'],
                ['kode' => '17', 'nama' => 'Down Sindrom (P)'],
                ['kode' => '18', 'nama' => 'Autis (Q)'],
            ],

            // 3. Tempat Tinggal
            'tempat_tinggal' => [
                ['kode' => '01', 'nama' => 'Bersama orang tua'],
                ['kode' => '02', 'nama' => 'Wali'],
                ['kode' => '03', 'nama' => 'Kos'],
                ['kode' => '04', 'nama' => 'Asrama'],
                ['kode' => '05', 'nama' => 'Panti Asuhan'],
                ['kode' => '99', 'nama' => 'Lainnya'],
            ],

            // 4. Moda Transportasi
            'moda_transportasi' => [
                ['kode' => '01', 'nama' => 'Jalan kaki'],
                ['kode' => '02', 'nama' => 'Kendaraan pribadi'],
                ['kode' => '03', 'nama' => 'Angkutan umum / angkot / pete-pete'],
                ['kode' => '04', 'nama' => 'Jemputan sekolah'],
                ['kode' => '05', 'nama' => 'Kereta api'],
                ['kode' => '06', 'nama' => 'Ojek'],
                ['kode' => '07', 'nama' => 'Andong / bendi / sado / dokar / delman / becak'],
                ['kode' => '08', 'nama' => 'Perahu penyeberangan / rakit / getek'],
                ['kode' => '99', 'nama' => 'Lainnya'],
            ],

            // 5. Pendidikan
            'pendidikan' => [
                ['kode' => '01', 'nama' => 'Tidak sekolah'],
                ['kode' => '02', 'nama' => 'Putus SD'],
                ['kode' => '03', 'nama' => 'SD Sederajat'],
                ['kode' => '04', 'nama' => 'SMP Sederajat'],
                ['kode' => '05', 'nama' => 'SMA Sederajat'],
                ['kode' => '06', 'nama' => 'D1'],
                ['kode' => '07', 'nama' => 'D2'],
                ['kode' => '08', 'nama' => 'D3'],
                ['kode' => '09', 'nama' => 'D4/S1'],
                ['kode' => '10', 'nama' => 'S2'],
                ['kode' => '11', 'nama' => 'S3'],
            ],

            // 6. Pekerjaan
            'pekerjaan' => [
                ['kode' => '01', 'nama' => 'Tidak bekerja'],
                ['kode' => '02', 'nama' => 'Nelayan'],
                ['kode' => '03', 'nama' => 'Petani'],
                ['kode' => '04', 'nama' => 'Peternak'],
                ['kode' => '05', 'nama' => 'PNS/TNI/POLRI'],
                ['kode' => '06', 'nama' => 'Karyawan Swasta'],
                ['kode' => '07', 'nama' => 'Pedagang Kecil'],
                ['kode' => '08', 'nama' => 'Pedagang Besar'],
                ['kode' => '09', 'nama' => 'Wiraswasta'],
                ['kode' => '10', 'nama' => 'Wirausaha'],
                ['kode' => '11', 'nama' => 'Buruh'],
                ['kode' => '12', 'nama' => 'Pensiunan'],
                ['kode' => '98', 'nama' => 'Meninggal Dunia'],
                ['kode' => '99', 'nama' => 'Lain-lain'],
            ],

            // 7. Penghasilan
            'penghasilan' => [
                ['kode' => '01', 'nama' => 'Kurang dari Rp. 500.000'],
                ['kode' => '02', 'nama' => 'Rp. 500.000 - Rp. 999.999'],
                ['kode' => '03', 'nama' => 'Rp. 1.000.000 - Rp. 1.999.999'],
                ['kode' => '04', 'nama' => 'Rp. 2.000.000 - Rp. 4.999.999'],
                ['kode' => '05', 'nama' => 'Rp. 5.000.000 - Rp. 20.000.000'],
                ['kode' => '06', 'nama' => 'Lebih dari Rp. 20.000.000'],
                ['kode' => '07', 'nama' => 'Tidak Berpenghasilan'],
            ],

            // 8. Alasan Menolak PIP
            'alasan_menolak_pip' => [
                ['kode' => '01', 'nama' => 'Dilarang Pemda'],
                ['kode' => '02', 'nama' => 'Menolak'],
                ['kode' => '03', 'nama' => 'Sudah Mampu'],
            ],

            // 9. Jenis Prestasi
            'jenis_prestasi' => [
                ['kode' => '1', 'nama' => 'Sains'],
                ['kode' => '2', 'nama' => 'Seni'],
                ['kode' => '3', 'nama' => 'Olahraga'],
                ['kode' => '4', 'nama' => 'Lain-lain'],
            ],

            // 10. Tingkat Prestasi
            'tingkat_prestasi' => [
                ['kode' => '1', 'nama' => 'Sekolah'],
                ['kode' => '2', 'nama' => 'Kecamatan'],
                ['kode' => '3', 'nama' => 'Kabupaten/Kota'],
                ['kode' => '4', 'nama' => 'Provinsi'],
                ['kode' => '5', 'nama' => 'Nasional'],
                ['kode' => '6', 'nama' => 'Internasional'],
            ],

            // 11. Jenis Beasiswa
            'jenis_beasiswa' => [
                ['kode' => '01', 'nama' => 'Anak Berprestasi'],
                ['kode' => '02', 'nama' => 'Anak Miskin'],
                ['kode' => '03', 'nama' => 'Pendidikan'],
                ['kode' => '04', 'nama' => 'Unggulan'],
                ['kode' => '99', 'nama' => 'Lain-lain'],
            ],

            // 12. Jenis Pendaftaran
            'jenis_pendaftaran' => [
                ['kode' => '01', 'nama' => 'Siswa Baru'],
                ['kode' => '02', 'nama' => 'Pindahan'],
                ['kode' => '03', 'nama' => 'Kembali Bersekolah'],
            ],

            // 13. Alasan Keluar
            'alasan_keluar' => [
                ['kode' => '00', 'nama' => 'Lulus'],
                ['kode' => '01', 'nama' => 'Mutasi'],
                ['kode' => '02', 'nama' => 'Dikeluarkan'],
                ['kode' => '03', 'nama' => 'Mengundurkan Diri'],
                ['kode' => '04', 'nama' => 'Putus Sekolah'],
                ['kode' => '05', 'nama' => 'Wafat'],
                ['kode' => '06', 'nama' => 'Hilang'],
            ],
        ];

        foreach ($data as $kategori => $items) {
            $urutan = 1;
            foreach ($items as $item) {
                ReferensiDapodik::updateOrCreate(
                    [
                        'kategori' => $kategori,
                        'kode' => $item['kode'],
                    ],
                    [
                        'nama' => $item['nama'],
                        'urutan' => $urutan++,
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}
