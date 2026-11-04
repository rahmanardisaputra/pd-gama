@extends('layouts.cetak')

@section('title', 'Cetak Leger Nilai - ' . $kelas->nama_kelas)

@push('styles')
<style>
    @page {
        size: landscape;
        margin: 8mm;
    }
    .print-landscape {
        width: 100% !important;
        max-width: 297mm;
        margin: 0 auto;
        padding: 10mm;
        background: #fff;
    }
    .table-leger {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
    }
    .table-leger th, .table-leger td {
        border: 1px solid #000;
        padding: 3px 4px;
    }
    .header-leger {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 6px;
        margin-bottom: 12px;
    }
</style>
@endpush

@section('content')
<div class="print-container print-landscape">
    <div class="header-leger">
        <h4 style="margin: 0; font-weight: 800; font-size: 13pt;">LEGER NILAI HASIL BELAJAR SISWA</h4>
        <div style="font-size: 9pt; margin-top: 3px;">
            KELAS: <strong>{{ $kelas->nama_kelas }}</strong> &nbsp;|&nbsp;
            TAHUN AJARAN: <strong>{{ $kelas->tahunAjaran->nama_tahun }} ({{ $kelas->tahunAjaran->semester }})</strong> &nbsp;|&nbsp;
            WALI KELAS: <strong>{{ $kelas->waliKelas->nama_guru ?? '-' }}</strong>
        </div>
    </div>

    <table class="table-leger">
        <thead>
            <tr style="background: #f1f5f9; text-align: center;">
                <th rowspan="2" style="width: 25px;">No</th>
                <th rowspan="2" style="width: 35px;">Abs</th>
                <th rowspan="2" style="width: 65px;">NIS</th>
                <th rowspan="2" style="width: 75px;">NISN</th>
                <th rowspan="2" style="min-width: 160px; text-align: left;">Nama Peserta Didik</th>
                @foreach($pengampuList as $p)
                    <th>{{ $p->mapel->kode_mapel }}</th>
                @endforeach
                <th rowspan="2" style="width: 45px;">Jml</th>
                <th rowspan="2" style="width: 45px;">Rata</th>
                <th rowspan="2" style="width: 35px;">Rank</th>
                <th colspan="3">Absensi</th>
            </tr>
            <tr style="background: #f1f5f9; text-align: center;">
                @foreach($pengampuList as $p)
                    <th style="font-size: 7pt; font-weight: normal;">{{ $p->kkm }}</th>
                @endforeach
                <th style="width: 25px;">S</th>
                <th style="width: 25px;">I</th>
                <th style="width: 25px;">A</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataTemp as $idx => $row)
                @php $anggota = $row['anggota']; @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center;">{{ $anggota->nomor_urut_absen ?? '-' }}</td>
                    <td style="text-align: center; font-family: monospace;">{{ $anggota->siswa->nis ?? '-' }}</td>
                    <td style="text-align: center; font-family: monospace;">{{ $anggota->siswa->nisn ?? '-' }}</td>
                    <td>{{ $anggota->siswa->nama_lengkap }}</td>

                    @foreach($pengampuList as $p)
                        @php $val = $row['nilai_detail'][$p->id] ?? null; @endphp
                        <td style="text-align: center; font-family: monospace; {{ $val !== null && $val < $p->kkm ? 'font-weight: bold; text-decoration: underline;' : '' }}">
                            {{ $val !== null ? number_format($val, 0) : '-' }}
                        </td>
                    @endforeach

                    <td style="text-align: center; font-weight: bold; font-family: monospace;">{{ number_format($row['total'], 0) }}</td>
                    <td style="text-align: center; font-weight: bold; font-family: monospace;">{{ number_format($row['rata'], 1) }}</td>
                    <td style="text-align: center; font-weight: bold; font-family: monospace;">{{ $rankMap[$anggota->id] ?? '-' }}</td>
                    <td style="text-align: center;">{{ $anggota->jumlah_sakit }}</td>
                    <td style="text-align: center;">{{ $anggota->jumlah_izin }}</td>
                    <td style="text-align: center;">{{ $anggota->jumlah_alpa }}</td>
                </tr>
            @empty
                <tr><td colspan="{{ 11 + count($pengampuList) }}" style="text-align: center; padding: 20px;">Belum ada siswa di kelas ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 8.5pt;">
        <div style="text-align: center; width: 220px;">
            <div>Mengetahui,</div>
            <div>Kepala Sekolah</div>
            <div style="height: 50px;"></div>
            <div style="font-weight: bold; text-decoration: underline;">{{ $kelas->tahunAjaran->nama_kepala_sekolah ?? '.........................................' }}</div>
            <div>NIP. {{ $kelas->tahunAjaran->nip_kepala_sekolah ?? '.........................................' }}</div>
        </div>
        <div style="text-align: center; width: 220px;">
            <div>{{ $kelas->tahunAjaran->tempat_cetak_rapor ?? 'Jakarta' }}, {{ $kelas->tahunAjaran->tanggal_cetak_rapor ? $kelas->tahunAjaran->tanggal_cetak_rapor->translatedFormat('d F Y') : date('d F Y') }}</div>
            <div>Wali Kelas {{ $kelas->nama_kelas }}</div>
            <div style="height: 50px;"></div>
            <div style="font-weight: bold; text-decoration: underline;">{{ $kelas->waliKelas->nama_guru ?? '.........................................' }}</div>
            <div>NIP. {{ $kelas->waliKelas->nip ?? '.........................................' }}</div>
        </div>
    </div>
</div>
@endsection

