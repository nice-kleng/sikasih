<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenagaKesehatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenaga_kesehatan';

    protected $fillable = [
        'user_id',
        'puskesmas_id',
        'nip',
        'str',
        'jenis_tenaga',
        'spesialisasi',
        'pendidikan_terakhir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'tanggal_mulai_kerja',
        'status_kepegawaian',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_mulai_kerja' => 'date',
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

    public function scopeBidan($query)
    {
        return $query->where('jenis_tenaga', 'bidan');
    }

    public function scopeDokter($query)
    {
        return $query->where('jenis_tenaga', 'dokter');
    }

    public function scopeDiPuskesmas($query, $puskesmasId)
    {
        return $query->where('puskesmas_id', $puskesmasId);
    }

    /**
     * Accessors
     */

    public function getNamaDenganGelarAttribute()
    {
        $gelar = '';

        if ($this->jenis_tenaga === 'bidan') {
            $gelar = ', S.ST'; // Sesuaikan dengan gelar yang sesuai
        } elseif ($this->jenis_tenaga === 'dokter') {
            $gelar = ', dr.';
        } elseif ($this->jenis_tenaga === 'dokter_spesialis') {
            $gelar = ', dr. ' . $this->spesialisasi;
        }

        return $this->user->nama . $gelar;
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age ?? 0;
    }

    public function getLamaKerjaAttribute()
    {
        return $this->tanggal_mulai_kerja->diffInYears(now()) ?? 0;
    }

    public function getJumlahPasienAttribute()
    {
        return $this->pemeriksaanAnc()->distinct('ibu_hamil_id')->count('ibu_hamil_id');
    }

    public function getJumlahKonsultasiSelesaiAttribute()
    {
        return $this->konsultasi()->where('status', 'selesai')->count();
    }
}
