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
        Schema::create('skrining_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamil')->onDelete('cascade');
            $table->foreignId('puskesmas_id')->constrained()->onDelete('restrict');
            $table->foreignId('tenaga_kesehatan_id')->nullable()->constrained('tenaga_kesehatan')->onDelete('set null');
            $table->string('no_skrining', 30)->unique();
            $table->date('tanggal_skrining');
            $table->integer('usia_kehamilan_minggu')->nullable();

            // Skor Base (Primigravida = 2)
            $table->integer('skor_base')->default(2);

            // Kelompok Faktor Risiko I
            $table->boolean('primigravida_terlalu_muda')->default(false); // ≤16 tahun = 4
            $table->boolean('primigravida_terlalu_tua')->default(false); // ≥35 tahun = 4
            $table->boolean('primigravida_tua_sekunder')->default(false); // = 4
            $table->boolean('tinggi_badan_rendah')->default(false); // ≤145cm = 4
            $table->boolean('gagal_kehamilan')->default(false); // = 4
            $table->boolean('riwayat_vakum_forceps')->default(false); // = 4
            $table->boolean('riwayat_operasi_sesar')->default(false); // = 4

            // Kelompok Faktor Risiko II
            $table->boolean('bayi_berat_rendah')->default(false); // <2500g = 4
            $table->boolean('bayi_cacat_bawaan')->default(false); // = 4
            $table->boolean('kurang_gizi_anemia')->default(false); // HB<11g = 4
            $table->boolean('penyakit_kronis')->default(false); // = 4
            $table->boolean('kelainan_obstetri')->default(false); // = 4
            $table->boolean('anak_terkecil_under_2')->default(false); // <2 tahun = 4
            $table->boolean('hamil_kembar')->default(false); // = 4
            $table->boolean('hidramnion')->default(false); // = 4
            $table->boolean('bayi_mati_kandungan')->default(false); // = 4
            $table->boolean('kehamilan_lebih_bulan')->default(false); // = 4
            $table->boolean('letak_sungsang')->default(false); // = 8
            $table->boolean('letak_lintang')->default(false); // = 8

            // Kelompok Faktor Risiko III
            $table->boolean('perdarahan_kehamilan')->default(false); // = 8
            $table->boolean('preeklampsia')->default(false); // = 8
            $table->boolean('eklampsia')->default(false); // = 8

            // Total Skor dan Kategori Risiko
            $table->integer('total_skor');
            $table->enum('kategori_risiko', ['KRR', 'KRT', 'KRST']); // Risiko Rendah, Tinggi, Sangat Tinggi

            // Rekomendasi
            $table->string('rekomendasi_tempat_bersalin'); // Puskesmas/Polindes, PONED/RS, RS
            $table->text('rekomendasi_tindakan')->nullable();
            $table->text('catatan')->nullable();

            // Status
            $table->enum('status', ['draft', 'final'])->default('final');
            $table->enum('jenis_skrining', ['mandiri', 'tenaga_kesehatan'])->default('mandiri');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tanggal_skrining');
            $table->index(['ibu_hamil_id', 'tanggal_skrining']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skrining_risiko');
    }
};
