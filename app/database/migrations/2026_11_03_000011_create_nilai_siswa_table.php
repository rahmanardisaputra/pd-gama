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
        // 12. Tabel: nilai_siswa (Menyimpan Nilai Leger & Rapor)
        Schema::create('nilai_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_rombel_id')->constrained('anggota_rombel')->cascadeOnDelete();
            $table->foreignId('pengampu_mapel_id')->constrained('pengampu_mapel')->cascadeOnDelete();
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('predikat', 2)->nullable(); // A, B, C, D
            $table->text('deskripsi_capaian')->nullable();
            $table->timestamps();
            $table->unique(['anggota_rombel_id', 'pengampu_mapel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa');
    }
};
