<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class IbuHamil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ibu_hamil';

    protected $fillable = [
        'user_id',
        'puskesmas_id',
        'no_rm',
        'nik',
        'nama_lengkap',
        'tanggal_lahir',
        'umur',
        'alamat_lengkap',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'golongan_darah',
        'pendidikan_terakhir',
        'pekerjaan',
        'nama_suami',
        'umur_suami',
        'pekerjaan_suami',
        'pendidikan_suami',
        'gravida',
        'para',
        'abortus',
        'anak_hidup',
        'usia_menikah',
        'usia_hamil_pertama',
        'riwayat_persalinan',
        'jarak_kehamilan_terakhir',
        'riwayat_komplikasi',
        'memiliki_bpjs',
        'no_bpjs',
        'hpht',
        'hpl',
        'usia_kehamilan_minggu',
        'berat_badan_awal',
        'tinggi_badan',
        'status_kehamilan',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'hpht' => 'date',
        'hpl' => 'date',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function pemeriksaanAnc()
    {
        return $this->hasMany(PemeriksaanAnc::class);
    }

    public function skriningRisiko()
    {
        return $this->hasMany(SkriningRisiko::class);
    }

    public function hasilLaboratorium()
    {
        return $this->hasMany(HasilLaboratorium::class);
    }

    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class);
    }

    /**
     * Scopes
     */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeHamil($query)
    {
        return $query->where('status_kehamilan', 'hamil');
    }

    public function scopeDiPuskesmas($query, $puskesmasId)
    {
        return $query->where('puskesmas_id', $puskesmasId);
    }

    public function scopeTrimester($query, $trimester)
    {
        if ($trimester == 1) {
            return $query->whereBetween('usia_kehamilan_minggu', [0, 13]);
        } elseif ($trimester == 2) {
            return $query->whereBetween('usia_kehamilan_minggu', [14, 27]);
        } elseif ($trimester == 3) {
            return $query->whereBetween('usia_kehamilan_minggu', [28, 42]);
        }
        return $query;
    }

    /**
     * Accessors & Mutators
     */

    public function getStatusObstetriAttribute()
    {
        return "G{$this->gravida}P{$this->para}A{$this->anak_hidup}Ab{$this->abortus}";
    }

    public function getAlamatLengkapFormatAttribute()
    {
        $alamat = $this->alamat_lengkap;
        if ($this->rt && $this->rw) {
            $alamat .= " RT {$this->rt} RW {$this->rw}";
        }
        $alamat .= ", {$this->kelurahan}, {$this->kecamatan}, {$this->kabupaten}";
        return $alamat;
    }

    public function getUsiaKehamilan()
    {
        if (!$this->hpht) {
            return null;
        }

        $hpht = Carbon::parse($this->hpht);
        $sekarang = Carbon::now();
        $selisihHari = $hpht->diffInDays($sekarang);
        $minggu = floor($selisihHari / 7);
        $hari = $selisihHari % 7;

        return [
            'minggu' => $minggu,
            'hari' => $hari,
            'format' => "{$minggu} minggu {$hari} hari"
        ];
    }

    public function getTrimesterAttribute()
    {
        $minggu = $this->usia_kehamilan_minggu;

        if ($minggu <= 13) {
            return 1;
        } elseif ($minggu <= 27) {
            return 2;
        } else {
            return 3;
        }
    }

    public function getJumlahAncAttribute()
    {
        return $this->pemeriksaanAnc()->count();
    }

    public function getSkriningRisikoTerbaruAttribute()
    {
        return $this->skriningRisiko()->latest('tanggal_skrining')->first();
    }

    public function getPemeriksaanAncTerbaruAttribute()
    {
        return $this->pemeriksaanAnc()->latest('tanggal_pemeriksaan')->first();
    }

    public function getBeratBadanTerbaruAttribute()
    {
        $pemeriksaan = $this->pemeriksaanAncTerbaru;
        return $pemeriksaan ? $pemeriksaan->berat_badan : $this->berat_badan_awal;
    }

    public function getKenaikanBeratBadanAttribute()
    {
        if (!$this->berat_badan_awal || !$this->beratBadanTerbaru) {
            return 0;
        }
        return $this->beratBadanTerbaru - $this->berat_badan_awal;
    }

    public function getImt()
    {
        if (!$this->berat_badan_awal || !$this->tinggi_badan) {
            return null;
        }

        $tinggiMeter = $this->tinggi_badan / 100;
        $imt = $this->berat_badan_awal / ($tinggiMeter * $tinggiMeter);

        return round($imt, 2);
    }

    public function getKategoriImtAttribute()
    {
        $imt = $this->getImt();

        if (!$imt) {
            return 'Tidak Diketahui';
        }

        if ($imt < 18.5) {
            return 'Kurus';
        } elseif ($imt < 25) {
            return 'Normal';
        } elseif ($imt < 30) {
            return 'Gemuk';
        } else {
            return 'Obesitas';
        }
    }
}
