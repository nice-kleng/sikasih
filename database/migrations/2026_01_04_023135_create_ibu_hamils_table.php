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
        Schema::create('ibu_hamil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('puskesmas_id')->constrained()->onDelete('restrict');
            $table->string('no_rm', 20)->unique(); // Nomor Rekam Medis
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->integer('umur');
            $table->text('alamat_lengkap');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable();
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'])->nullable();
            $table->string('pekerjaan')->nullable();

            // Data Suami
            $table->string('nama_suami');
            $table->integer('umur_suami')->nullable();
            $table->string('pekerjaan_suami');
            $table->enum('pendidikan_suami', ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'])->nullable();

            // Data Kehamilan
            $table->integer('gravida'); // Jumlah kehamilan (G)
            $table->integer('para'); // Jumlah persalinan (P)
            $table->integer('abortus'); // Jumlah keguguran (A)
            $table->integer('anak_hidup'); // Jumlah anak hidup
            $table->integer('usia_menikah')->nullable();
            $table->integer('usia_hamil_pertama')->nullable();
            $table->text('riwayat_persalinan')->nullable();
            $table->string('jarak_kehamilan_terakhir')->nullable();
            $table->text('riwayat_komplikasi')->nullable();

            // BPJS
            $table->enum('memiliki_bpjs', ['Ya', 'Tidak'])->default('Tidak');
            $table->string('no_bpjs', 20)->nullable();

            // Kehamilan Saat Ini
            $table->date('hpht')->nullable(); // Hari Pertama Haid Terakhir
            $table->date('hpl')->nullable(); // Hari Perkiraan Lahir
            $table->integer('usia_kehamilan_minggu')->nullable();
            $table->decimal('berat_badan_awal', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();

            $table->enum('status_kehamilan', ['hamil', 'melahirkan', 'nifas', 'selesai'])->default('hamil');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu_hamil');
    }
};
