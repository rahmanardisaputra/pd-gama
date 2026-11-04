<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun',
        'semester',
        'aktif',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'tempat_cetak_rapor',
        'tanggal_cetak_rapor',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'tanggal_cetak_rapor' => 'date',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }
}
