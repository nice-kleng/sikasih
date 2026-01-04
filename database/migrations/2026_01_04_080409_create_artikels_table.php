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
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penulis_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('konten');
            $table->string('gambar_utama')->nullable();
            $table->enum('kategori', [
                'nutrisi',
                'olahraga',
                'perkembangan_janin',
                'tanda_bahaya',
                'persiapan_persalinan',
                'tips_kehamilan',
                'kesehatan_ibu',
                'lainnya'
            ]);
            $table->string('tags')->nullable(); // Comma separated tags
            $table->integer('views')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('published_at');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
