<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'kompetensi_keahlian_id',
        'wali_kelas_id',
        'nama_kelas',
        'tingkat',
    ];

    protected $casts = [
        'tingkat' => 'integer',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function kompetensiKeahlian(): BelongsTo
    {
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_keahlian_id');
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function anggotaRombel(): HasMany
    {
        return $this->hasMany(AnggotaRombel::class, 'kelas_id')->orderBy('nomor_urut_absen');
    }

    public function pengampuMapel(): HasMany
    {
        return $this->hasMany(PengampuMapel::class, 'kelas_id');
    }

    public function siswa(): HasManyThrough
    {
        return $this->hasManyThrough(
            Siswa::class,
            AnggotaRombel::class,
            'kelas_id',
            'id',
            'id',
            'siswa_id'
        );
    }
}
