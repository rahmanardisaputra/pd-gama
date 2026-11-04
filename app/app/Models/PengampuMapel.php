<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengampuMapel extends Model
{
    use HasFactory;

    protected $table = 'pengampu_mapel';

    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'kkm',
    ];

    protected $casts = [
        'kkm' => 'integer',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class, 'pengampu_mapel_id');
    }
}
