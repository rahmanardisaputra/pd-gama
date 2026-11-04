<?php

namespace App\Imports;

use App\Models\AnggotaRombel;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\ReferensiDapodik;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;

    public int $updatedCount = 0;

    public array $skippedRows = [];

    protected Collection $referensi;

    protected Collection $jurusanList;

    protected ?TahunAjaran $taAktif;

    protected Collection $kelasList;

    public function __construct(
        protected ?int $defaultKelasId = null
    ) {
        $this->referensi = ReferensiDapodik::all()->groupBy('kategori');
        $this->jurusanList = KompetensiKeahlian::all();
        $this->taAktif = TahunAjaran::where('aktif', true)->first();
        $this->kelasList = $this->taAktif ? Kelas::where('tahun_ajaran_id', $this->taAktif->id)->get() : collect();
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $index => $row) {
            $rowNum = $index + 2; // Baris Excel (1-based, header di baris 1)

            // Ambil Nama Lengkap
            $nama = $row['nama_lengkap'] ?? $row['nama_lengkap_'] ?? $row['nama'] ?? null;
            if (empty($nama)) {
                continue; // Lewati baris kosong
            }

            // Ambil Jenis Kelamin
            $jk = strtoupper(trim((string) ($row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? 'L')));
            if (! in_array($jk, ['L', 'P'])) {
                $jk = str_starts_with($jk, 'P') ? 'P' : 'L';
            }

            // Ambil Tempat & Tanggal Lahir
            $tempatLahir = $row['tempat_lahir'] ?? $row['tempat_lahir_'] ?? 'Jakarta';
            $rawTanggalLahir = $row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? null;
            $tanggalLahir = $this->parseDate($rawTanggalLahir) ?? '2008-01-01';

            // Identitas Unik
            $nisn = ! empty($row['nisn']) ? trim((string) $row['nisn']) : null;
            $nik = ! empty($row['nik']) ? trim((string) $row['nik']) : null;
            $nis = ! empty($row['nis']) ? trim((string) $row['nis']) : null;

            // Resolusi Referensi Dinamis
            $agamaId = $this->findReferensiId('agama', $row['agama'] ?? null);
            $tempatTinggalId = $this->findReferensiId('tempat_tinggal', $row['tempat_tinggal'] ?? null);
            $transportasiId = $this->findReferensiId('moda_transportasi', $row['moda_transportasi'] ?? null);
            $pendidikanAyahId = $this->findReferensiId('pendidikan', $row['pendidikan_ayah'] ?? null);
            $pekerjaanAyahId = $this->findReferensiId('pekerjaan', $row['pekerjaan_ayah'] ?? null);
            $penghasilanAyahId = $this->findReferensiId('penghasilan', $row['penghasilan_ayah'] ?? null);
            $pendidikanIbuId = $this->findReferensiId('pendidikan', $row['pendidikan_ibu'] ?? null);
            $pekerjaanIbuId = $this->findReferensiId('pekerjaan', $row['pekerjaan_ibu'] ?? null);
            $penghasilanIbuId = $this->findReferensiId('penghasilan', $row['penghasilan_ibu'] ?? null);
            $jurusanId = $this->findJurusanId($row['kode_jurusan'] ?? $row['jurusan'] ?? null);

            // Payload Siswa
            $payload = [
                'nama_lengkap' => trim((string) $nama),
                'jenis_kelamin' => $jk,
                'tempat_lahir' => trim((string) $tempatLahir),
                'tanggal_lahir' => $tanggalLahir,
                'no_kk' => ! empty($row['no_kk']) ? trim((string) $row['no_kk']) : null,
                'agama_id' => $agamaId,
                'alamat_jalan' => $row['alamat_jalan'] ?? null,
                'rt' => $row['rt'] ?? null,
                'rw' => $row['rw'] ?? null,
                'nama_dusun' => $row['dusun'] ?? $row['nama_dusun'] ?? null,
                'nama_kelurahan_desa' => $row['kelurahan_desa'] ?? $row['kelurahan'] ?? $row['desa'] ?? null,
                'kecamatan' => $row['kecamatan'] ?? null,
                'kode_pos' => $row['kode_pos'] ?? null,
                'tempat_tinggal_id' => $tempatTinggalId,
                'moda_transportasi_id' => $transportasiId,
                'nama_ayah' => $row['nama_ayah'] ?? null,
                'nik_ayah' => ! empty($row['nik_ayah']) ? trim((string) $row['nik_ayah']) : null,
                'pendidikan_ayah_id' => $pendidikanAyahId,
                'pekerjaan_ayah_id' => $pekerjaanAyahId,
                'penghasilan_ayah_id' => $penghasilanAyahId,
                'nama_ibu' => $row['nama_ibu'] ?? null,
                'nik_ibu' => ! empty($row['nik_ibu']) ? trim((string) $row['nik_ibu']) : null,
                'pendidikan_ibu_id' => $pendidikanIbuId,
                'pekerjaan_ibu_id' => $pekerjaanIbuId,
                'penghasilan_ibu_id' => $penghasilanIbuId,
                'nama_wali' => $row['nama_wali'] ?? null,
                'nomor_hp' => ! empty($row['nomor_hp']) ? trim((string) $row['nomor_hp']) : null,
                'email' => ! empty($row['email']) ? trim((string) $row['email']) : null,
                'sekolah_asal' => $row['sekolah_asal'] ?? null,
                'kompetensi_keahlian_id' => $jurusanId,
                'nis' => $nis,
                'kewarganegaraan' => 'WNI',
                'tanggal_masuk_sekolah' => date('Y-m-d'),
            ];

            // Cari siswa berdasarkan NISN atau NIK atau NIS jika sudah ada untuk updateOrCreate
            $siswa = null;
            if ($nisn) {
                $siswa = Siswa::where('nisn', $nisn)->first();
            }
            if (! $siswa && $nik) {
                $siswa = Siswa::where('nik', $nik)->first();
            }
            if (! $siswa && $nis) {
                $siswa = Siswa::where('nis', $nis)->first();
            }

            if ($siswa) {
                $payload['nisn'] = $nisn ?? $siswa->nisn;
                $payload['nik'] = $nik ?? $siswa->nik;
                $siswa->update($payload);
                $this->updatedCount++;
            } else {
                $payload['nisn'] = $nisn;
                $payload['nik'] = $nik;
                $siswa = Siswa::create($payload);
                $this->importedCount++;
            }

            // Masukkan ke kelas jika ditentukan
            $targetKelasId = $this->defaultKelasId;
            $namaKelasRow = $row['nama_kelas_opsional'] ?? $row['nama_kelas'] ?? $row['kelas'] ?? null;
            if ($namaKelasRow && $this->kelasList->isNotEmpty()) {
                $kelasFound = $this->kelasList->first(fn ($k) => strcasecmp($k->nama_kelas, trim((string) $namaKelasRow)) === 0);
                if ($kelasFound) {
                    $targetKelasId = $kelasFound->id;
                }
            }

            if ($targetKelasId && $this->taAktif) {
                $maxAbsen = AnggotaRombel::where('kelas_id', $targetKelasId)->max('nomor_urut_absen') ?? 0;
                AnggotaRombel::firstOrCreate(
                    [
                        'kelas_id' => $targetKelasId,
                        'siswa_id' => $siswa->id,
                    ],
                    [
                        'nomor_urut_absen' => $maxAbsen + 1,
                        'jumlah_sakit' => 0,
                        'jumlah_izin' => 0,
                        'jumlah_alpa' => 0,
                    ]
                );
            }
        }
    }

    protected function parseDate(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Jika berupa serial integer dari Excel
        if (is_numeric($value) && $value > 1000) {
            try {
                return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Lanjut ke parser string
            }
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function findReferensiId(string $kategori, ?string $value): ?int
    {
        if (empty($value)) {
            return null;
        }

        $cleanVal = trim((string) $value);
        $items = $this->referensi->get($kategori, collect());

        // 1. Cocokkan kode persis
        $match = $items->firstWhere('kode', $cleanVal);
        if ($match) {
            return $match->id;
        }

        // 2. Cocokkan nama persis (case-insensitive)
        $match = $items->first(fn ($i) => strcasecmp($i->nama, $cleanVal) === 0);
        if ($match) {
            return $match->id;
        }

        // 3. Cocokkan sebagian nama (fuzzy / contains)
        $match = $items->first(fn ($i) => stripos($i->nama, $cleanVal) !== false || stripos($cleanVal, $i->nama) !== false);

        return $match?->id;
    }

    protected function findJurusanId(?string $value): ?int
    {
        if (empty($value)) {
            return null;
        }

        $cleanVal = trim((string) $value);
        $match = $this->jurusanList->first(fn ($j) => strcasecmp($j->kode_jurusan, $cleanVal) === 0 || strcasecmp($j->nama_jurusan, $cleanVal) === 0);

        return $match?->id;
    }
}
