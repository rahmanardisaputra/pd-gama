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
        Schema::create('mapel', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique(); // MTK, BIND, PAI
            $table->string('nama_mapel', 100);
            $table->string('kelompok', 10)->default('A'); // Kelompok A, B, atau Kejuruan
            $table->unsignedSmallInteger('urutan_leger')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};
