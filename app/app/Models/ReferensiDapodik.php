<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiDapodik extends Model
{
    use HasFactory;

    protected $table = 'referensi_dapodik';

    protected $fillable = [
        'kategori',
        'kode',
        'nama',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope query untuk kategori tertentu yang aktif.
     */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori)
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('kode');
    }

    /**
     * Scope query untuk kategori tanpa filter aktif.
     */
    public function scopeSemuaKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori)
            ->orderBy('urutan')
            ->orderBy('kode');
    }
}
