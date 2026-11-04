<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnggotaRombel extends Model
{
    use HasFactory;

    protected $table = 'anggota_rombel';

    protected $fillable = [
        'kelas_id',
        'siswa_id',
        'nomor_urut_absen',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alpa',
        'catatan_wali_kelas',
        'status_kenaikan',
    ];

    protected $casts = [
        'nomor_urut_absen' => 'integer',
        'jumlah_sakit' => 'integer',
        'jumlah_izin' => 'integer',
        'jumlah_alpa' => 'integer',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class, 'anggota_rombel_id');
    }
}
