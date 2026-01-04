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
        Schema::create('pemeriksaan_anc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamil')->onDelete('cascade');
            $table->foreignId('puskesmas_id')->constrained()->onDelete('restrict');
            $table->foreignId('tenaga_kesehatan_id')->constrained('tenaga_kesehatan')->onDelete('restrict');
            $table->string('no_pemeriksaan', 30)->unique();
            $table->date('tanggal_pemeriksaan');
            $table->time('waktu_pemeriksaan');
            $table->integer('kunjungan_ke'); // ANC ke-berapa
            $table->integer('usia_kehamilan_minggu'); // Usia kehamilan saat pemeriksaan

            // Vital Signs
            $table->decimal('berat_badan', 5, 2); // kg
            $table->decimal('tinggi_badan', 5, 2)->nullable(); // cm (opsional, biasa diukur sekali)
            $table->integer('tekanan_darah_sistol'); // mmHg
            $table->integer('tekanan_darah_diastol'); // mmHg
            $table->decimal('suhu_tubuh', 4, 1)->nullable(); // Celsius
            $table->integer('nadi')->nullable(); // bpm
            $table->integer('respirasi')->nullable(); // per menit

            // Pemeriksaan Fisik Kehamilan
            $table->decimal('lila', 4, 1)->nullable(); // Lingkar Lengan Atas (cm)
            $table->decimal('tinggi_fundus', 4, 1)->nullable(); // cm
            $table->integer('djj')->nullable(); // Denyut Jantung Janin (bpm)
            $table->string('letak_janin')->nullable(); // kepala, sungsang, lintang
            $table->string('presentasi')->nullable();
            $table->decimal('taksiran_berat_janin', 6, 2)->nullable(); // gram

            // Pemeriksaan Laboratorium (jika ada di kunjungan ini)
            $table->decimal('hb', 4, 1)->nullable(); // g/dL
            $table->string('golongan_darah')->nullable();
            $table->string('protein_urin')->nullable();
            $table->string('glukosa_urin')->nullable();
            $table->enum('hbsag', ['Reaktif', 'Non-Reaktif', 'Belum Diperiksa'])->nullable();
            $table->enum('hiv', ['Reaktif', 'Non-Reaktif', 'Belum Diperiksa'])->nullable();
            $table->enum('sifilis', ['Reaktif', 'Non-Reaktif', 'Belum Diperiksa'])->nullable();

            // Keluhan dan Riwayat
            $table->text('keluhan')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('riwayat_alergi')->nullable();

            // Diagnosis dan Tindakan
            $table->text('diagnosis')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('terapi_obat')->nullable();
            $table->text('edukasi')->nullable();
            $table->text('catatan')->nullable();

            // Jadwal Kunjungan Berikutnya
            $table->date('jadwal_kunjungan_berikutnya')->nullable();

            // Status dan Rujukan
            $table->enum('status_pemeriksaan', ['selesai', 'rujukan'])->default('selesai');
            $table->string('rujukan_ke')->nullable();
            $table->text('alasan_rujukan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tanggal_pemeriksaan');
            $table->index(['ibu_hamil_id', 'tanggal_pemeriksaan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_anc');
    }
};
