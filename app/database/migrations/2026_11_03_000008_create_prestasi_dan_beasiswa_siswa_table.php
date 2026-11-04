<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel: prestasi_siswa (Relasi 1:N karena 1 siswa bisa punya banyak prestasi)
        Schema::create('prestasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('jenis_prestasi_id')->constrained('referensi_dapodik');
            $table->foreignId('tingkat_prestasi_id')->constrained('referensi_dapodik');
            $table->string('nama_prestasi', 150);
            $table->year('tahun_prestasi');
            $table->string('penyelenggara', 150);
            $table->string('peringkat', 20)->nullable();
            $table->timestamps();
        });

        // Tabel: beasiswa_siswa (Relasi 1:N karena 1 siswa bisa punya riwayat beasiswa)
        Schema::create('beasiswa_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('jenis_beasiswa_id')->constrained('referensi_dapodik');
            $table->string('keterangan', 150);
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswa_siswa');
        Schema::dropIfExists('prestasi_siswa');
    }
};
