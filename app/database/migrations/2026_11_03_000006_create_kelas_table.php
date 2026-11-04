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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('kompetensi_keahlian_id')->nullable()->constrained('kompetensi_keahlian')->nullOnDelete();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->string('nama_kelas', 30); // VII-A, X-RPL-1
            $table->unsignedTinyInteger('tingkat'); // 7, 8, 9 atau 10, 11, 12
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
