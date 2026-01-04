<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hasil_laboratorium';

    protected $fillable = [
        'ibu_hamil_id',
        'puskesmas_id',
        'pemeriksaan_anc_id',
        'no_lab',
        'tanggal_pemeriksaan',
        'usia_kehamilan_minggu',
        'jenis_pemeriksaan',
        'hemoglobin',
        'leukosit',
        'eritrosit',
        'trombosit',
        'hematokrit',
        'mcv',
        'mch',
        'mchc',
        'golongan_darah',
        'rhesus',
        'warna_urine',
        'kejernihan_urine',
        'ph_urine',
        'berat_jenis_urine',
        'protein_urine',
        'glukosa_urine',
        'keton_urine',
        'bilirubin_urine',
        'urobilinogen_urine',
        'leukosit_urine',
        'eritrosit_urine',
        'hbsag',
        'anti_hcv',
        'anti_hiv',
        'vdrl_sifilis',
        'tpha_sifilis',
        'toxoplasma_igg',
        'toxoplasma_igm',
        'rubella_igg',
        'rubella_igm',
        'cmv_igg',
        'cmv_igm',
        'gula_darah_puasa',
        'gula_darah_2jam_pp',
        'gula_darah_sewaktu',
        'hba1c',
        'ureum',
        'kreatinin',
        'asam_urat',
        'sgot',
        'sgpt',
        'hasil_pemeriksaan',
        'interpretasi',
        'status_hasil',
        'saran_tindak_lanjut',
        'catatan',
        'nama_petugas_lab',
        'file_hasil'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
    ];

    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class);
    }

    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function pemeriksaanAnc()
    {
        return $this->belongsTo(PemeriksaanAnc::class);
    }
}
