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
        Schema::create('video_edukasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('youtube_id'); // ID video YouTube
            $table->string('thumbnail')->nullable();
            $table->enum('kategori', [
                'persiapan_kehamilan',
                'perkembangan_janin',
                'senam_hamil',
                'nutrisi',
                'persiapan_persalinan',
                'perawatan_bayi',
                'lainnya'
            ]);
            $table->integer('durasi_detik')->nullable();
            $table->integer('views')->default(0);
            $table->integer('urutan')->default(0); // Untuk sorting
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('kategori');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_edukasi');
    }
};
