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
        // 10. Tabel: anggota_rombel (Pivot Siswa <-> Kelas di Tahun Ajaran tertentu)
        Schema::create('anggota_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->unsignedSmallInteger('nomor_urut_absen')->nullable();

            // Rekap Absensi & Catatan Rapor Semester Ini
            $table->unsignedSmallInteger('jumlah_sakit')->default(0);
            $table->unsignedSmallInteger('jumlah_izin')->default(0);
            $table->unsignedSmallInteger('jumlah_alpa')->default(0);
            $table->text('catatan_wali_kelas')->nullable();
            $table->string('status_kenaikan', 50)->nullable(); // Naik Kelas / Lulus / Tinggal Kelas

            $table->timestamps();
            $table->unique(['kelas_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_rombel');
    }
};
