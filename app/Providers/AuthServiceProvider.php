<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\PemeriksaanAnc;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register custom gates for complex rules
        Gate::define('update_pemeriksaan_within_24h', function (User $user, PemeriksaanAnc $pemeriksaan) {
            // Super admin always can
            if ($user->hasRole('super_admin')) {
                return true;
            }

            // Puskesmas can update anytime
            if ($user->puskesmas && $pemeriksaan->puskesmas_id === $user->puskesmas->id) {
                return true;
            }

            // Tenaga kesehatan only within 24 hours
            if ($user->tenagaKesehatan) {
                $isPemeriksa = $pemeriksaan->tenaga_kesehatan_id === $user->tenagaKesehatan->id;
                $isRecent = $pemeriksaan->created_at->diffInHours(now()) < 24;
                $isSamePuskesmas = $pemeriksaan->puskesmas_id === $user->tenagaKesehatan->puskesmas_id;

                return $isPemeriksa && $isRecent && $isSamePuskesmas;
            }

            return false;
        });
    }
}
