@extends('layouts.cetak')

@section('title', 'Formulir F-PESERTA DIDIK - ' . $siswa->nama_lengkap)

@push('styles')
<style>
    .dapodik-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5pt;
        line-height: 1.3;
    }
    .dapodik-table td, .dapodik-table th {
        padding: 3px 5px;
        vertical-align: top;
    }
    .bordered-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
    }
    .bordered-table th, .bordered-table td {
        border: 1px solid #000;
        padding: 3px 5px;
    }
    .section-title {
        background-color: #f1f5f9;
        font-weight: bold;
        font-size: 9pt;
        padding: 4px 6px;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        margin-top: 8px;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .box-char {
        display: inline-block;
        border: 1px solid #333;
        width: 14px;
        height: 16px;
        line-height: 16px;
        text-align: center;
        font-family: monospace;
        font-size: 9pt;
        margin-right: 1px;
    }
</style>
@endpush

@section('content')
<div class="print-container">
    <!-- Header Dokumen Dapodik -->
    <div style="border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h5 style="margin: 0; font-weight: 800; font-size: 13pt;">FORMULIR PESERTA DIDIK (F-PD)</h5>
                <small style="font-size: 8pt; color: #444;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</small>
            </div>
            <div style="text-align: right; font-size: 8pt;">
                <div>Tanggal: <strong>{{ $siswa->tanggal_formulir ? $siswa->tanggal_formulir->format('d/m/Y') : date('d/m/Y') }}</strong></div>
                <div style="font-family: monospace;">F-PESERTA DIDIK</div>
            </div>
        </div>
    </div>

    <!-- Bagian 1: Data Pribadi -->
    <div class="section-title">DATA PRIBADI PESERTA DIDIK</div>
    <table class="dapodik-table">
        <tr>
            <td style="width: 25px;">1.</td>
            <td style="width: 220px;">Nama Lengkap</td>
            <td>: <strong>{{ strtoupper($siswa->nama_lengkap) }}</strong></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Jenis Kelamin</td>
            <td>: [ {{ $siswa->jenis_kelamin === 'L' ? 'X' : ' ' }} ] Laki-laki &nbsp;&nbsp;&nbsp; [ {{ $siswa->jenis_kelamin === 'P' ? 'X' : ' ' }} ] Perempuan</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>NISN</td>
            <td>: <span style="font-family: monospace; font-weight: bold;">{{ $siswa->nisn ?? '__________' }}</span></td>
        </tr>
        <tr>
            <td>4.</td>
            <td>NIK / No. KITAS</td>
            <td>: <span style="font-family: monospace;">{{ $siswa->nik ?? '________________' }}</span></td>
        </tr>
        <tr>
            <td>5.</td>
            <td>No. Kartu Keluarga</td>
            <td>: <span style="font-family: monospace;">{{ $siswa->no_kk ?? '________________' }}</span></td>
        </tr>
        <tr>
            <td>6.</td>
            <td>Tempat Lahir</td>
            <td>: {{ strtoupper($siswa->tempat_lahir) }}</td>
        </tr>
        <tr>
            <td>7.</td>
            <td>Tanggal Lahir</td>
            <td>: {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d / m / Y') : '- / - / -' }}</td>
        </tr>
        <tr>
            <td>8.</td>
            <td>No. Registrasi Akta Lahir</td>
            <td>: {{ $siswa->no_registrasi_akta_lahir ?? '-' }}</td>
        </tr>
        <tr>
            <td>9.</td>
            <td>Agama & Kepercayaan</td>
            <td>: {{ $siswa->agama ? $siswa->agama->kode . ') ' . $siswa->agama->nama : '-' }}</td>
        </tr>
        <tr>
            <td>10.</td>
            <td>Kewarganegaraan</td>
            <td>: {{ $siswa->kewarganegaraan }} (Negara: {{ $siswa->nama_negara ?? 'Indonesia' }})</td>
        </tr>
        <tr>
            <td>11.</td>
            <td>Berkebutuhan Khusus</td>
            <td>: {{ $siswa->berkebutuhanKhusus ? $siswa->berkebutuhanKhusus->kode . ') ' . $siswa->berkebutuhanKhusus->nama : '01) Tidak' }}</td>
        </tr>
        <tr>
            <td>12.</td>
            <td>Alamat Jalan</td>
            <td>: {{ $siswa->alamat_jalan ?? '-' }}</td>
        </tr>
        <tr>
            <td>13.</td>
            <td>RT / RW</td>
            <td>: RT. {{ $siswa->rt ?? '___' }} / RW. {{ $siswa->rw ?? '___' }}</td>
        </tr>
        <tr>
            <td>14.</td>
            <td>Nama Dusun / Kelurahan</td>
            <td>: Dusun {{ $siswa->nama_dusun ?? '-' }}, Kel/Desa {{ $siswa->nama_kelurahan_desa ?? '-' }}</td>
        </tr>
        <tr>
            <td>15.</td>
            <td>Kecamatan & Kode Pos</td>
            <td>: Kec. {{ $siswa->kecamatan ?? '-' }}, Kode Pos: {{ $siswa->kode_pos ?? '-' }}</td>
        </tr>
        <tr>
            <td>16.</td>
            <td>Tempat Tinggal</td>
            <td>: {{ $siswa->tempatTinggal ? $siswa->tempatTinggal->kode . ') ' . $siswa->tempatTinggal->nama : '-' }}</td>
        </tr>
        <tr>
            <td>17.</td>
            <td>Moda Transportasi</td>
            <td>: {{ $siswa->modaTransportasi ? $siswa->modaTransportasi->kode . ') ' . $siswa->modaTransportasi->nama : '-' }}</td>
        </tr>
        <tr>
            <td>18.</td>
            <td>Anak ke-berapa</td>
            <td>: {{ $siswa->anak_keberapa ?? '-' }}</td>
        </tr>
        <tr>
            <td>19.</td>
            <td>Penerima KIP / PIP</td>
            <td>: [ {{ $siswa->punya_kip ? 'X' : ' ' }} ] Ya &nbsp;&nbsp;&nbsp; [ {{ ! $siswa->punya_kip ? 'X' : ' ' }} ] Tidak</td>
        </tr>
    </table>

    <!-- Bagian 2: Data Orang Tua -->
    <div class="section-title">DATA ORANG TUA KANDUNG & WALI</div>
    <table class="bordered-table mb-2">
        <thead class="table-light">
            <tr>
                <th style="width: 25%;">Rincian Data</th>
                <th style="width: 25%;">Ayah Kandung</th>
                <th style="width: 25%;">Ibu Kandung</th>
                <th style="width: 25%;">Wali</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nama Lengkap</td>
                <td><strong>{{ $siswa->nama_ayah ?? '-' }}</strong></td>
                <td><strong>{{ $siswa->nama_ibu ?? '-' }}</strong></td>
                <td><strong>{{ $siswa->nama_wali ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>{{ $siswa->nik_ayah ?? '-' }}</td>
                <td>{{ $siswa->nik_ibu ?? '-' }}</td>
                <td>{{ $siswa->nik_wali ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tahun Lahir</td>
                <td>{{ $siswa->tahun_lahir_ayah ?? '-' }}</td>
                <td>{{ $siswa->tahun_lahir_ibu ?? '-' }}</td>
                <td>{{ $siswa->tahun_lahir_wali ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pendidikan</td>
                <td>{{ $siswa->pendidikanAyah->nama ?? '-' }}</td>
                <td>{{ $siswa->pendidikanIbu->nama ?? '-' }}</td>
                <td>{{ $siswa->pendidikanWali->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>{{ $siswa->pekerjaanAyah->nama ?? '-' }}</td>
                <td>{{ $siswa->pekerjaanIbu->nama ?? '-' }}</td>
                <td>{{ $siswa->pekerjaanWali->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Penghasilan Bulanan</td>
                <td>{{ $siswa->penghasilanAyah->nama ?? '-' }}</td>
                <td>{{ $siswa->penghasilanIbu->nama ?? '-' }}</td>
                <td>{{ $siswa->penghasilanWali->nama ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Bagian 3: Data Periodik & Kontak -->
    <div class="section-title">KONTAK & DATA PERIODIK</div>
    <table class="dapodik-table">
        <tr>
            <td style="width: 25px;">20.</td>
            <td style="width: 220px;">Nomor Telepon / HP</td>
            <td>: Telp: {{ $siswa->nomor_telepon_rumah ?? '-' }} | HP/WA: <strong>{{ $siswa->nomor_hp ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>21.</td>
            <td>Email Pribadi</td>
            <td>: {{ $siswa->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>22.</td>
            <td>Tinggi / Berat / Lingkar Kepala</td>
            <td>: {{ $siswa->tinggi_badan_cm ?? '-' }} cm / {{ $siswa->berat_badan_kg ?? '-' }} kg / {{ $siswa->lingkar_kepala_cm ?? '-' }} cm</td>
        </tr>
        <tr>
            <td>23.</td>
            <td>Jarak & Waktu Tempuh ke Sekolah</td>
            <td>: {{ $siswa->jarak_sekolah_km ?? '-' }} km ({{ $siswa->kategori_jarak_sekolah === 'kurang_1_km' ? '< 1 km' : '> 1 km' }}), Waktu: {{ $siswa->waktu_tempuh_jam }} Jam {{ $siswa->waktu_tempuh_menit }} Menit</td>
        </tr>
        <tr>
            <td>24.</td>
            <td>Jumlah Saudara Kandung</td>
            <td>: {{ $siswa->jumlah_saudara_kandung }} orang</td>
        </tr>
    </table>

    <!-- Bagian 4: Registrasi Siswa -->
    <div class="section-title">REGISTRASI PESERTA DIDIK</div>
    <table class="dapodik-table">
        <tr>
            <td style="width: 25px;">25.</td>
            <td style="width: 220px;">Kompetensi Keahlian (Jurusan)</td>
            <td>: <strong>{{ $siswa->kompetensiKeahlian->nama_jurusan ?? 'Umum' }}</strong> ({{ $siswa->kompetensiKeahlian->kode_jurusan ?? '-' }})</td>
        </tr>
        <tr>
            <td>26.</td>
            <td>Jenis Pendaftaran / Nomor Induk (NIS)</td>
            <td>: {{ $siswa->jenisPendaftaran->nama ?? '-' }} / NIS: <strong>{{ $siswa->nis ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>27.</td>
            <td>Sekolah Asal</td>
            <td>: {{ $siswa->sekolah_asal ?? '-' }}</td>
        </tr>
        <tr>
            <td>28.</td>
            <td>Tanggal Masuk Sekolah</td>
            <td>: {{ $siswa->tanggal_masuk_sekolah ? $siswa->tanggal_masuk_sekolah->translatedFormat('d F Y') : '-' }}</td>
        </tr>
    </table>

    <!-- Tanda Tangan Validasi -->
    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 8.5pt;">
        <div style="text-align: center; width: 220px;">
            <div>Mengetahui,</div>
            <div>Orang Tua / Wali Siswa</div>
            <div style="height: 55px;"></div>
            <div style="border-bottom: 1px solid #000; font-weight: bold;">( {{ $siswa->nama_ayah ?? $siswa->nama_ibu ?? $siswa->nama_wali ?? '.........................................' }} )</div>
        </div>
        <div style="text-align: center; width: 220px;">
            <div>........................, .............................</div>
            <div>Peserta Didik Bersangkutan</div>
            <div style="height: 55px;"></div>
            <div style="border-bottom: 1px solid #000; font-weight: bold;">( {{ strtoupper($siswa->nama_lengkap) }} )</div>
        </div>
    </div>
</div>
@endsection

