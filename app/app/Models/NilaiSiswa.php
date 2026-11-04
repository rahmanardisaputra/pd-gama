<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_siswa';

    protected $fillable = [
        'anggota_rombel_id',
        'pengampu_mapel_id',
        'nilai_akhir',
        'predikat',
        'deskripsi_capaian',
    ];

    protected $casts = [
        'nilai_akhir' => 'float',
    ];

    public function anggotaRombel(): BelongsTo
    {
        return $this->belongsTo(AnggotaRombel::class, 'anggota_rombel_id');
    }

    public function pengampuMapel(): BelongsTo
    {
        return $this->belongsTo(PengampuMapel::class, 'pengampu_mapel_id');
    }
}
