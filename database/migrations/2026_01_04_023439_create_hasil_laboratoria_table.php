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
        Schema::create('hasil_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamil')->onDelete('cascade');
            $table->foreignId('puskesmas_id')->constrained('puskesmas')->onDelete('restrict');
            $table->foreignId('pemeriksaan_anc_id')->nullable()->constrained('pemeriksaan_anc')->onDelete('set null');
            $table->string('no_lab', 30)->unique();
            $table->date('tanggal_pemeriksaan');
            $table->integer('usia_kehamilan_minggu')->nullable();
            $table->enum('jenis_pemeriksaan', ['darah_lengkap', 'urine', 'serologi', 'kimia_darah', 'lainnya']);

            // Pemeriksaan Darah Lengkap
            $table->decimal('hemoglobin', 4, 1)->nullable(); // g/dL
            $table->integer('leukosit')->nullable(); // /µL
            $table->integer('eritrosit')->nullable(); // juta/µL
            $table->integer('trombosit')->nullable(); // ribu/µL
            $table->decimal('hematokrit', 4, 1)->nullable(); // %
            $table->decimal('mcv', 5, 1)->nullable(); // fL
            $table->decimal('mch', 5, 1)->nullable(); // pg
            $table->decimal('mchc', 5, 1)->nullable(); // g/dL

            // Golongan Darah & Rhesus
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('rhesus', ['+', '-'])->nullable();

            // Pemeriksaan Urine
            $table->string('warna_urine')->nullable();
            $table->string('kejernihan_urine')->nullable();
            $table->decimal('ph_urine', 3, 1)->nullable();
            $table->string('berat_jenis_urine')->nullable();
            $table->string('protein_urine')->nullable();
            $table->string('glukosa_urine')->nullable();
            $table->string('keton_urine')->nullable();
            $table->string('bilirubin_urine')->nullable();
            $table->string('urobilinogen_urine')->nullable();
            $table->integer('leukosit_urine')->nullable();
            $table->integer('eritrosit_urine')->nullable();

            // Pemeriksaan Serologi
            $table->enum('hbsag', ['Reaktif', 'Non-Reaktif'])->nullable();
            $table->enum('anti_hcv', ['Reaktif', 'Non-Reaktif'])->nullable();
            $table->enum('anti_hiv', ['Reaktif', 'Non-Reaktif'])->nullable();
            $table->enum('vdrl_sifilis', ['Reaktif', 'Non-Reaktif'])->nullable();
            $table->enum('tpha_sifilis', ['Reaktif', 'Non-Reaktif'])->nullable();
            $table->string('toxoplasma_igg')->nullable();
            $table->string('toxoplasma_igm')->nullable();
            $table->string('rubella_igg')->nullable();
            $table->string('rubella_igm')->nullable();
            $table->string('cmv_igg')->nullable();
            $table->string('cmv_igm')->nullable();

            // Pemeriksaan Kimia Darah
            $table->integer('gula_darah_puasa')->nullable(); // mg/dL
            $table->integer('gula_darah_2jam_pp')->nullable(); // mg/dL
            $table->integer('gula_darah_sewaktu')->nullable(); // mg/dL
            $table->decimal('hba1c', 4, 1)->nullable(); // %
            $table->decimal('ureum', 5, 1)->nullable(); // mg/dL
            $table->decimal('kreatinin', 4, 2)->nullable(); // mg/dL
            $table->decimal('asam_urat', 4, 1)->nullable(); // mg/dL
            $table->integer('sgot')->nullable(); // U/L
            $table->integer('sgpt')->nullable(); // U/L

            // Hasil dan Interpretasi
            $table->text('hasil_pemeriksaan')->nullable();
            $table->text('interpretasi')->nullable();
            $table->enum('status_hasil', ['normal', 'abnormal', 'perlu_tindak_lanjut'])->default('normal');
            $table->text('saran_tindak_lanjut')->nullable();
            $table->text('catatan')->nullable();

            // Petugas Lab
            $table->string('nama_petugas_lab')->nullable();
            $table->text('file_hasil')->nullable(); // Path file PDF hasil lab

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
        Schema::dropIfExists('hasil_laboratorium');
    }
};
