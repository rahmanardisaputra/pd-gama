<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'prestasi_siswa';

    protected $fillable = [
        'siswa_id',
        'jenis_prestasi_id',
        'tingkat_prestasi_id',
        'nama_prestasi',
        'tahun_prestasi',
        'penyelenggara',
        'peringkat',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisPrestasi(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'jenis_prestasi_id');
    }

    public function tingkatPrestasi(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'tingkat_prestasi_id');
    }
}
