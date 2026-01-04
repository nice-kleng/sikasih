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
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamil')->onDelete('cascade');
            $table->foreignId('tenaga_kesehatan_id')->nullable()->constrained('tenaga_kesehatan')->onDelete('set null');
            $table->foreignId('puskesmas_id')->constrained('puskesmas')->onDelete('restrict');
            $table->string('no_konsultasi', 30)->unique();
            $table->date('tanggal_konsultasi');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->integer('usia_kehamilan_minggu')->nullable();

            // Topik Konsultasi
            $table->string('topik_konsultasi');
            $table->enum('kategori', [
                'keluhan_kehamilan',
                'nutrisi',
                'olahraga',
                'persiapan_persalinan',
                'tanda_bahaya',
                'kontrasepsi',
                'lainnya'
            ]);

            // Metode Konsultasi
            $table->enum('metode', ['chat_online', 'video_call', 'tatap_muka', 'telepon'])->default('chat_online');

            // Keluhan dan Tindak Lanjut
            $table->text('keluhan_ibu')->nullable();
            $table->text('jawaban_konsultasi')->nullable();
            $table->text('saran')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('catatan')->nullable();

            // Status Konsultasi
            $table->enum('status', ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');

            // Rating (opsional, untuk feedback)
            $table->integer('rating')->nullable(); // 1-5
            $table->text('feedback')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tanggal_konsultasi');
            $table->index(['ibu_hamil_id', 'status']);
            $table->index(['tenaga_kesehatan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};
