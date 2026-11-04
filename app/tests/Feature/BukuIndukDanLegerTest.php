<?php

namespace Tests\Feature;

use App\Exports\LegerKelasExport;
use App\Exports\TemplateNilaiMapelExport;
use App\Exports\TemplateSiswaExport;
use App\Models\AnggotaRombel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\Mapel;
use App\Models\PengampuMapel;
use App\Models\ReferensiDapodik;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class BukuIndukDanLegerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected TahunAjaran $tahunAjaran;

    protected KompetensiKeahlian $jurusan;

    protected Guru $guru;

    protected Mapel $mapel;

    protected Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@sekolah.sch.id')->first();
        $this->tahunAjaran = TahunAjaran::where('aktif', true)->first();
        $this->jurusan = KompetensiKeahlian::first();
        $this->guru = Guru::first();
        $this->mapel = Mapel::first();
        $this->kelas = Kelas::where('tahun_ajaran_id', $this->tahunAjaran->id)->first();
    }

    public function test_halaman_login_bisa_diakses(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('BUKU INDUK SISWA');
    }

    public function test_admin_bisa_login_dengan_kredensial_benar(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'admin@sekolah.sch.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_gagal_jika_password_salah(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'admin@sekolah.sch.id',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_dashboard_bisa_diakses_setelah_login(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Dashboard');
        $response->assertSee('Siswa Aktif');
    }

    public function test_master_referensi_dapodik_bisa_dilihat_dan_ditambah(): void
    {
        $response = $this->actingAs($this->admin)->get(route('referensi.index', ['kategori' => 'pekerjaan']));
        $response->assertStatus(200);
        $response->assertSee('Pekerjaan');

        $storeResponse = $this->actingAs($this->admin)->post(route('referensi.store'), [
            'kategori' => 'pekerjaan',
            'kode' => '88',
            'nama' => 'Software Engineer / Programmer',
            'urutan' => 99,
            'aktif' => '1',
        ]);

        $storeResponse->assertRedirect(route('referensi.index', ['kategori' => 'pekerjaan']));
        $this->assertDatabaseHas('referensi_dapodik', [
            'kategori' => 'pekerjaan',
            'kode' => '88',
            'nama' => 'Software Engineer / Programmer',
        ]);

        $opsi = ReferensiDapodik::kategori('pekerjaan')->where('kode', '88')->first();
        $this->assertNotNull($opsi);
        $this->assertEquals('Software Engineer / Programmer', $opsi->nama);
    }

    public function test_crud_buku_induk_siswa(): void
    {
        $agama = ReferensiDapodik::kategori('agama')->first();
        $pekerjaan = ReferensiDapodik::kategori('pekerjaan')->first();

        $storeResponse = $this->actingAs($this->admin)->post(route('siswa.store'), [
            'nama_lengkap' => 'Muhammad Rizky Pratama',
            'jenis_kelamin' => 'L',
            'nisn' => '0098765432',
            'nik' => '3171010101090001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2009-01-15',
            'agama_id' => $agama->id,
            'kewarganegaraan' => 'WNI',
            'nama_ayah' => 'Ahmad Pratama',
            'pekerjaan_ayah_id' => $pekerjaan->id,
            'kompetensi_keahlian_id' => $this->jurusan->id,
            'nis' => '20261099',
            'tanggal_masuk_sekolah' => '2026-07-15',
        ]);

        $this->assertDatabaseHas('siswa', [
            'nisn' => '0098765432',
            'nama_lengkap' => 'Muhammad Rizky Pratama',
        ]);

        $siswa = Siswa::where('nisn', '0098765432')->first();
        $storeResponse->assertRedirect(route('siswa.show', $siswa->id));

        $detailResponse = $this->actingAs($this->admin)->get(route('siswa.show', $siswa->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Muhammad Rizky Pratama');

        $cetakDapodikResponse = $this->actingAs($this->admin)->get(route('siswa.cetak.dapodik', $siswa->id));
        $cetakDapodikResponse->assertStatus(200);
        $cetakDapodikResponse->assertSee('FORMULIR PESERTA DIDIK');

        $cetakBukuIndukResponse = $this->actingAs($this->admin)->get(route('siswa.cetak.buku-induk', $siswa->id));
        $cetakBukuIndukResponse->assertStatus(200);
        $cetakBukuIndukResponse->assertSee('LEMBAR BUKU INDUK PESERTA DIDIK');

        $updateResponse = $this->actingAs($this->admin)->put(route('siswa.update', $siswa->id), [
            'nama_lengkap' => 'Muhammad Rizky Pratama (Updated)',
            'jenis_kelamin' => 'L',
            'nisn' => '0098765432',
            'nik' => '3171010101090001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2009-01-15',
            'agama_id' => $agama->id,
            'kewarganegaraan' => 'WNI',
        ]);

        $updateResponse->assertRedirect(route('siswa.show', $siswa->id));
        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'nama_lengkap' => 'Muhammad Rizky Pratama (Updated)',
        ]);
    }

    public function test_ploting_rombel_dan_pengampu_mapel(): void
    {
        $siswaBaru = Siswa::create([
            'nama_lengkap' => 'Siswa Ploting Test',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2009-04-10',
            'kewarganegaraan' => 'WNI',
        ]);

        $response = $this->actingAs($this->admin)->post(route('kelas.siswa.tambah', $this->kelas->id), [
            'siswa_ids' => [$siswaBaru->id],
        ]);

        $response->assertRedirect(route('kelas.kelola', $this->kelas->id));
        $this->assertDatabaseHas('anggota_rombel', [
            'kelas_id' => $this->kelas->id,
            'siswa_id' => $siswaBaru->id,
        ]);

        $anggota = AnggotaRombel::where('kelas_id', $this->kelas->id)->where('siswa_id', $siswaBaru->id)->first();
        $absenResponse = $this->actingAs($this->admin)->post(route('kelas.kehadiran', $this->kelas->id), [
            'kehadiran' => [
                $anggota->id => [
                    'nomor_urut_absen' => 10,
                    'jumlah_sakit' => 2,
                    'jumlah_izin' => 1,
                    'jumlah_alpa' => 0,
                    'catatan_wali_kelas' => 'Sangat rajin dan berprestasi.',
                    'status_kenaikan' => 'Naik Kelas',
                ],
            ],
        ]);

        $absenResponse->assertRedirect(route('kelas.kelola', $this->kelas->id));
        $this->assertDatabaseHas('anggota_rombel', [
            'id' => $anggota->id,
            'jumlah_sakit' => 2,
            'status_kenaikan' => 'Naik Kelas',
        ]);
    }

    public function test_lihat_leger_kelas(): void
    {
        $legerResponse = $this->actingAs($this->admin)->get(route('leger.index', [
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kelas_id' => $this->kelas->id,
        ]));
        $legerResponse->assertStatus(200);
        $legerResponse->assertSee('LEGER NILAI:');
    }

    public function test_cetak_leger_landscape(): void
    {
        $cetakLegerResponse = $this->actingAs($this->admin)->get(route('leger.cetak-leger', $this->kelas->id));
        $cetakLegerResponse->assertStatus(200);
        $cetakLegerResponse->assertSee('LEGER NILAI HASIL BELAJAR SISWA');
    }

    public function test_cetak_rapor_kelas(): void
    {
        $cetakRaporResponse = $this->actingAs($this->admin)->get(route('leger.rapor', $this->kelas->id));
        $cetakRaporResponse->assertStatus(200);
        $cetakRaporResponse->assertSee('LAPORAN HASIL BELAJAR PESERTA DIDIK');
    }

    public function test_download_template_excel(): void
    {
        Excel::fake();

        $pengampu = PengampuMapel::where('kelas_id', $this->kelas->id)->first();
        $this->assertNotNull($pengampu);

        $response = $this->actingAs($this->admin)->get(route('leger.template', $pengampu->id));
        $response->assertStatus(200);

        Excel::assertDownloaded("Format_Nilai_{$pengampu->kelas->nama_kelas}_{$pengampu->mapel->kode_mapel}.xlsx", function (TemplateNilaiMapelExport $export) {
            return $export->collection()->isNotEmpty();
        });
    }

    public function test_export_leger_excel(): void
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('leger.export', $this->kelas->id));
        $response->assertStatus(200);

        $namaTa = str_replace('/', '-', $this->kelas->tahunAjaran->nama_tahun);
        $filename = "Leger_Nilai_{$this->kelas->nama_kelas}_{$namaTa}_{$this->kelas->tahunAjaran->semester}.xlsx";

        Excel::assertDownloaded($filename, function (LegerKelasExport $export) {
            return $export->collection()->isNotEmpty();
        });
    }

    public function test_download_template_import_siswa(): void
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('siswa.template'));
        $response->assertStatus(200);

        Excel::assertDownloaded('Format_Import_Buku_Induk_Siswa.xlsx', function (TemplateSiswaExport $export) {
            return $export->collection()->isNotEmpty();
        });
    }

    public function test_import_siswa_dari_excel(): void
    {
        $header = 'NAMA_LENGKAP*,JENIS_KELAMIN (L/P)*,NIS,NISN,NIK,NO_KK,TEMPAT_LAHIR*,TANGGAL_LAHIR (YYYY-MM-DD)*,AGAMA,ALAMAT_JALAN,RT,RW,DUSUN,KELURAHAN_DESA,KECAMATAN,KODE_POS,TEMPAT_TINGGAL,MODA_TRANSPORTASI,NAMA_AYAH,NIK_AYAH,PENDIDIKAN_AYAH,PEKERJAAN_AYAH,PENGHASILAN_AYAH,NAMA_IBU,NIK_IBU,PENDIDIKAN_IBU,PEKERJAAN_IBU,PENGHASILAN_IBU,NAMA_WALI,NOMOR_HP,EMAIL,SEKOLAH_ASAL,KODE_JURUSAN,NAMA_KELAS (Opsional)';
        $row = '"Gilang Ramadhan",L,20261055,0089998877,3276010101088899,3276010101008899,Surabaya,2008-06-18,Islam,"Jl. Melati No. 5",002,004,Dusun 1,Cilodong,Cilodong,16415,"Bersama orang tua","Kendaraan pribadi","Sutrisno",3276010101708899,"D4/S1","PNS/TNI/POLRI","Rp. 2.000.000 - Rp. 4.999.999","Siti Mariam",3276010101758899,"SMA Sederajat","Karyawan Swasta","Rp. 1.000.000 - Rp. 1.999.999",,081234567800,gilang@example.com,"SMPN 5 Depok",RPL,X-RPL-1';
        $csvContent = $header."\n".$row;

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('siswa.import'), [
            'file_excel' => $file,
        ]);

        $response->assertRedirect(route('siswa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'nama_lengkap' => 'Gilang Ramadhan',
            'nisn' => '0089998877',
            'tempat_lahir' => 'Surabaya',
        ]);

        $siswa = Siswa::where('nisn', '0089998877')->first();
        $this->assertNotNull($siswa);
        $this->assertNotNull($siswa->agama_id);
        $this->assertNotNull($siswa->pendidikan_ayah_id);
        $this->assertNotNull($siswa->pekerjaan_ayah_id);

        // Pastikan masuk otomatis ke rombel X-RPL-1
        $this->assertDatabaseHas('anggota_rombel', [
            'kelas_id' => $this->kelas->id,
            'siswa_id' => $siswa->id,
        ]);
    }
}
