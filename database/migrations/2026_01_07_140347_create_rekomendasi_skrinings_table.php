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
        Schema::create('rekomendasi_skrining', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skrining_risiko_id')->constrained('skrining_risiko')->cascadeOnDelete();
            $table->enum('kategori_risiko', ['KRR', 'KRT', 'KRST']);
            $table->integer('total_skor');
            $table->text('rekomendasi_umum');
            $table->json('rekomendasi_list'); // Array of recommendations
            $table->string('tempat_bersalin');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_skrining');
    }
};
