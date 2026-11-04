@extends('layouts.cetak')

@section('title', 'Laporan Hasil Belajar (Rapor) - ' . $kelas->nama_kelas)

@push('styles')
<style>
    .rapor-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
        margin-top: 10px;
    }
    .rapor-table th, .rapor-table td {
        border: 1px solid #000;
        padding: 5px 7px;
        vertical-align: top;
    }
    .info-table {
        width: 100%;
        font-size: 9pt;
        line-height: 1.5;
        margin-bottom: 12px;
    }
    .info-table td {
        padding: 2px 4px;
        vertical-align: top;
    }
    .header-rapor {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }
</style>
@endpush

@section('content')
@foreach($anggotaList as $idx => $anggota)
    <div class="print-container">
        <!-- Header Rapor -->
        <div class="header-rapor">
            <h5 style="margin: 0; font-weight: 800; font-size: 13pt;">LAPORAN HASIL BELAJAR PESERTA DIDIK</h5>
            <div style="font-size: 9.5pt; margin-top: 2px;">SEKOLAH MENENGAH KEJURUAN / SMA</div>
        </div>

        <!-- Identitas Siswa & Rapor -->
        <table class="info-table">
            <tr>
                <td style="width: 120px; font-weight: bold;">Nama Siswa</td>
                <td style="width: 250px;">: <strong>{{ strtoupper($anggota->siswa->nama_lengkap) }}</strong></td>
                <td style="width: 120px; font-weight: bold;">Kelas / Rombel</td>
                <td>: <strong>{{ $kelas->nama_kelas }}</strong></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">NIS / NISN</td>
                <td>: {{ $anggota->siswa->nis ?? '-' }} / {{ $anggota->siswa->nisn ?? '-' }}</td>
                <td style="font-weight: bold;">Semester</td>
                <td>: {{ $kelas->tahunAjaran->semester }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Kompetensi Keahlian</td>
                <td>: {{ $kelas->kompetensiKeahlian->nama_jurusan ?? 'Umum' }}</td>
                <td style="font-weight: bold;">Tahun Ajaran</td>
                <td>: {{ $kelas->tahunAjaran->nama_tahun }}</td>
            </tr>
        </table>

        <!-- Tabel Capaian Nilai Akademik -->
        <div style="font-weight: bold; font-size: 9.5pt; margin-top: 8px;">A. NILAI AKADEMIK & CAPAIAN KOMPETENSI</div>
        <table class="rapor-table">
            <thead style="background: #f1f5f9; text-align: center;">
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 220px; text-align: left;">Mata Pelajaran</th>
                    <th style="width: 50px;">KKM</th>
                    <th style="width: 65px;">Nilai Akhir</th>
                    <th style="width: 55px;">Predikat</th>
                    <th style="text-align: left;">Capaian Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $nilaiMap = $anggota->nilaiSiswa->keyBy('pengampu_mapel_id');
                    $total = 0;
                    $count = 0;
                @endphp
                @forelse($kelas->pengampuMapel as $pIdx => $pengampu)
                    @php
                        $n = $nilaiMap->get($pengampu->id);
                        $nilaiAngka = $n?->nilai_akhir;
                        if($nilaiAngka !== null) {
                            $total += (float)$nilaiAngka;
                            $count++;
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $pIdx + 1 }}</td>
                        <td>
                            <strong>{{ $pengampu->mapel->nama_mapel }}</strong>
                            <small style="display: block; font-size: 7.5pt; color: #555;">Guru: {{ $pengampu->guru->nama_guru ?? '-' }}</small>
                        </td>
                        <td style="text-align: center; font-family: monospace;">{{ $pengampu->kkm }}</td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold; {{ $nilaiAngka !== null && $nilaiAngka < $pengampu->kkm ? 'color: red;' : '' }}">
                            {{ $nilaiAngka !== null ? number_format($nilaiAngka, 0) : '-' }}
                        </td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold;">{{ $n?->predikat ?? '-' }}</td>
                        <td style="font-size: 8pt;">{{ $n?->deskripsi_capaian ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; padding: 15px;">Belum ada data mata pelajaran pada kelas ini.</td></tr>
                @endforelse
            </tbody>
            @if($count > 0)
                <tfoot>
                    <tr style="background: #f8fafc; font-weight: bold;">
                        <td colspan="3" style="text-align: right;">JUMLAH NILAI / RATA-RATA:</td>
                        <td style="text-align: center; font-family: monospace;">{{ number_format($total, 0) }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ round($total / $count, 1) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Tabel Ketidakhadiran & Catatan Wali Kelas -->
        <div style="display: flex; gap: 15px; margin-top: 15px;">
            <div style="flex: 1;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">B. KETIDAKHADIRAN</div>
                <table class="rapor-table" style="margin-top: 0;">
                    <tr><td style="width: 140px;">Sakit</td><td style="width: 60px; text-align: center;">{{ $anggota->jumlah_sakit }} hari</td></tr>
                    <tr><td>Izin</td><td style="text-align: center;">{{ $anggota->jumlah_izin }} hari</td></tr>
                    <tr><td>Tanpa Keterangan</td><td style="text-align: center;">{{ $anggota->jumlah_alpa }} hari</td></tr>
                </table>
            </div>

            <div style="flex: 2;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">C. CATATAN WALI KELAS</div>
                <div style="border: 1px solid #000; padding: 8px 10px; min-height: 85px; font-size: 8.5pt;">
                    {{ $anggota->catatan_wali_kelas ?? 'Tingkatkan terus prestasi belajar dan kedisiplinan di sekolah.' }}
                    @if($anggota->status_kenaikan)
                        <div style="margin-top: 8px; font-weight: bold; border-top: 1px dashed #999; padding-top: 4px;">
                            Keputusan: Dinyatakan <u>{{ $anggota->status_kenaikan }}</u>.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 8.5pt;">
            <div style="text-align: center; width: 190px;">
                <div>Mengetahui,</div>
                <div>Orang Tua / Wali Siswa</div>
                <div style="height: 55px;"></div>
                <div style="border-bottom: 1px solid #000; font-weight: bold;">( ......................................... )</div>
            </div>

            <div style="text-align: center; width: 220px;">
                <div>{{ $kelas->tahunAjaran->tempat_cetak_rapor ?? 'Jakarta' }}, {{ $kelas->tahunAjaran->tanggal_cetak_rapor ? $kelas->tahunAjaran->tanggal_cetak_rapor->translatedFormat('d F Y') : date('d F Y') }}</div>
                <div>Wali Kelas</div>
                <div style="height: 55px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $kelas->waliKelas->nama_guru ?? '.........................................' }}</div>
                <div>NIP. {{ $kelas->waliKelas->nip ?? '.........................................' }}</div>
            </div>

            <div style="text-align: center; width: 220px;">
                <div>Mengetahui,</div>
                <div>Kepala Sekolah</div>
                <div style="height: 55px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $kelas->tahunAjaran->nama_kepala_sekolah ?? '.........................................' }}</div>
                <div>NIP. {{ $kelas->tahunAjaran->nip_kepala_sekolah ?? '.........................................' }}</div>
            </div>
        </div>
    </div>

    @if(! $loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
@endsection

