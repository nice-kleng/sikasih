<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemeriksaanAnc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemeriksaan_anc';

    protected $fillable = [
        'ibu_hamil_id',
        'puskesmas_id',
        'tenaga_kesehatan_id',
        'no_pemeriksaan',
        'tanggal_pemeriksaan',
        'waktu_pemeriksaan',
        'kunjungan_ke',
        'usia_kehamilan_minggu',
        'berat_badan',
        'tinggi_badan',
        'tekanan_darah_sistol',
        'tekanan_darah_diastol',
        'suhu_tubuh',
        'nadi',
        'respirasi',
        'lila',
        'tinggi_fundus',
        'djj',
        'letak_janin',
        'presentasi',
        'taksiran_berat_janin',
        'hb',
        'golongan_darah',
        'protein_urin',
        'glukosa_urin',
        'hbsag',
        'hiv',
        'sifilis',
        'keluhan',
        'riwayat_penyakit',
        'riwayat_alergi',
        'diagnosis',
        'tindakan',
        'terapi_obat',
        'edukasi',
        'catatan',
        'jadwal_kunjungan_berikutnya',
        'status_pemeriksaan',
        'rujukan_ke',
        'alasan_rujukan',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'waktu_pemeriksaan' => 'datetime',
        'jadwal_kunjungan_berikutnya' => 'date',
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

    public function hasilLaboratorium()
    {
        return $this->hasMany(HasilLaboratorium::class);
    }

    /**
     * Scopes
     */

    public function scopeDiPuskesmas($query, $puskesmasId)
    {
        return $query->where('puskesmas_id', $puskesmasId);
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal_pemeriksaan', now()->year);
    }

    /**
     * Accessors
     */

    public function getTekananDarahAttribute()
    {
        return "{$this->tekanan_darah_sistol}/{$this->tekanan_darah_diastol} mmHg";
    }

    public function getStatusTekananDarahAttribute()
    {
        if ($this->tekanan_darah_sistol >= 140 || $this->tekanan_darah_diastol >= 90) {
            return 'tinggi';
        } elseif ($this->tekanan_darah_sistol < 90 || $this->tekanan_darah_diastol < 60) {
            return 'rendah';
        }
        return 'normal';
    }

    public function getStatusHbAttribute()
    {
        if (!$this->hb) {
            return null;
        }

        if ($this->hb < 11) {
            return 'anemia';
        } elseif ($this->hb < 12) {
            return 'ringan';
        }
        return 'normal';
    }

    public function getStatusDjjAttribute()
    {
        if (!$this->djj) {
            return null;
        }

        if ($this->djj < 120 || $this->djj > 160) {
            return 'abnormal';
        }
        return 'normal';
    }

    public function getTrimesterAttribute()
    {
        if ($this->usia_kehamilan_minggu <= 13) {
            return 1;
        } elseif ($this->usia_kehamilan_minggu <= 27) {
            return 2;
        }
        return 3;
    }

    public function getKenaikanBeratBadanAttribute()
    {
        $pemeriksaanSebelumnya = PemeriksaanAnc::where('ibu_hamil_id', $this->ibu_hamil_id)
            ->where('tanggal_pemeriksaan', '<', $this->tanggal_pemeriksaan)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        if (!$pemeriksaanSebelumnya) {
            return $this->berat_badan - $this->ibuHamil->berat_badan_awal;
        }

        return $this->berat_badan - $pemeriksaanSebelumnya->berat_badan;
    }
}
