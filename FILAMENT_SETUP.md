# 🎨 PANDUAN SETUP LARAVEL FILAMENT - SIKASIH

## 📦 Step 1: Install Laravel Filament

```bash
composer require filament/filament:"^3.2" -W
```

## 🔧 Step 2: Install Filament Panel

```bash
# Install panel builder
php artisan filament:install --panels

# Saat ditanya panel name, ketik: admin
# Saat ditanya want to create a user, pilih: yes
# Buat user superadmin dengan email dan password
```

## 🏗️ Step 3: Buat Panel Kedua untuk Puskesmas

```bash
php artisan make:filament-panel puskesmas
```

## 📝 Step 4: Konfigurasi Panel

### Edit `app/Providers/Filament/AdminPanelProvider.php`

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Pink,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->brandName('SIKASIH - Superadmin')
            ->brandLogo(asset('images/logo.png'))
            ->favicon(asset('images/favicon.png'))
            ->navigationGroups([
                'Master Data',
                'Data Pemeriksaan',
                'Konten & Edukasi',
                'Laporan',
                'Pengaturan',
            ]);
    }
}
```

### Edit `app/Providers/Filament/PuskesmasPanelProvider.php`

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PuskesmasPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('puskesmas')
            ->path('puskesmas')
            ->login()
            ->colors([
                'primary' => Color::Pink,
            ])
            ->discoverResources(in: app_path('Filament/Puskesmas/Resources'), for: 'App\\Filament\\Puskesmas\\Resources')
            ->discoverPages(in: app_path('Filament/Puskesmas/Pages'), for: 'App\\Filament\\Puskesmas\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Puskesmas/Widgets'), for: 'App\\Filament\\Puskesmas\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->brandName('SIKASIH - Puskesmas')
            ->brandLogo(asset('images/logo.png'))
            ->favicon(asset('images/favicon.png'))
            ->navigationGroups([
                'Data Utama',
                'Pemeriksaan & Skrining',
                'Konsultasi',
                'Laporan',
            ]);
    }
}
```

## 🔐 Step 5: Setup Middleware untuk Role-Based Access

Buat middleware baru:

```bash
php artisan make:middleware EnsureSuperadmin
php artisan make:middleware EnsurePuskesmas
```

### `app/Http/Middleware/EnsureSuperadmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized - Superadmin access only');
        }

        return $next($request);
    }
}
```

### `app/Http/Middleware/EnsurePuskesmas.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePuskesmas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['puskesmas', 'tenaga_kesehatan'])) {
            abort(403, 'Unauthorized - Puskesmas access only');
        }

        return $next($request);
    }
}
```

### Register Middleware di `bootstrap/app.php` (Laravel 11)

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'superadmin' => \App\Http\Middleware\EnsureSuperadmin::class,
        'puskesmas' => \App\Http\Middleware\EnsurePuskesmas::class,
    ]);
})
```

## 🎯 Step 6: Update Panel dengan Middleware

### Update AdminPanelProvider

```php
->authMiddleware([
    Authenticate::class,
    \App\Http\Middleware\EnsureSuperadmin::class,
])
```

### Update PuskesmasPanelProvider

```php
->authMiddleware([
    Authenticate::class,
    \App\Http\Middleware\EnsurePuskesmas::class,
])
```

## 📁 Struktur Folder yang Akan Dibuat

```
app/Filament/
├── Admin/                          # Panel Superadmin
│   ├── Resources/
│   │   ├── PuskesmasResource.php
│   │   ├── UserResource.php
│   │   ├── TenagaKesehatanResource.php
│   │   ├── IbuHamilResource.php
│   │   ├── PemeriksaanAncResource.php
│   │   ├── SkriningRisikoResource.php
│   │   ├── HasilLaboratoriumResource.php
│   │   ├── KonsultasiResource.php
│   │   ├── ArtikelResource.php
│   │   └── VideoEdukasiResource.php
│   ├── Pages/
│   │   └── Dashboard.php
│   └── Widgets/
│       ├── StatsOverview.php
│       ├── PuskesmasChart.php
│       └── IbuHamilChart.php
│
└── Puskesmas/                     # Panel Puskesmas
    ├── Resources/
    │   ├── TenagaKesehatanResource.php
    │   ├── IbuHamilResource.php
    │   ├── PemeriksaanAncResource.php
    │   ├── SkriningRisikoResource.php
    │   ├── HasilLaboratoriumResource.php
    │   └── KonsultasiResource.php
    ├── Pages/
    │   ├── Dashboard.php
    │   └── ProfilPuskesmas.php
    └── Widgets/
        ├── StatsOverview.php
        ├── IbuHamilTrimesterChart.php
        └── PemeriksaanBulananChart.php
```

## 🚀 Step 7: Generate Resources

### Untuk Panel Admin (Superadmin)

```bash
# Master Data
php artisan make:filament-resource Puskesmas --generate --panel=admin
php artisan make:filament-resource User --generate --panel=admin
php artisan make:filament-resource TenagaKesehatan --generate --panel=admin
php artisan make:filament-resource IbuHamil --generate --panel=admin

# Data Pemeriksaan
php artisan make:filament-resource PemeriksaanAnc --generate --panel=admin
php artisan make:filament-resource SkriningRisiko --generate --panel=admin
php artisan make:filament-resource HasilLaboratorium --generate --panel=admin
php artisan make:filament-resource Konsultasi --generate --panel=admin

# Konten
php artisan make:filament-resource Artikel --generate --panel=admin
php artisan make:filament-resource VideoEdukasi --generate --panel=admin
```

### Untuk Panel Puskesmas

```bash
php artisan make:filament-resource TenagaKesehatan --generate --panel=puskesmas
php artisan make:filament-resource IbuHamil --generate --panel=puskesmas
php artisan make:filament-resource PemeriksaanAnc --generate --panel=puskesmas
php artisan make:filament-resource SkriningRisiko --generate --panel=puskesmas
php artisan make:filament-resource HasilLaboratorium --generate --panel=puskesmas
php artisan make:filament-resource Konsultasi --generate --panel=puskesmas
```

## 📊 Step 8: Generate Widgets

### Admin Widgets

```bash
php artisan make:filament-widget StatsOverview --panel=admin
php artisan make:filament-widget PuskesmasChart --panel=admin
php artisan make:filament-widget IbuHamilChart --panel=admin
```

### Puskesmas Widgets

```bash
php artisan make:filament-widget StatsOverview --panel=puskesmas
php artisan make:filament-widget IbuHamilTrimesterChart --panel=puskesmas
php artisan make:filament-widget PemeriksaanBulananChart --panel=puskesmas
```

## ✅ Checklist Setup

- [ ] Install Filament
- [ ] Create Admin Panel
- [ ] Create Puskesmas Panel
- [ ] Configure both panels
- [ ] Create middleware
- [ ] Register middleware
- [ ] Generate all resources
- [ ] Generate all widgets
- [ ] Test login for both panels
- [ ] Customize resources
- [ ] Add policies

## 🔗 URLs

```
Superadmin Panel: http://localhost:8000/admin
Puskesmas Panel: http://localhost:8000/puskesmas

Login:
- Superadmin: admin@sikasih.id / password
- Puskesmas: puskesmas@sikasih.id / password
```

## 📝 Next Steps

Setelah setup dasar selesai, kita akan:
1. Customize setiap Resource dengan form dan table yang sesuai
2. Implement scoping data per puskesmas
3. Add custom dashboard widgets
4. Create policies untuk authorization
5. Add custom actions dan bulk actions
6. Implement laporan dan export

Lanjut ke file berikutnya untuk detail implementasi! 🚀
