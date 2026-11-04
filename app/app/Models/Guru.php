<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama_guru',
        'jenis_kelamin',
        'nomor_hp',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function pengampuMapel(): HasMany
    {
        return $this->hasMany(PengampuMapel::class, 'guru_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true)->orderBy('nama_guru');
    }
}
