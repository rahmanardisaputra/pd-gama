<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KompetensiKeahlian extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_keahlian';

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kompetensi_keahlian_id');
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'kompetensi_keahlian_id');
    }
}
