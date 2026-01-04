<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konsultasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'konsultasi';

    protected $fillable = [
        'ibu_hamil_id',
        'tenaga_kesehatan_id',
        'puskesmas_id',
        'no_konsultasi',
        'tanggal_konsultasi',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'usia_kehamilan_minggu',
        'topik_konsultasi',
        'kategori',
        'metode',
        'keluhan_ibu',
        'jawaban_konsultasi',
        'saran',
        'tindak_lanjut',
        'catatan',
        'status',
        'prioritas',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'tanggal_konsultasi' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Relationships
     */

    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class);
    }

    public function tenagaKesehatan()
    {
        return $this->belongsTo(TenagaKesehatan::class);
    }

    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function pesanKonsultasi()
    {
        return $this->hasMany(PesanKonsultasi::class);
    }

    /**
     * Scopes
     */

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'berlangsung');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDarurat($query)
    {
        return $query->where('prioritas', 'darurat');
    }

    /**
     * Accessors
     */

    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'menunggu' => 'warning',
            'berlangsung' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            default => 'secondary'
        };
    }

    public function getPrioritasBadgeColorAttribute()
    {
        return match ($this->prioritas) {
            'darurat' => 'danger',
            'tinggi' => 'warning',
            'sedang' => 'info',
            'rendah' => 'secondary',
            default => 'secondary'
        };
    }
}
