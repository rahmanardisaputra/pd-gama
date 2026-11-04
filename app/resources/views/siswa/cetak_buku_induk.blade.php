@extends('layouts.cetak')

@section('title', 'Lembar Buku Induk - ' . $siswa->nama_lengkap)

@push('styles')
<style>
    .buku-induk-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5pt;
        line-height: 1.4;
    }
    .buku-induk-table td {
        padding: 4px 6px;
        vertical-align: top;
    }
    .header-buku-induk {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    .sub-heading {
        font-weight: bold;
        font-size: 10pt;
        background-color: #f8fafc;
        border-top: 1px solid #cbd5e1;
        border-bottom: 1px solid #cbd5e1;
        padding: 4px 8px;
        margin: 12px 0 6px 0;
        text-transform: uppercase;
    }
    .pas-foto-box {
        width: 30mm;
        height: 40mm;
        border: 1px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8pt;
        color: #64748b;
        text-align: center;
        margin: 0 auto;
    }
    .table-nilai {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5pt;
        margin-top: 8px;
    }
    .table-nilai th, .table-nilai td {
        border: 1px solid #000;
        padding: 4px 6px;
    }
</style>
@endpush

@section('content')
<!-- HALAMAN 1: LEMBAR BIODATA PESERTA DIDIK -->
<div class="print-container">
    <div class="header-buku-induk">
        <h4 style="margin: 0; font-weight: 800; font-size: 14pt; letter-spacing: 0.5px;">LEMBAR BUKU INDUK PESERTA DIDIK</h4>
        <div style="font-size: 9pt; margin-top: 3px;">NOMOR INDUK SISWA (NIS): <strong>{{ $siswa->nis ?? '-' }}</strong> &nbsp;|&nbsp; NISN: <strong>{{ $siswa->nisn ?? '-' }}</strong></div>
    </div>

    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <!-- Data Kiri (Biodata) -->
        <div style="flex: 1;">
            <div class="sub-heading" style="margin-top: 0;">A. KETERANGAN TENTANG DIRI PESERTA DIDIK</div>
            <table class="buku-induk-table">
                <tr><td style="width: 25px;">1.</td><td style="width: 190px;">Nama Lengkap</td><td>: <strong>{{ strtoupper($siswa->nama_lengkap) }}</strong></td></tr>
                <tr><td>2.</td><td>Jenis Kelamin</td><td>: {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><td>3.</td><td>Tempat & Tanggal Lahir</td><td>: {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir?->translatedFormat('d F Y') }}</td></tr>
                <tr><td>4.</td><td>Agama</td><td>: {{ $siswa->agama->nama ?? '-' }}</td></tr>
                <tr><td>5.</td><td>Kewarganegaraan</td><td>: {{ $siswa->kewarganegaraan }}</td></tr>
                <tr><td>6.</td><td>Anak ke-berapa</td><td>: {{ $siswa->anak_keberapa ?? '-' }} (dari {{ $siswa->jumlah_saudara_kandung ? $siswa->jumlah_saudara_kandung + 1 : 1 }} bersaudara)</td></tr>
                <tr><td>7.</td><td>NIK / No. KK</td><td>: {{ $siswa->nik ?? '-' }} / {{ $siswa->no_kk ?? '-' }}</td></tr>
                <tr><td>8.</td><td>Kebutuhan Khusus</td><td>: {{ $siswa->berkebutuhanKhusus->nama ?? 'Tidak Ada' }}</td></tr>
            </table>

            <div class="sub-heading">B. KETERANGAN TEMPAT TINGGAL</div>
            <table class="buku-induk-table">
                <tr><td style="width: 25px;">9.</td><td style="width: 190px;">Alamat Tempat Tinggal</td><td>: {{ $siswa->alamat_jalan ?? '-' }}</td></tr>
                <tr><td>10.</td><td>RT / RW / Dusun</td><td>: RT. {{ $siswa->rt ?? '-' }} / RW. {{ $siswa->rw ?? '-' }} - {{ $siswa->nama_dusun ?? '-' }}</td></tr>
                <tr><td>11.</td><td>Kelurahan / Desa</td><td>: {{ $siswa->nama_kelurahan_desa ?? '-' }}</td></tr>
                <tr><td>12.</td><td>Kecamatan & Kode Pos</td><td>: {{ $siswa->kecamatan ?? '-' }} ({{ $siswa->kode_pos ?? '-' }})</td></tr>
                <tr><td>13.</td><td>Tinggal Bersama</td><td>: {{ $siswa->tempatTinggal->nama ?? '-' }}</td></tr>
                <tr><td>14.</td><td>Transportasi ke Sekolah</td><td>: {{ $siswa->modaTransportasi->nama ?? '-' }}</td></tr>
            </table>
        </div>

        <!-- Pas Foto 3x4 Box -->
        <div style="width: 35mm; text-align: center;">
            <div class="pas-foto-box">
                Pas Foto<br>3 x 4 cm
            </div>
            <small style="font-size: 7pt; color: #64748b; display: block; margin-top: 5px;">Cap Jari Tiga Jari Kiri</small>
        </div>
    </div>

    <div class="sub-heading">C. KETERANGAN ORANG TUA / WALI</div>
    <table class="buku-induk-table">
        <tr>
            <td style="width: 25px;">15.</td>
            <td style="width: 190px;">Nama Ayah Kandung</td>
            <td>: {{ $siswa->nama_ayah ?? '-' }} (NIK: {{ $siswa->nik_ayah ?? '-' }})</td>
        </tr>
        <tr>
            <td>16.</td>
            <td>Pendidikan & Pekerjaan Ayah</td>
            <td>: {{ $siswa->pendidikanAyah->nama ?? '-' }} / {{ $siswa->pekerjaanAyah->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>17.</td>
            <td>Penghasilan Ayah</td>
            <td>: {{ $siswa->penghasilanAyah->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>18.</td>
            <td>Nama Ibu Kandung</td>
            <td>: {{ $siswa->nama_ibu ?? '-' }} (NIK: {{ $siswa->nik_ibu ?? '-' }})</td>
        </tr>
        <tr>
            <td>19.</td>
            <td>Pendidikan & Pekerjaan Ibu</td>
            <td>: {{ $siswa->pendidikanIbu->nama ?? '-' }} / {{ $siswa->pekerjaanIbu->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>20.</td>
            <td>Penghasilan Ibu</td>
            <td>: {{ $siswa->penghasilanIbu->nama ?? '-' }}</td>
        </tr>
        @if($siswa->nama_wali)
            <tr>
                <td>21.</td>
                <td>Nama Wali & Hubungan</td>
                <td>: {{ $siswa->nama_wali }} (Pekerjaan: {{ $siswa->pekerjaanWali->nama ?? '-' }})</td>
            </tr>
        @endif
    </table>

    <div class="sub-heading">D. KETERANGAN MASUK & KELUAR</div>
    <table class="buku-induk-table">
        <tr>
            <td style="width: 25px;">22.</td>
            <td style="width: 190px;">Diterima di Sekolah Ini</td>
            <td>: Tanggal {{ $siswa->tanggal_masuk_sekolah ? $siswa->tanggal_masuk_sekolah->translatedFormat('d F Y') : '-' }} (Jurusan: {{ $siswa->kompetensiKeahlian->nama_jurusan ?? 'Umum' }})</td>
        </tr>
        <tr>
            <td>23.</td>
            <td>Sekolah Asal</td>
            <td>: {{ $siswa->sekolah_asal ?? '-' }}</td>
        </tr>
        @if($siswa->keluar_karena_id)
            <tr>
                <td>24.</td>
                <td>Status Keluar</td>
                <td>: Tanggal {{ $siswa->tanggal_keluar ? $siswa->tanggal_keluar->translatedFormat('d F Y') : '-' }} Karena <strong>{{ $siswa->keluarKarena->nama ?? '-' }}</strong> ({{ $siswa->alasan_keluar }})</td>
            </tr>
        @endif
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: flex-end; font-size: 9pt;">
        <div style="text-align: center; width: 250px;">
            <div>{{ $taAktif->tempat_cetak_rapor ?? 'Jakarta' }}, {{ date('d F Y') }}</div>
            <div>Kepala Sekolah,</div>
            <div style="height: 60px;"></div>
            <div style="font-weight: bold; text-decoration: underline;">{{ $taAktif->nama_kepala_sekolah ?? '.........................................' }}</div>
            <div>NIP. {{ $taAktif->nip_kepala_sekolah ?? '.........................................' }}</div>
        </div>
    </div>
</div>

<!-- HALAMAN 2: REKAPITULASI NILAI LEGER LINTAS SEMESTER -->
<div class="page-break"></div>

<div class="print-container">
    <div class="header-buku-induk">
        <h4 style="margin: 0; font-weight: 800; font-size: 13pt;">REKAPITULASI NILAI LEGER PESERTA DIDIK</h4>
        <div style="font-size: 9pt; margin-top: 3px;">Nama: <strong>{{ strtoupper($siswa->nama_lengkap) }}</strong> &nbsp;|&nbsp; NIS: <strong>{{ $siswa->nis ?? '-' }}</strong> &nbsp;|&nbsp; NISN: <strong>{{ $siswa->nisn ?? '-' }}</strong></div>
    </div>

    @forelse($siswa->anggotaRombel as $rombel)
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 9pt; font-weight: bold; background: #e2e8f0; padding: 4px 8px; border: 1px solid #94a3b8;">
                <span>KELAS: {{ $rombel->kelas->nama_kelas }} (TA {{ $rombel->kelas->tahunAjaran->nama_tahun }} - {{ $rombel->kelas->tahunAjaran->semester }})</span>
                <span>Wali Kelas: {{ $rombel->kelas->waliKelas->nama_guru ?? '-' }}</span>
            </div>

            <table class="table-nilai">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="width: 35px; text-align: center;">No</th>
                        <th>Mata Pelajaran</th>
                        <th style="width: 60px; text-align: center;">KKM</th>
                        <th style="width: 70px; text-align: center;">Nilai</th>
                        <th style="width: 60px; text-align: center;">Predikat</th>
                        <th>Capaian Kompetensi / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilai = 0; $countNilai = 0; @endphp
                    @forelse($rombel->nilaiSiswa as $nIdx => $n)
                        @php
                            $totalNilai += (float)$n->nilai_akhir;
                            $countNilai++;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $nIdx + 1 }}</td>
                            <td>{{ $n->pengampuMapel->mapel->nama_mapel ?? '-' }}</td>
                            <td style="text-align: center; font-family: monospace;">{{ $n->pengampuMapel->kkm ?? 75 }}</td>
                            <td style="text-align: center; font-weight: bold; font-family: monospace;">{{ number_format($n->nilai_akhir, 2) }}</td>
                            <td style="text-align: center; font-family: monospace;">{{ $n->predikat ?? '-' }}</td>
                            <td style="font-size: 8pt;">{{ $n->deskripsi_capaian ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; padding: 10px;">Belum ada nilai untuk semester ini.</td></tr>
                    @endforelse
                </tbody>
                @if($countNilai > 0)
                    <tfoot>
                        <tr style="font-weight: bold; background: #f8fafc;">
                            <td colspan="3" style="text-align: right;">RATA-RATA NILAI:</td>
                            <td style="text-align: center; font-family: monospace;">{{ round($totalNilai / $countNilai, 2) }}</td>
                            <td colspan="2">
                                Ketidakhadiran: S: {{ $rombel->jumlah_sakit }}, I: {{ $rombel->jumlah_izin }}, A: {{ $rombel->jumlah_alpa }} | Status: {{ $rombel->status_kenaikan ?? '-' }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #64748b;">
            Belum ada rekam jejak akademik / nilai pada Buku Induk ini.
        </div>
    @endforelse
</div>
@endsection

