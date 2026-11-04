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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun', 20); // Contoh: 2026/2027
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('aktif')->default(false);
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah', 30)->nullable();
            $table->string('tempat_cetak_rapor', 50)->nullable();
            $table->date('tanggal_cetak_rapor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
