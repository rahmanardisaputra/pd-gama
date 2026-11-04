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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_formulir')->nullable();

            // BAGIAN 1: DATA PRIBADI
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nisn', 10)->nullable()->unique();
            $table->string('nik', 16)->nullable()->unique(); // NIK / No. KITAS
            $table->string('no_kk', 16)->nullable();
            $table->string('tempat_lahir', 60);
            $table->date('tanggal_lahir');
            $table->string('no_registrasi_akta_lahir', 60)->nullable();
            $table->foreignId('agama_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->string('nama_negara', 60)->nullable()->default('Indonesia');
            $table->foreignId('berkebutuhan_khusus_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();

            // Alamat & Domisili
            $table->text('alamat_jalan')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('nama_dusun', 60)->nullable();
            $table->string('nama_kelurahan_desa', 60)->nullable();
            $table->string('kecamatan', 60)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('lintang', 30)->nullable();
            $table->string('bujur', 30)->nullable();
            $table->foreignId('tempat_tinggal_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('moda_transportasi_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->unsignedTinyInteger('anak_keberapa')->nullable();
            $table->foreignId('pekerjaan_siswa_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete(); // No 24: warga belajar

            // KIP / PIP
            $table->boolean('punya_kip')->default(false);
            $table->boolean('tetap_terima_kip')->default(false);
            $table->foreignId('alasan_menolak_pip_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();

            // BAGIAN 2: DATA AYAH KANDUNG
            $table->string('nama_ayah', 150)->nullable();
            $table->string('nik_ayah', 16)->nullable();
            $table->year('tahun_lahir_ayah')->nullable();
            $table->foreignId('pendidikan_ayah_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('pekerjaan_ayah_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('penghasilan_ayah_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('kebutuhan_khusus_ayah_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();

            // BAGIAN 3: DATA IBU KANDUNG
            $table->string('nama_ibu', 150)->nullable();
            $table->string('nik_ibu', 16)->nullable();
            $table->year('tahun_lahir_ibu')->nullable();
            $table->foreignId('pendidikan_ibu_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('pekerjaan_ibu_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('penghasilan_ibu_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('kebutuhan_khusus_ibu_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();

            // BAGIAN 4: DATA WALI
            $table->string('nama_wali', 150)->nullable();
            $table->string('nik_wali', 16)->nullable();
            $table->year('tahun_lahir_wali')->nullable();
            $table->foreignId('pendidikan_wali_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('pekerjaan_wali_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->foreignId('penghasilan_wali_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();

            // BAGIAN 5: KONTAK
            $table->string('nomor_telepon_rumah', 20)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('email', 100)->nullable();

            // BAGIAN 6: DATA PERIODIK
            $table->unsignedSmallInteger('tinggi_badan_cm')->nullable();
            $table->unsignedSmallInteger('berat_badan_kg')->nullable();
            $table->unsignedSmallInteger('lingkar_kepala_cm')->nullable();
            $table->enum('kategori_jarak_sekolah', ['kurang_1_km', 'lebih_1_km'])->nullable();
            $table->unsignedSmallInteger('jarak_sekolah_km')->nullable();
            $table->unsignedTinyInteger('waktu_tempuh_jam')->default(0);
            $table->unsignedTinyInteger('waktu_tempuh_menit')->default(0);
            $table->unsignedTinyInteger('jumlah_saudara_kandung')->default(0);

            // BAGIAN 7: REGISTRASI PESERTA DIDIK (MASUK)
            $table->foreignId('kompetensi_keahlian_id')->nullable()->constrained('kompetensi_keahlian')->nullOnDelete();
            $table->foreignId('jenis_pendaftaran_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->string('nis', 30)->nullable()->index(); // NIS / Nomor Induk PD
            $table->date('tanggal_masuk_sekolah')->nullable();
            $table->string('sekolah_asal', 100)->nullable();
            $table->string('nomor_peserta_un_sebelumnya', 30)->nullable();
            $table->string('no_seri_ijazah_sebelumnya', 40)->nullable();
            $table->string('no_skhun_sebelumnya', 40)->nullable();

            // BAGIAN 8: PENDAFTARAN KELUAR
            $table->foreignId('keluar_karena_id')->nullable()->constrained('referensi_dapodik')->nullOnDelete();
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
