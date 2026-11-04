<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_formulir' => 'date',
        'tanggal_lahir' => 'date',
        'tanggal_masuk_sekolah' => 'date',
        'tanggal_keluar' => 'date',
        'punya_kip' => 'boolean',
        'tetap_terima_kip' => 'boolean',
        'anak_keberapa' => 'integer',
        'tinggi_badan_cm' => 'integer',
        'berat_badan_kg' => 'integer',
        'lingkar_kepala_cm' => 'integer',
        'jarak_sekolah_km' => 'integer',
        'waktu_tempuh_jam' => 'integer',
        'waktu_tempuh_menit' => 'integer',
        'jumlah_saudara_kandung' => 'integer',
    ];

    public function agama(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'agama_id');
    }

    public function berkebutuhanKhusus(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'berkebutuhan_khusus_id');
    }

    public function tempatTinggal(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'tempat_tinggal_id');
    }

    public function modaTransportasi(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'moda_transportasi_id');
    }

    public function pekerjaanSiswa(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pekerjaan_siswa_id');
    }

    public function alasanMenolakPip(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'alasan_menolak_pip_id');
    }

    public function pendidikanAyah(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pendidikan_ayah_id');
    }

    public function pekerjaanAyah(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pekerjaan_ayah_id');
    }

    public function penghasilanAyah(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'penghasilan_ayah_id');
    }

    public function kebutuhanKhususAyah(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'kebutuhan_khusus_ayah_id');
    }

    public function pendidikanIbu(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pendidikan_ibu_id');
    }

    public function pekerjaanIbu(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pekerjaan_ibu_id');
    }

    public function penghasilanIbu(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'penghasilan_ibu_id');
    }

    public function kebutuhanKhususIbu(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'kebutuhan_khusus_ibu_id');
    }

    public function pendidikanWali(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pendidikan_wali_id');
    }

    public function pekerjaanWali(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'pekerjaan_wali_id');
    }

    public function penghasilanWali(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'penghasilan_wali_id');
    }

    public function kompetensiKeahlian(): BelongsTo
    {
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_keahlian_id');
    }

    public function jenisPendaftaran(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'jenis_pendaftaran_id');
    }

    public function keluarKarena(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'keluar_karena_id');
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(PrestasiSiswa::class, 'siswa_id');
    }

    public function beasiswa(): HasMany
    {
        return $this->hasMany(BeasiswaSiswa::class, 'siswa_id');
    }

    public function anggotaRombel(): HasMany
    {
        return $this->hasMany(AnggotaRombel::class, 'siswa_id');
    }

    public function rombelAktif()
    {
        return $this->hasOne(AnggotaRombel::class, 'siswa_id')
            ->whereHas('kelas.tahunAjaran', function ($q) {
                $q->where('aktif', true);
            });
    }
}
