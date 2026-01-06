# 🛡️ FILAMENT SHIELD V3 - COMPLETE INSTALLATION GUIDE

## 🎯 Tujuan Migration

Mengganti manual Policy dengan Filament Shield v3 untuk authorization yang lebih powerful dan maintainable.

---

## 📦 STEP 1: INSTALLATION

### Install Package

```bash
composer require bezhansalleh/filament-shield:"^3.0"
```

### Publish Config

```bash
php artisan vendor:publish --tag=filament-shield-config
```

### Install Shield

```bash
php artisan shield:install --fresh

# Pilih:
# - Panel: admin
# - Generate permissions? yes
# - Generate super_admin? yes
```

### Generate All Permissions

```bash
# Generate permissions untuk semua resources
php artisan shield:generate --all

# Atau generate per resource:
php artisan shield:generate --resource=PuskesmasResource
php artisan shield:generate --resource=IbuHamilResource
php artisan shield:generate --resource=TenagaKesehatanResource
php artisan shield:generate --resource=PemeriksaanAncResource
php artisan shield:generate --resource=SkriningRisikoResource
php artisan shield:generate --resource=ArtikelResource
php artisan shield:generate --resource=VideoEdukasiResource
```

---

## ⚙️ STEP 2: CONFIGURATION

### Edit `config/filament-shield.php`

```php
<?php

return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => -1,
        'navigation_badge' => true,
        'navigation_group' => 'Pengaturan',
        'is_globally_searchable' => false,
        'show_model_path' => true,
        'is_scoped_to_tenant' => false,
    ],

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => true,
        'name' => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // before | after
    ],

    'filament_user' => [
        'enabled' => true,
        'name' => 'filament_user',
    ],

    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => true,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
    ],

    'exclude' => [
        'enabled' => true,

        'pages' => [
            'Dashboard',
        ],

        'widgets' => [
            'AccountWidget',
            'FilamentInfoWidget',
        ],

        'resources' => [],
    ],

    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets' => false,
        'discover_all_pages' => false,
    ],

    'register_role_policy' => [
        'enabled' => true,
    ],
];
```

---

## 🗑️ STEP 3: DELETE OLD POLICY FILES

```bash
# Delete manual policy files
rm app/Policies/IbuHamilPolicy.php
rm app/Policies/PemeriksaanAncPolicy.php
rm app/Policies/PuskesmasPolicy.php

# Update AuthServiceProvider - remove policy registrations
```

### Edit `app/Providers/AuthServiceProvider.php`

**SEBELUM:**

```php
protected $policies = [
    \App\Models\IbuHamil::class => \App\Policies\IbuHamilPolicy::class,
    \App\Models\PemeriksaanAnc::class => \App\Policies\PemeriksaanAncPolicy::class,
    \App\Models\Puskesmas::class => \App\Policies\PuskesmasPolicy::class,
];
```

**SESUDAH:**

```php
protected $policies = [
    // Shield will auto-generate policies
];

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
```

---

## 🔄 STEP 4: UPDATE USER MODEL

### Edit `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield; // ADD THIS
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser // ADD INTERFACE
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasPanelShield; // ADD HasPanelShield

    protected $fillable = [
        'nama',
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

    // Filament User Implementation
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

    // Relationships
    public function puskesmas()
    {
        return $this->hasOne(Puskesmas::class);
    }

    public function tenagaKesehatan()
    {
        return $this->hasOne(TenagaKesehatan::class);
    }

    public function ibuHamil()
    {
        return $this->hasOne(IbuHamil::class);
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'penulis_id');
    }

    // Helper Methods
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

    public function getNamaLengkapAttribute(): string
    {
        if ($this->tenagaKesehatan) {
            $gelar = match($this->tenagaKesehatan->jenis_tenaga) {
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
```

---

## 📝 STEP 5: UPDATE ALL RESOURCES

### Template untuk semua Resources:

```php
<?php

namespace App\Filament\Admin\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions; // ADD THIS

class YourResource extends Resource implements HasShieldPermissions // ADD INTERFACE
{
    // ... existing code ...

    // ADD THIS METHOD
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ];
    }

    // KEEP data scoping logic
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Super admin sees all
        if (auth()->user()->hasRole('super_admin')) {
            return $query;
        }

        // Scope to puskesmas for other roles
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        if ($puskesmasId) {
            $query->where('puskesmas_id', $puskesmasId);
        }

        return $query;
    }
}
```

---

## 🔧 STEP 6: UPDATE SPECIFIC RESOURCES

### 1. IbuHamilResource (Admin)

File: `app/Filament/Admin/Resources/IbuHamilResource.php`

```php
<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IbuHamilResource\Pages;
use App\Models\IbuHamil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class IbuHamilResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = IbuHamil::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 4;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    // ... existing form() and table() methods ...

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIbuHamil::route('/'),
            'create' => Pages\CreateIbuHamil::route('/create'),
            'edit' => Pages\EditIbuHamil::route('/{record}/edit'),
            'view' => Pages\ViewIbuHamil::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_kehamilan', 'hamil')->count();
    }
}
```

### 2. PemeriksaanAncResource (Admin & Puskesmas)

File: `app/Filament/Admin/Resources/PemeriksaanAncResource.php`

```php
<?php

namespace App\Filament\Admin\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class PemeriksaanAncResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PemeriksaanAnc::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }

    // ... existing methods ...

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... existing columns ...
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(function (PemeriksaanAnc $record) {
                        // Use custom gate for 24h rule
                        return auth()->user()->can('update_pemeriksaan_within_24h', $record);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

### 3. Update ALL Other Resources

Apply same pattern untuk:

-   ✅ PuskesmasResource
-   ✅ TenagaKesehatanResource
-   ✅ SkriningRisikoResource
-   ✅ ArtikelResource
-   ✅ VideoEdukasiResource

Dan untuk panel Puskesmas:

-   ✅ IbuHamilResource (Puskesmas)
-   ✅ TenagaKesehatanResource (Puskesmas)
-   ✅ PemeriksaanAncResource (Puskesmas)
-   ✅ SkriningRisikoResource (Puskesmas)

---

## 🎨 STEP 7: UPDATE PANEL PROVIDERS

### AdminPanelProvider.php

```php
->authMiddleware([
    Authenticate::class,
    // REMOVE: \App\Http\Middleware\EnsureSuperadmin::class,
])
// Shield handles authorization automatically
```

### PuskesmasPanelProvider.php

```php
->authMiddleware([
    Authenticate::class,
    // REMOVE: \App\Http\Middleware\EnsurePuskesmas::class,
])
// Shield handles authorization automatically
```

**Note:** Middleware masih bisa dipakai untuk extra security, tapi Shield sudah cukup.

---

## 👥 STEP 8: CREATE ROLES & ASSIGN PERMISSIONS

### Via Artisan (Development)

```bash
# Create roles
php artisan shield:generate --all

# Create super admin user
php artisan shield:super-admin
# Email: admin@sikasih.id
# Name: Super Admin
# Password: password
```

### Via GUI (Production)

1. Login sebagai super_admin
2. Buka menu **Shield → Roles**
3. Create roles:

#### Role: `puskesmas`

**Permissions:**

-   ✅ view_any_ibu_hamil
-   ✅ view_ibu_hamil
-   ✅ create_ibu_hamil
-   ✅ update_ibu_hamil
-   ✅ delete_ibu_hamil
-   ✅ view_any_tenaga_kesehatan
-   ✅ view_tenaga_kesehatan
-   ✅ create_tenaga_kesehatan
-   ✅ update_tenaga_kesehatan
-   ✅ delete_tenaga_kesehatan
-   ✅ view_any_pemeriksaan_anc
-   ✅ view_pemeriksaan_anc
-   ✅ create_pemeriksaan_anc
-   ✅ update_pemeriksaan_anc
-   ✅ delete_pemeriksaan_anc
-   ✅ view_any_skrining_risiko
-   ✅ view_skrining_risiko
-   ✅ create_skrining_risiko
-   ✅ update_skrining_risiko
-   ✅ delete_skrining_risiko

#### Role: `tenaga_kesehatan`

**Permissions:**

-   ✅ view_any_ibu_hamil
-   ✅ view_ibu_hamil
-   ✅ create_ibu_hamil
-   ✅ update_ibu_hamil
-   ❌ delete_ibu_hamil (NO!)
-   ✅ view_any_tenaga_kesehatan
-   ✅ view_tenaga_kesehatan
-   ❌ create_tenaga_kesehatan (NO!)
-   ❌ update_tenaga_kesehatan (NO!)
-   ❌ delete_tenaga_kesehatan (NO!)
-   ✅ view_any_pemeriksaan_anc
-   ✅ view_pemeriksaan_anc
-   ✅ create_pemeriksaan_anc
-   ✅ update_pemeriksaan_anc (with 24h gate check)
-   ❌ delete_pemeriksaan_anc (NO!)
-   ✅ view_any_skrining_risiko
-   ✅ view_skrining_risiko
-   ✅ create_skrining_risiko
-   ✅ update_skrining_risiko
-   ❌ delete_skrining_risiko (NO!)

---

## 🔄 STEP 9: UPDATE DATABASE SEEDER

### Edit `database/seeders/DatabaseSeeder.php`

```php
public function run(): void
{
    // Create Super Admin role first
    $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

    // Create Super Admin user
    $superAdmin = User::create([
        'nama' => 'Super Admin',
        'email' => 'admin@sikasih.id',
        'password' => bcrypt('password'),
        'status' => 'aktif',
    ]);
    $superAdmin->assignRole('super_admin');

    // Create other roles
    $puskesmasRole = Role::firstOrCreate(['name' => 'puskesmas']);
    $tenagaKesehatanRole = Role::firstOrCreate(['name' => 'tenaga_kesehatan']);
    $ibuHamilRole = Role::firstOrCreate(['name' => 'ibu_hamil']);

    // Assign permissions to roles (Shield will handle this)

    // ... rest of seeder code ...
}
```

---

## ✅ STEP 10: TESTING

### Test Checklist:

```bash
# 1. Clear cache
php artisan optimize:clear
php artisan shield:generate --all

# 2. Test Super Admin
- Login: admin@sikasih.id
- Can access /admin
- Can CRUD all resources
- Can see Shield menu
- Can manage roles & permissions

# 3. Test Puskesmas Role
- Login: puskesmas@sikasih.id
- Can access /puskesmas
- Can CRUD ibu hamil (scoped)
- Can CRUD tenaga kesehatan (scoped)
- Can CRUD pemeriksaan (scoped)
- Can delete records

# 4. Test Tenaga Kesehatan Role
- Login: bidan.linda@sikasih.id
- Can access /puskesmas
- Can view/create ibu hamil
- CANNOT delete ibu hamil
- Can create pemeriksaan
- Can edit pemeriksaan (within 24h only)
- CANNOT delete pemeriksaan

# 5. Test Authorization
- Try accessing unauthorized resources → should get 403
- Try editing old pemeriksaan as nakes → should be hidden
- Try deleting as nakes → button should be hidden
```

---

## 🎯 SUMMARY

### What Changed:

-   ❌ **DELETED:** Manual Policy files
-   ❌ **REMOVED:** Manual policy registration
-   ❌ **REMOVED:** EnsureSuperadmin middleware (optional)
-   ❌ **REMOVED:** EnsurePuskesmas middleware (optional)

### What Added:

-   ✅ **ADDED:** Filament Shield package
-   ✅ **ADDED:** HasShieldPermissions interface to Resources
-   ✅ **ADDED:** HasPanelShield trait to User model
-   ✅ **ADDED:** FilamentUser interface to User model
-   ✅ **ADDED:** Custom gate for 24h rule
-   ✅ **ADDED:** Shield config
-   ✅ **ADDED:** Shield Resources (Role & Permission management)

### What Kept:

-   ✅ **KEPT:** Data scoping logic in getEloquentQuery()
-   ✅ **KEPT:** Custom business rules (24h edit)
-   ✅ **KEPT:** Spatie Permission package
-   ✅ **KEPT:** All existing features

### Benefits:

-   🎨 **GUI** for managing permissions
-   ⚡ **Faster** setup & maintenance
-   🔒 **More secure** with proper authorization
-   📊 **Better UX** for admins
-   🚀 **Production ready**

---

## 🚀 NEXT STEPS

1. Run all migration commands
2. Update all Resource files
3. Test thoroughly
4. Setup production roles via GUI
5. Deploy! 🎉
