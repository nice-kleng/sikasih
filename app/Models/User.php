<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
        'foto',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */

    // Relasi ke Puskesmas (jika user adalah admin puskesmas)
    public function puskesmas()
    {
        return $this->hasOne(Puskesmas::class);
    }

    // Relasi ke Tenaga Kesehatan (jika user adalah bidan/dokter)
    public function tenagaKesehatan()
    {
        return $this->hasOne(TenagaKesehatan::class);
    }

    // Relasi ke Ibu Hamil (jika user adalah ibu hamil)
    public function ibuHamil()
    {
        return $this->hasOne(IbuHamil::class);
    }

    // Relasi ke Artikel yang ditulis
    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'penulis_id');
    }

    /**
     * Helper Methods
     */

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isPuskesmas()
    {
        return $this->hasRole('puskesmas');
    }

    public function isIbuHamil()
    {
        return $this->hasRole('ibu_hamil');
    }

    public function isTenagaKesehatan()
    {
        return $this->tenagaKesehatan()->exists();
    }

    // Get full name dengan gelar (untuk tenaga kesehatan)
    public function getNamaLengkapAttribute()
    {
        if ($this->tenagaKesehatan) {
            return $this->tenagaKesehatan->nama_dengan_gelar;
        }
        return $this->nama;
    }
}
