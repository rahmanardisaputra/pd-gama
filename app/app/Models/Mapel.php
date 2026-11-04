<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok',
        'urutan_leger',
    ];

    protected $casts = [
        'urutan_leger' => 'integer',
    ];

    public function pengampuMapel(): HasMany
    {
        return $this->hasMany(PengampuMapel::class, 'mapel_id');
    }
}
