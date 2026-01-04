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
        Schema::create('puskesmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kode_puskesmas', 20)->unique();
            $table->string('nama_puskesmas');
            $table->text('alamat');
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('kepala_puskesmas')->nullable();
            $table->text('fasilitas')->nullable(); // JSON untuk menyimpan fasilitas yang tersedia
            $table->enum('tipe', ['poned', 'non_poned'])->default('non_poned');
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
        Schema::dropIfExists('puskesmas');
    }
};
