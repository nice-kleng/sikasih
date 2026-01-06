<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasPanelShield;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telepon',
        'foto',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if user can access Filament panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Super admin can access all panels
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // Admin panel - only super_admin
        if ($panel->getId() === 'admin') {
            return $this->hasRole('super_admin');
        }

        // Puskesmas panel - puskesmas & tenaga_kesehatan
        if ($panel->getId() === 'puskesmas') {
            return $this->hasAnyRole(['puskesmas', 'tenaga_kesehatan']);
        }

        return false;
    }

    /**
     * Relationships
     */
    public function puskesmas()
    {
        return $this->hasOne(\App\Models\Puskesmas::class);
    }

    public function tenagaKesehatan()
    {
        return $this->hasOne(\App\Models\TenagaKesehatan::class);
    }

    public function ibuHamil()
    {
        return $this->hasOne(\App\Models\IbuHamil::class);
    }

    public function artikel()
    {
        return $this->hasMany(\App\Models\Artikel::class, 'penulis_id');
    }

    /**
     * Helper Methods
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isPuskesmas(): bool
    {
        return $this->hasRole('puskesmas');
    }

    public function isIbuHamil(): bool
    {
        return $this->hasRole('ibu_hamil');
    }

    public function isTenagaKesehatan(): bool
    {
        return $this->hasRole('tenaga_kesehatan');
    }

    /**
     * Get nama lengkap dengan gelar (jika tenaga kesehatan)
     */
    public function getNamaLengkapAttribute(): string
    {
        if ($this->tenagaKesehatan) {
            $gelar = match ($this->tenagaKesehatan->jenis_tenaga) {
                'bidan' => 'Bd.',
                'dokter' => 'dr.',
                'dokter_spesialis' => 'dr. ' . $this->tenagaKesehatan->spesialisasi,
                'perawat' => 'Ns.',
                default => '',
            };
            return $gelar . ' ' . $this->nama;
        }

        return $this->nama;
    }
}
