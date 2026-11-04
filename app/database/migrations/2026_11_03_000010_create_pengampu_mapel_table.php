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
        // 11. Tabel: pengampu_mapel (Pivot Kelas <-> Mapel <-> Guru)
        Schema::create('pengampu_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->unsignedTinyInteger('kkm')->default(75);
            $table->timestamps();
            $table->unique(['kelas_id', 'mapel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengampu_mapel');
    }
};
