<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkriningRisiko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'skrining_risiko';

    protected $fillable = [
        'ibu_hamil_id',
        'puskesmas_id',
        'tenaga_kesehatan_id',
        'no_skrining',
        'tanggal_skrining',
        'usia_kehamilan_minggu',
        'skor_base',
        // Kelompok I
        'primigravida_terlalu_muda',
        'primigravida_terlalu_tua',
        'primigravida_tua_sekunder',
        'tinggi_badan_rendah',
        'gagal_kehamilan',
        'riwayat_vakum_forceps',
        'riwayat_operasi_sesar',
        // Kelompok II
        'bayi_berat_rendah',
        'bayi_cacat_bawaan',
        'kurang_gizi_anemia',
        'penyakit_kronis',
        'kelainan_obstetri',
        'anak_terkecil_under_2',
        'hamil_kembar',
        'hidramnion',
        'bayi_mati_kandungan',
        'kehamilan_lebih_bulan',
        'letak_sungsang',
        'letak_lintang',
        // Kelompok III
        'perdarahan_kehamilan',
        'preeklampsia',
        'eklampsia',
        // Hasil
        'total_skor',
        'kategori_risiko',
        'rekomendasi_tempat_bersalin',
        'rekomendasi_tindakan',
        'catatan',
        'status',
        'jenis_skrining',
    ];

    protected $casts = [
        'tanggal_skrining' => 'date',
        'primigravida_terlalu_muda' => 'boolean',
        'primigravida_terlalu_tua' => 'boolean',
        'primigravida_tua_sekunder' => 'boolean',
        'tinggi_badan_rendah' => 'boolean',
        'gagal_kehamilan' => 'boolean',
        'riwayat_vakum_forceps' => 'boolean',
        'riwayat_operasi_sesar' => 'boolean',
        'bayi_berat_rendah' => 'boolean',
        'bayi_cacat_bawaan' => 'boolean',
        'kurang_gizi_anemia' => 'boolean',
        'penyakit_kronis' => 'boolean',
        'kelainan_obstetri' => 'boolean',
        'anak_terkecil_under_2' => 'boolean',
        'hamil_kembar' => 'boolean',
        'hidramnion' => 'boolean',
        'bayi_mati_kandungan' => 'boolean',
        'kehamilan_lebih_bulan' => 'boolean',
        'letak_sungsang' => 'boolean',
        'letak_lintang' => 'boolean',
        'perdarahan_kehamilan' => 'boolean',
        'preeklampsia' => 'boolean',
        'eklampsia' => 'boolean',
    ];

    /**
     * Relationships
     */

    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class);
    }

    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function tenagaKesehatan()
    {
        return $this->belongsTo(TenagaKesehatan::class);
    }

    public function rekomendasi()
    {
        return $this->hasOne(RekomendasiSkrining::class);
    }

    /**
     * Scopes
     */

    public function scopeFinal($query)
    {
        return $query->where('status', 'final');
    }

    public function scopeMandiri($query)
    {
        return $query->where('jenis_skrining', 'mandiri');
    }

    public function scopeOlehTenagaKesehatan($query)
    {
        return $query->where('jenis_skrining', 'tenaga_kesehatan');
    }

    /**
     * Methods untuk Perhitungan Skor
     */

    public static function hitungSkor($data)
    {
        $skor = 2; // Skor base primigravida

        // Kelompok Faktor Risiko I (masing-masing = 4)
        $faktorRisiko4 = [
            'primigravida_terlalu_muda',
            'primigravida_terlalu_tua',
            'primigravida_tua_sekunder',
            'tinggi_badan_rendah',
            'gagal_kehamilan',
            'riwayat_vakum_forceps',
            'riwayat_operasi_sesar',
            'bayi_berat_rendah',
            'bayi_cacat_bawaan',
            'kurang_gizi_anemia',
            'penyakit_kronis',
            'kelainan_obstetri',
            'anak_terkecil_under_2',
            'hamil_kembar',
            'hidramnion',
            'bayi_mati_kandungan',
            'kehamilan_lebih_bulan',
        ];

        foreach ($faktorRisiko4 as $faktor) {
            if (isset($data[$faktor]) && $data[$faktor]) {
                $skor += 4;
            }
        }

        // Faktor Risiko dengan skor 8
        $faktorRisiko8 = [
            'letak_sungsang',
            'letak_lintang',
            'perdarahan_kehamilan',
            'preeklampsia',
            'eklampsia',
        ];

        foreach ($faktorRisiko8 as $faktor) {
            if (isset($data[$faktor]) && $data[$faktor]) {
                $skor += 8;
            }
        }

        return $skor;
    }

    public static function tentukanKategoriRisiko($totalSkor)
    {
        if ($totalSkor >= 2 && $totalSkor <= 6) {
            return 'KRR'; // Kehamilan Risiko Rendah
        } elseif ($totalSkor >= 8 && $totalSkor <= 12) {
            return 'KRT'; // Kehamilan Risiko Tinggi
        } else {
            return 'KRST'; // Kehamilan Risiko Sangat Tinggi
        }
    }

    public static function tentukanTempatBersalin($kategori)
    {
        switch ($kategori) {
            case 'KRR':
                return 'Puskesmas atau Polindes';
            case 'KRT':
                return 'Puskesmas PONED atau Rumah Sakit';
            case 'KRST':
                return 'Rumah Sakit';
            default:
                return 'Puskesmas';
        }
    }

    public static function getRekomendasiTindakan($kategori)
    {
        $rekomendasi = [
            'KRR' => [
                'Lakukan pemeriksaan kehamilan rutin minimal 4 kali (1-1-2)',
                'Konsumsi tablet tambah darah dan vitamin sesuai anjuran',
                'Jaga pola makan bergizi seimbang',
                'Istirahat yang cukup dan hindari stress',
                'Persiapkan persalinan dengan baik'
            ],
            'KRT' => [
                'Diperlukan pemeriksaan lebih intensif oleh tenaga kesehatan',
                'Konsultasi dengan dokter spesialis kandungan',
                'Perhatikan tanda-tanda bahaya kehamilan',
                'Siapkan donor darah dan transportasi darurat',
                'Pertimbangkan untuk tinggal dekat fasilitas kesehatan menjelang persalinan'
            ],
            'KRST' => [
                'Segera konsultasi dengan dokter spesialis kandungan',
                'Pemeriksaan dan monitoring ketat sangat diperlukan',
                'Persiapkan biaya, donor darah, dan transportasi darurat',
                'Jika terjadi tanda bahaya segera ke Rumah Sakit',
                'Pertimbangkan rawat inap jika diperlukan',
                'Keluarga harus siap mendampingi kapan saja'
            ]
        ];

        return $rekomendasi[$kategori] ?? [];
    }

    /**
     * Accessors
     */

    public function getKategoriRisikoLengkapAttribute()
    {
        $labels = [
            'KRR' => 'Kehamilan Risiko Rendah',
            'KRT' => 'Kehamilan Risiko Tinggi',
            'KRST' => 'Kehamilan Risiko Sangat Tinggi'
        ];

        return $labels[$this->kategori_risiko] ?? $this->kategori_risiko;
    }

    public function getJumlahFaktorRisikoAttribute()
    {
        $jumlah = 0;
        $faktors = [
            'primigravida_terlalu_muda',
            'primigravida_terlalu_tua',
            'primigravida_tua_sekunder',
            'tinggi_badan_rendah',
            'gagal_kehamilan',
            'riwayat_vakum_forceps',
            'riwayat_operasi_sesar',
            'bayi_berat_rendah',
            'bayi_cacat_bawaan',
            'kurang_gizi_anemia',
            'penyakit_kronis',
            'kelainan_obstetri',
            'anak_terkecil_under_2',
            'hamil_kembar',
            'hidramnion',
            'bayi_mati_kandungan',
            'kehamilan_lebih_bulan',
            'letak_sungsang',
            'letak_lintang',
            'perdarahan_kehamilan',
            'preeklampsia',
            'eklampsia',
        ];

        foreach ($faktors as $faktor) {
            if ($this->$faktor) {
                $jumlah++;
            }
        }

        return $jumlah;
    }

    public function getDaftarFaktorRisikoAttribute()
    {
        $daftar = [];
        $labels = [
            'primigravida_terlalu_muda' => 'Primigravida terlalu muda (≤16 tahun)',
            'primigravida_terlalu_tua' => 'Primigravida terlalu tua (≥35 tahun)',
            'primigravida_tua_sekunder' => 'Primigravida tua sekunder',
            'tinggi_badan_rendah' => 'Tinggi badan ≤145 cm',
            'gagal_kehamilan' => 'Pernah gagal kehamilan',
            'riwayat_vakum_forceps' => 'Riwayat vakum/forceps',
            'riwayat_operasi_sesar' => 'Riwayat operasi sesar',
            'bayi_berat_rendah' => 'Bayi berat lahir rendah (<2500g)',
            'bayi_cacat_bawaan' => 'Bayi cacat bawaan',
            'kurang_gizi_anemia' => 'Kurang gizi/anemia (HB<11g)',
            'penyakit_kronis' => 'Penyakit kronis',
            'kelainan_obstetri' => 'Kelainan obstetri',
            'anak_terkecil_under_2' => 'Anak terkecil <2 tahun',
            'hamil_kembar' => 'Hamil kembar',
            'hidramnion' => 'Hidramnion',
            'bayi_mati_kandungan' => 'Bayi mati dalam kandungan',
            'kehamilan_lebih_bulan' => 'Kehamilan lebih bulan',
            'letak_sungsang' => 'Letak sungsang',
            'letak_lintang' => 'Letak lintang',
            'perdarahan_kehamilan' => 'Perdarahan dalam kehamilan',
            'preeklampsia' => 'Preeklampsia',
            'eklampsia' => 'Eklampsia',
        ];

        foreach ($labels as $key => $label) {
            if ($this->$key) {
                $daftar[] = $label;
            }
        }

        return $daftar;
    }
}
