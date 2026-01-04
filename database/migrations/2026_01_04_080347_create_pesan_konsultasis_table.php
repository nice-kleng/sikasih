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
        Schema::create('pesan_konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsultasi_id')->constrained('konsultasi')->onDelete('cascade');
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe_pengirim', ['ibu_hamil', 'tenaga_kesehatan']);
            $table->text('pesan');
            $table->text('file_lampiran')->nullable(); // Path file jika ada lampiran (foto, dokumen)
            $table->enum('tipe_pesan', ['text', 'image', 'document'])->default('text');
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['konsultasi_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_konsultasi');
    }
};
