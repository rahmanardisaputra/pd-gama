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
        Schema::create('referensi_dapodik', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 50)->index(); // contoh: 'pekerjaan', 'pendidikan', 'agama'
            $table->string('kode', 10)->nullable();  // contoh: '01', '02', '99' sesuai form Dapodik
            $table->string('nama', 100);             // contoh: 'PNS/TNI/POLRI', 'Karyawan Swasta'
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referensi_dapodik');
    }
};
