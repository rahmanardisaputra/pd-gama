<?php

namespace Database\Seeders;

use App\Models\AnggotaRombel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\Mapel;
use App\Models\NilaiSiswa;
use App\Models\PengampuMapel;
use App\Models\PrestasiSiswa;
use App\Models\ReferensiDapodik;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Default
        User::updateOrCreate(
            ['email' => 'admin@sekolah.sch.id'],
            [
                'name' => 'Administrator Sekolah',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Seeder Referensi Dapodik
        $this->call(ReferensiDapodikSeeder::class);

        // 3. Kompetensi Keahlian
        $rpl = KompetensiKeahlian::updateOrCreate(
            ['kode_jurusan' => 'RPL'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak']
        );
        $tkj = KompetensiKeahlian::updateOrCreate(
            ['kode_jurusan' => 'TKJ'],
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan']
        );
        $dkv = KompetensiKeahlian::updateOrCreate(
            ['kode_jurusan' => 'DKV'],
            ['nama_jurusan' => 'Desain Komunikasi Visual']
        );

        // 4. Tahun Ajaran
        TahunAjaran::updateOrCreate(
            ['nama_tahun' => '2025/2026', 'semester' => 'Genap'],
            [
                'aktif' => false,
                'nama_kepala_sekolah' => 'Drs. H. Mulyono, M.Pd.',
                'nip_kepala_sekolah' => '197001011995011001',
                'tempat_cetak_rapor' => 'Jakarta',
                'tanggal_cetak_rapor' => '2026-06-20',
            ]
        );

        $taAktif = TahunAjaran::updateOrCreate(
            ['nama_tahun' => '2026/2027', 'semester' => 'Ganjil'],
            [
                'aktif' => true,
                'nama_kepala_sekolah' => 'Drs. H. Mulyono, M.Pd.',
                'nip_kepala_sekolah' => '197001011995011001',
                'tempat_cetak_rapor' => 'Jakarta',
                'tanggal_cetak_rapor' => '2026-12-19',
            ]
        );

        // 5. Data Guru
        $guru1 = Guru::updateOrCreate(
            ['nip' => '198002152005011002'],
            ['nama_guru' => 'Budi Santoso, S.Kom.', 'jenis_kelamin' => 'L', 'nomor_hp' => '081234567891', 'aktif' => true]
        );
        $guru2 = Guru::updateOrCreate(
            ['nip' => '198504122008022003'],
            ['nama_guru' => 'Siti Nurhaliza, S.Pd.', 'jenis_kelamin' => 'P', 'nomor_hp' => '081234567892', 'aktif' => true]
        );
        $guru3 = Guru::updateOrCreate(
            ['nip' => '197809202003011004'],
            ['nama_guru' => 'Drs. H. Ahmad Fauzi, M.Pd.', 'jenis_kelamin' => 'L', 'nomor_hp' => '081234567893', 'aktif' => true]
        );
        $guru4 = Guru::updateOrCreate(
            ['nip' => '199011112015012005'],
            ['nama_guru' => 'Rina Kartika, M.Kom.', 'jenis_kelamin' => 'P', 'nomor_hp' => '081234567894', 'aktif' => true]
        );
        $guru5 = Guru::updateOrCreate(
            ['nip' => '198807082012011006'],
            ['nama_guru' => 'Hendra Wijaya, S.Pd.', 'jenis_kelamin' => 'L', 'nomor_hp' => '081234567895', 'aktif' => true]
        );

        // 6. Mata Pelajaran
        $mapelPai = Mapel::updateOrCreate(
            ['kode_mapel' => 'PAI'],
            ['nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'kelompok' => 'A', 'urutan_leger' => 1]
        );
        $mapelPpkn = Mapel::updateOrCreate(
            ['kode_mapel' => 'PPKN'],
            ['nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelompok' => 'A', 'urutan_leger' => 2]
        );
        $mapelBind = Mapel::updateOrCreate(
            ['kode_mapel' => 'BIND'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kelompok' => 'A', 'urutan_leger' => 3]
        );
        $mapelMtk = Mapel::updateOrCreate(
            ['kode_mapel' => 'MTK'],
            ['nama_mapel' => 'Matematika', 'kelompok' => 'A', 'urutan_leger' => 4]
        );
        $mapelBing = Mapel::updateOrCreate(
            ['kode_mapel' => 'BING'],
            ['nama_mapel' => 'Bahasa Inggris', 'kelompok' => 'A', 'urutan_leger' => 5]
        );
        $mapelWeb = Mapel::updateOrCreate(
            ['kode_mapel' => 'PWEB'],
            ['nama_mapel' => 'Pemrograman Web dan Bergerak', 'kelompok' => 'C', 'urutan_leger' => 6]
        );
        $mapelPbo = Mapel::updateOrCreate(
            ['kode_mapel' => 'PBO'],
            ['nama_mapel' => 'Pemrograman Berorientasi Objek', 'kelompok' => 'C', 'urutan_leger' => 7]
        );

        // 7. Data Kelas
        $kelasX = Kelas::updateOrCreate(
            [
                'tahun_ajaran_id' => $taAktif->id,
                'nama_kelas' => 'X-RPL-1',
            ],
            [
                'kompetensi_keahlian_id' => $rpl->id,
                'wali_kelas_id' => $guru1->id,
                'tingkat' => 10,
            ]
        );

        $kelasXI = Kelas::updateOrCreate(
            [
                'tahun_ajaran_id' => $taAktif->id,
                'nama_kelas' => 'XI-RPL-1',
            ],
            [
                'kompetensi_keahlian_id' => $rpl->id,
                'wali_kelas_id' => $guru2->id,
                'tingkat' => 11,
            ]
        );

        // 8. Pengampu Mapel di Kelas X-RPL-1
        $pengampu1 = PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelasX->id, 'mapel_id' => $mapelPai->id],
            ['guru_id' => $guru3->id, 'kkm' => 75]
        );
        $pengampu2 = PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelasX->id, 'mapel_id' => $mapelBind->id],
            ['guru_id' => $guru2->id, 'kkm' => 75]
        );
        $pengampu3 = PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelasX->id, 'mapel_id' => $mapelMtk->id],
            ['guru_id' => $guru5->id, 'kkm' => 70]
        );
        $pengampu4 = PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelasX->id, 'mapel_id' => $mapelWeb->id],
            ['guru_id' => $guru1->id, 'kkm' => 78]
        );
        $pengampu5 = PengampuMapel::updateOrCreate(
            ['kelas_id' => $kelasX->id, 'mapel_id' => $mapelPbo->id],
            ['guru_id' => $guru4->id, 'kkm' => 78]
        );

        // Ambil referensi dapodik yang dibutuhkan
        $agamaIslam = ReferensiDapodik::where('kategori', 'agama')->where('kode', '01')->first();
        $ortu = ReferensiDapodik::where('kategori', 'tempat_tinggal')->where('kode', '01')->first();
        $motor = ReferensiDapodik::where('kategori', 'moda_transportasi')->where('kode', '02')->first();
        $sd = ReferensiDapodik::where('kategori', 'pendidikan')->where('kode', '03')->first();
        $s1 = ReferensiDapodik::where('kategori', 'pendidikan')->where('kode', '09')->first();
        $pns = ReferensiDapodik::where('kategori', 'pekerjaan')->where('kode', '05')->first();
        $swasta = ReferensiDapodik::where('kategori', 'pekerjaan')->where('kode', '06')->first();
        $gaji3 = ReferensiDapodik::where('kategori', 'penghasilan')->where('kode', '03')->first();
        $gaji4 = ReferensiDapodik::where('kategori', 'penghasilan')->where('kode', '04')->first();
        $pendaftaranBaru = ReferensiDapodik::where('kategori', 'jenis_pendaftaran')->where('kode', '01')->first();
        $prestasiSains = ReferensiDapodik::where('kategori', 'jenis_prestasi')->where('kode', '1')->first();
        $tingkatKab = ReferensiDapodik::where('kategori', 'tingkat_prestasi')->where('kode', '3')->first();

        // 9. Siswa Contoh
        $daftarSiswa = [
            [
                'nama' => 'Aditya Pratama',
                'jk' => 'L',
                'nisn' => '0081234561',
                'nik' => '3171010101080001',
                'nis' => '20261001',
                'tempat' => 'Jakarta',
                'tgl_lahir' => '2008-05-14',
                'ayah' => 'Bambang Pratama',
                'ibu' => 'Sri Wahyuni',
            ],
            [
                'nama' => 'Annisa Rahmawati',
                'jk' => 'P',
                'nisn' => '0081234562',
                'nik' => '3171010101080002',
                'nis' => '20261002',
                'tempat' => 'Bandung',
                'tgl_lahir' => '2008-08-20',
                'ayah' => 'Dedi Rahmawan',
                'ibu' => 'Dewi Sartika',
            ],
            [
                'nama' => 'Bintang Ramadhan',
                'jk' => 'L',
                'nisn' => '0081234563',
                'nik' => '3171010101080003',
                'nis' => '20261003',
                'tempat' => 'Surabaya',
                'tgl_lahir' => '2008-09-11',
                'ayah' => 'Surya Ramadhan',
                'ibu' => 'Lilis Ernawati',
            ],
            [
                'nama' => 'Cantika Putri Lestari',
                'jk' => 'P',
                'nisn' => '0081234564',
                'nik' => '3171010101080004',
                'nis' => '20261004',
                'tempat' => 'Semarang',
                'tgl_lahir' => '2008-03-02',
                'ayah' => 'Agus Lestari',
                'ibu' => 'Rini Suhartini',
            ],
            [
                'nama' => 'Dimas Arya Yudha',
                'jk' => 'L',
                'nisn' => '0081234565',
                'nik' => '3171010101080005',
                'nis' => '20261005',
                'tempat' => 'Yogyakarta',
                'tgl_lahir' => '2008-11-25',
                'ayah' => 'Yudha Pratama',
                'ibu' => 'Endang Sulastri',
            ],
        ];

        $nomorAbsen = 1;
        foreach ($daftarSiswa as $s) {
            $siswa = Siswa::updateOrCreate(
                ['nisn' => $s['nisn']],
                [
                    'tanggal_formulir' => '2026-07-10',
                    'nama_lengkap' => $s['nama'],
                    'jenis_kelamin' => $s['jk'],
                    'nik' => $s['nik'],
                    'no_kk' => '3171010101000001',
                    'tempat_lahir' => $s['tempat'],
                    'tanggal_lahir' => $s['tgl_lahir'],
                    'no_registrasi_akta_lahir' => 'AKTA-'.rand(10000, 99999),
                    'agama_id' => $agamaIslam?->id,
                    'kewarganegaraan' => 'WNI',
                    'nama_negara' => 'Indonesia',
                    'alamat_jalan' => 'Jl. Merdeka No. '.rand(1, 100),
                    'rt' => '002',
                    'rw' => '005',
                    'nama_dusun' => 'Dusun Harapan',
                    'nama_kelurahan_desa' => 'Mekarsari',
                    'kecamatan' => 'Cimanggis',
                    'kode_pos' => '16452',
                    'tempat_tinggal_id' => $ortu?->id,
                    'moda_transportasi_id' => $motor?->id,
                    'anak_keberapa' => 1,
                    'punya_kip' => false,
                    'nama_ayah' => $s['ayah'],
                    'nik_ayah' => '3171010101700001',
                    'tahun_lahir_ayah' => 1975,
                    'pendidikan_ayah_id' => $s1?->id,
                    'pekerjaan_ayah_id' => $swasta?->id,
                    'penghasilan_ayah_id' => $gaji4?->id,
                    'nama_ibu' => $s['ibu'],
                    'nik_ibu' => '3171010101780002',
                    'tahun_lahir_ibu' => 1978,
                    'pendidikan_ibu_id' => $s1?->id,
                    'pekerjaan_ibu_id' => $pns?->id,
                    'penghasilan_ibu_id' => $gaji3?->id,
                    'nomor_hp' => '08571234'.rand(1000, 9999),
                    'email' => strtolower(str_replace(' ', '', $s['nama'])).'@student.sch.id',
                    'tinggi_badan_cm' => rand(155, 175),
                    'berat_badan_kg' => rand(48, 65),
                    'lingkar_kepala_cm' => 54,
                    'kategori_jarak_sekolah' => 'kurang_1_km',
                    'jarak_sekolah_km' => 1,
                    'waktu_tempuh_menit' => 15,
                    'jumlah_saudara_kandung' => 2,
                    'kompetensi_keahlian_id' => $rpl->id,
                    'jenis_pendaftaran_id' => $pendaftaranBaru?->id,
                    'nis' => $s['nis'],
                    'tanggal_masuk_sekolah' => '2026-07-15',
                    'sekolah_asal' => 'SMP Negeri 1 Pusat',
                ]
            );

            // Prestasi contoh untuk siswa pertama
            if ($nomorAbsen === 1 && $prestasiSains && $tingkatKab) {
                PrestasiSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'nama_prestasi' => 'Juara 1 Olimpiade Sains Komputer',
                    ],
                    [
                        'jenis_prestasi_id' => $prestasiSains->id,
                        'tingkat_prestasi_id' => $tingkatKab->id,
                        'tahun_prestasi' => 2026,
                        'penyelenggara' => 'Dinas Pendidikan Kota',
                        'peringkat' => 'Juara 1',
                    ]
                );
            }

            // Tambahkan sebagai anggota rombel di X-RPL-1
            $rombel = AnggotaRombel::updateOrCreate(
                [
                    'kelas_id' => $kelasX->id,
                    'siswa_id' => $siswa->id,
                ],
                [
                    'nomor_urut_absen' => $nomorAbsen++,
                    'jumlah_sakit' => rand(0, 2),
                    'jumlah_izin' => rand(0, 1),
                    'jumlah_alpa' => 0,
                    'catatan_wali_kelas' => 'Terus pertahankan motivasi belajar dan keaktifan di kelas.',
                    'status_kenaikan' => null,
                ]
            );

            // Berikan nilai contoh
            $pengampuList = [$pengampu1, $pengampu2, $pengampu3, $pengampu4, $pengampu5];
            foreach ($pengampuList as $pengampu) {
                $nilaiAngka = rand(78, 95);
                $predikat = $nilaiAngka >= 90 ? 'A' : ($nilaiAngka >= 80 ? 'B' : 'C');
                $deskripsi = 'Menunjukkan penguasaan yang sangat baik dalam materi pembelajaran.';

                NilaiSiswa::updateOrCreate(
                    [
                        'anggota_rombel_id' => $rombel->id,
                        'pengampu_mapel_id' => $pengampu->id,
                    ],
                    [
                        'nilai_akhir' => $nilaiAngka,
                        'predikat' => $predikat,
                        'deskripsi_capaian' => $deskripsi,
                    ]
                );
            }
        }
    }
}
