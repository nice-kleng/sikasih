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
        Schema::create('tenaga_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('puskesmas_id')->constrained()->onDelete('cascade');
            $table->string('nip', 30)->unique()->nullable();
            $table->string('str', 30)->unique(); // Surat Tanda Registrasi
            $table->enum('jenis_tenaga', ['bidan', 'dokter', 'dokter_spesialis', 'perawat']);
            $table->string('spesialisasi')->nullable(); // Untuk dokter spesialis
            $table->string('pendidikan_terakhir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->date('tanggal_mulai_kerja');
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'Kontrak', 'Honorer'])->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_kesehatan');
    }
};
