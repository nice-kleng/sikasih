<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Puskesmas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'puskesmas';

    protected $fillable = [
        'user_id',
        'kode_puskesmas',
        'nama_puskesmas',
        'alamat',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'no_telepon',
        'email',
        'kepala_puskesmas',
        'fasilitas',
        'tipe',
        'status',
    ];

    protected $casts = [
        'fasilitas' => 'array',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenagaKesehatan()
    {
        return $this->hasMany(TenagaKesehatan::class);
    }

    public function ibuHamil()
    {
        return $this->hasMany(IbuHamil::class);
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

    public function scopePoned($query)
    {
        return $query->where('tipe', 'poned');
    }

    /**
     * Accessors
     */

    public function getAlamatLengkapAttribute()
    {
        return "{$this->alamat}, {$this->kecamatan}, {$this->kabupaten}, {$this->provinsi}";
    }

    public function getJumlahTenagaKesehatanAttribute()
    {
        return $this->tenagaKesehatan()->count();
    }

    public function getJumlahIbuHamilAktifAttribute()
    {
        return $this->ibuHamil()->where('status_kehamilan', 'hamil')->count();
    }
}
