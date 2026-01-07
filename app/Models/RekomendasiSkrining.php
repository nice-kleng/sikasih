<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekomendasiSkrining extends Model
{
    protected $table = 'rekomendasi_skrining';

    protected $fillable = [
        'skrining_risiko_id',
        'kategori_risiko',
        'total_skor',
        'rekomendasi_umum',
        'rekomendasi_list',
        'tempat_bersalin',
        'catatan_tambahan',
    ];

    protected $casts = [
        'rekomendasi_list' => 'array',
    ];

    public function skriningRisiko(): BelongsTo
    {
        return $this->belongsTo(SkriningRisiko::class);
    }

    /**
     * Generate rekomendasi based on kategori and skor
     */
    public static function generateRekomendasi(int $totalSkor, string $kategoriRisiko): array
    {
        if ($totalSkor <= 2) {
            return [
                'kategori_risiko' => 'KRR',
                'rekomendasi_umum' => 'Kehamilan Anda termasuk risiko rendah. Tetap jaga kesehatan dengan pola hidup sehat dan pemeriksaan rutin.',
                'rekomendasi_list' => [
                    'Ibu dapat melahirkan di Puskesmas atau Polindes',
                    'Lakukan pemeriksaan kehamilan rutin minimal 4 kali (1-1-2)',
                    'Konsumsi tablet tambah darah dan vitamin sesuai anjuran',
                    'Jaga pola makan bergizi seimbang',
                    'Istirahat yang cukup dan hindari stress',
                    'Persiapkan persalinan dengan baik'
                ],
                'tempat_bersalin' => 'Puskesmas atau Polindes'
            ];
        } elseif ($totalSkor >= 3 && $totalSkor <= 6) {
            return [
                'kategori_risiko' => 'KRT',
                'rekomendasi_umum' => 'Kehamilan Anda memerlukan perhatian khusus. Diperlukan pemeriksaan dan monitoring lebih intensif.',
                'rekomendasi_list' => [
                    'Ibu perlu melahirkan di Puskesmas PONED atau Rumah Sakit',
                    'Diperlukan pemeriksaan lebih intensif oleh tenaga kesehatan',
                    'Konsultasi dengan dokter spesialis kandungan',
                    'Perhatikan tanda-tanda bahaya kehamilan',
                    'Siapkan donor darah dan transportasi darurat',
                    'Pertimbangkan untuk tinggal dekat fasilitas kesehatan menjelang persalinan'
                ],
                'tempat_bersalin' => 'Puskesmas PONED atau Rumah Sakit'
            ];
        } else {
            return [
                'kategori_risiko' => 'KRST',
                'rekomendasi_umum' => 'PERHATIAN! Kehamilan Anda termasuk risiko sangat tinggi. Segera konsultasi dengan dokter dan persiapkan persalinan di Rumah Sakit.',
                'rekomendasi_list' => [
                    'IBU HARUS melahirkan di Rumah Sakit',
                    'Segera konsultasi dengan dokter spesialis kandungan',
                    'Pemeriksaan dan monitoring ketat sangat diperlukan',
                    'Persiapkan biaya, donor darah, dan transportasi darurat',
                    'Jika terjadi tanda bahaya segera ke Rumah Sakit',
                    'Pertimbangkan rawat inap jika diperlukan',
                    'Keluarga harus siap mendampingi kapan saja'
                ],
                'tempat_bersalin' => 'Rumah Sakit'
            ];
        }
    }
}
