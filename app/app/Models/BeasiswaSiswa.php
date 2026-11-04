<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeasiswaSiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswa_siswa';

    protected $fillable = [
        'siswa_id',
        'jenis_beasiswa_id',
        'keterangan',
        'tahun_mulai',
        'tahun_selesai',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisBeasiswa(): BelongsTo
    {
        return $this->belongsTo(ReferensiDapodik::class, 'jenis_beasiswa_id');
    }
}
