# 🎯 PANDUAN LENGKAP IMPLEMENTASI FILAMENT - SIKASIH

## 📋 Daftar File yang Sudah Dibuat

### 🔴 Panel Admin (Superadmin)

#### Resources:

1. ✅ `PuskesmasResource.php` - CRUD Puskesmas dengan user management
2. ✅ `IbuHamilResource.php` - View all ibu hamil dari semua puskesmas
3. ✅ `SkriningRisikoResource.php` - Skrining risiko dengan auto calculate
4. ✅ `ArtikelResource.php` - Manajemen artikel edukasi
5. ✅ `VideoEdukasiResource.php` - Manajemen video YouTube

#### Widgets:

1. ✅ `StatsOverview.php` - 7 statistik cards
2. ✅ `IbuHamilChart.php` - Chart pendaftaran 6 bulan terakhir
3. ✅ `RisikoKehamilanChart.php` - Pie chart distribusi risiko

### 🟢 Panel Puskesmas

#### Resources:

1. ✅ `IbuHamilResource.php` - CRUD ibu hamil (scoped ke puskesmas sendiri)

#### Widgets:

1. ✅ `StatsOverview.php` - 7 statistik cards (scoped data)

## 🚀 Langkah Implementasi

### Step 1: Install Dependencies

```bash
# Install Filament
composer require filament/filament:"^3.2" -W

# Install Flowframe Trend (untuk chart)
composer require flowframe/laravel-trend
```

### Step 2: Setup Filament Panels

```bash
# Install panel admin
php artisan filament:install --panels

# Buat panel puskesmas
php artisan make:filament-panel puskesmas
```

### Step 3: Copy Files

Copy semua file dari folder yang sudah dibuat ke project Laravel Anda:

```
Source → Destination

filament/
├── FILAMENT_SETUP.md → (baca untuk panduan)
├── Admin/
│   ├── Resources/
│   │   ├── PuskesmasResource.php → app/Filament/Admin/Resources/
│   │   ├── IbuHamilResource.php → app/Filament/Admin/Resources/
│   │   ├── SkriningRisikoResource.php → app/Filament/Admin/Resources/
│   │   ├── ArtikelResource.php → app/Filament/Admin/Resources/
│   │   └── VideoEdukasiResource.php → app/Filament/Admin/Resources/
│   └── Widgets/
│       ├── StatsOverview.php → app/Filament/Admin/Widgets/
│       ├── IbuHamilChart.php → app/Filament/Admin/Widgets/
│       └── RisikoKehamilanChart.php → app/Filament/Admin/Widgets/
└── Puskesmas/
    ├── Resources/
    │   └── IbuHamilResource.php → app/Filament/Puskesmas/Resources/
    └── Widgets/
        └── StatsOverview.php → app/Filament/Puskesmas/Widgets/
```

### Step 4: Generate Resource Pages

Setelah copy Resource files, generate pages untuk setiap resource:

```bash
# Admin Panel
php artisan make:filament-pages --resource=PuskesmasResource --panel=admin
php artisan make:filament-pages --resource=IbuHamilResource --panel=admin
php artisan make:filament-pages --resource=SkriningRisikoResource --panel=admin
php artisan make:filament-pages --resource=ArtikelResource --panel=admin
php artisan make:filament-pages --resource=VideoEdukasiResource --panel=admin

# Puskesmas Panel
php artisan make:filament-pages --resource=IbuHamilResource --panel=puskesmas
```

### Step 5: Create Middleware

```bash
php artisan make:middleware EnsureSuperadmin
php artisan make:middleware EnsurePuskesmas
```

Copy isi middleware dari `FILAMENT_SETUP.md`

### Step 6: Register Middleware

Di `bootstrap/app.php` (Laravel 11):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'superadmin' => \App\Http\Middleware\EnsureSuperadmin::class,
        'puskesmas' => \App\Http\Middleware\EnsurePuskesmas::class,
    ]);
})
```

### Step 7: Update Panel Providers

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
use App\Filament\Admin\Widgets;

public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->login()
        ->colors([
            'primary' => Color::Pink,
        ])
        ->brandName('SIKASIH - Superadmin')
        ->navigationGroups([
            'Master Data',
            'Data Pemeriksaan',
            'Konten & Edukasi',
            'Laporan',
        ])
        ->widgets([
            Widgets\StatsOverview::class,
            Widgets\IbuHamilChart::class,
            Widgets\RisikoKehamilanChart::class,
        ])
        ->authMiddleware([
            Authenticate::class,
            \App\Http\Middleware\EnsureSuperadmin::class,
        ]);
}
```

Edit `app/Providers/Filament/PuskesmasPanelProvider.php`:

```php
use App\Filament\Puskesmas\Widgets;

public function panel(Panel $panel): Panel
{
    return $panel
        ->id('puskesmas')
        ->path('puskesmas')
        ->login()
        ->colors([
            'primary' => Color::Pink,
        ])
        ->brandName('SIKASIH - Puskesmas')
        ->navigationGroups([
            'Data Utama',
            'Pemeriksaan & Skrining',
            'Konsultasi',
            'Laporan',
        ])
        ->widgets([
            Widgets\StatsOverview::class,
        ])
        ->authMiddleware([
            Authenticate::class,
            \App\Http\Middleware\EnsurePuskesmas::class,
        ]);
}
```

### Step 8: Create YouTube Preview Component (Optional)

Buat file `resources/views/filament/forms/components/youtube-preview.blade.php`:

```blade
<div class="youtube-preview">
    @if($getRecord() && $getRecord()->youtube_id)
        <div class="rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700">
            <iframe
                width="100%"
                height="315"
                src="https://www.youtube.com/embed/{{ $getRecord()->youtube_id }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
    @endif
</div>
```

## ✅ Testing

### Test Login

```bash
# Jalankan server
php artisan serve

# Buka browser:
http://localhost:8000/admin
http://localhost:8000/puskesmas

# Login credentials (dari seeder):
Superadmin: admin@sikasih.id / password
Puskesmas: puskesmas@sikasih.id / password
```

### Checklist Testing

- [ ] Login ke panel admin berhasil
- [ ] Login ke panel puskesmas berhasil
- [ ] Dashboard admin menampilkan widget
- [ ] Dashboard puskesmas menampilkan widget
- [ ] CRUD Puskesmas di admin berfungsi
- [ ] CRUD Ibu Hamil di admin berfungsi (view all data)
- [ ] CRUD Ibu Hamil di puskesmas berfungsi (only scoped data)
- [ ] Skrining risiko auto calculate berfungsi
- [ ] Artikel CRUD dan publish/unpublish berfungsi
- [ ] Video edukasi CRUD dan preview YouTube berfungsi
- [ ] Navigation badge menampilkan count yang benar
- [ ] Filter dan search berfungsi
- [ ] Export berfungsi

## 🎨 Fitur Unggulan yang Sudah Diimplementasi

### 1. **Data Scoping Otomatis**

Panel Puskesmas hanya menampilkan data puskesmas sendiri:

```php
public static function getEloquentQuery(): Builder
{
    $user = auth()->user();
    if ($user->puskesmas) {
        return parent::getEloquentQuery()
            ->where('puskesmas_id', $user->puskesmas->id);
    }
    return parent::getEloquentQuery();
}
```

### 2. **Auto Generate No. RM**

```php
Forms\Components\TextInput::make('no_rm')
    ->default(function () {
        $lastRM = IbuHamil::max('no_rm');
        $number = $lastRM ? ((int) substr($lastRM, -4)) + 1 : 1;
        return 'RM-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    })
```

### 3. **Auto Calculate Usia Kehamilan**

```php
Forms\Components\DatePicker::make('hpht')
    ->reactive()
    ->afterStateUpdated(function ($state, Forms\Set $set) {
        if ($state) {
            $hpl = \Carbon\Carbon::parse($state)->addDays(280);
            $set('hpl', $hpl->format('Y-m-d'));

            $usiaKehamilan = floor(\Carbon\Carbon::parse($state)->diffInDays(now()) / 7);
            $set('usia_kehamilan_minggu', $usiaKehamilan);
        }
    })
```

### 4. **Skrining Risiko dengan Auto Calculate**

Form reaktif yang otomatis menghitung skor berdasarkan checkbox yang dipilih.

### 5. **Publish/Unpublish Artikel**

```php
Tables\Actions\Action::make('publish')
    ->action(fn (Artikel $record) => $record->publish())
    ->visible(fn (Artikel $record) => $record->status !== 'published')
```

### 6. **YouTube Auto Extract ID**

```php
->afterStateUpdated(function ($state, Forms\Set $set) {
    if (Str::contains($state, 'youtube.com')) {
        preg_match('/[?&]v=([^&]+)/', $state, $matches);
        if (isset($matches[1])) {
            $set('youtube_id', $matches[1]);
        }
    }
})
```

### 7. **Navigation Badges**

```php
public static function getNavigationBadge(): ?string
{
    return static::getModel()::where('status_kehamilan', 'hamil')->count();
}
```

### 8. **Dynamic Charts**

- Line chart pendaftaran ibu hamil 6 bulan terakhir
- Pie chart distribusi kategori risiko
- Menggunakan Flowframe Trend

## 📊 Resources yang Masih Perlu Dibuat

### Panel Admin:

- [ ] TenagaKesehatanResource
- [ ] PemeriksaanAncResource
- [ ] HasilLaboratoriumResource
- [ ] KonsultasiResource
- [ ] UserResource

### Panel Puskesmas:

- [ ] TenagaKesehatanResource (scoped)
- [ ] PemeriksaanAncResource (scoped)
- [ ] SkriningRisikoResource (scoped)
- [ ] HasilLaboratoriumResource (scoped)
- [ ] KonsultasiResource (scoped)

Pattern yang sama seperti IbuHamilResource - tinggal copy dan sesuaikan field-nya!

## 🔧 Customization Tips

### 1. Custom Colors

```php
->colors([
    'primary' => Color::Pink,
    'success' => Color::Green,
    'danger' => Color::Red,
])
```

### 2. Custom Navigation Icon

```php
protected static ?string $navigationIcon = 'heroicon-o-user-group';
```

### 3. Custom Page Title

```php
protected static ?string $title = 'Data Ibu Hamil';
```

### 4. Add Custom Actions

```php
Tables\Actions\Action::make('cetak_kartu')
    ->label('Cetak Kartu')
    ->icon('heroicon-o-printer')
    ->url(fn ($record) => route('cetak.kartu', $record))
    ->openUrlInNewTab()
```

### 5. Add Bulk Actions

```php
Tables\Actions\BulkAction::make('kirim_sms')
    ->label('Kirim SMS Reminder')
    ->action(fn ($records) => /* Send SMS */)
```

## 🚨 Important Notes

1. **Always Use Scoping** di Panel Puskesmas untuk security
2. **Validate Input** di form untuk data integrity
3. **Use Soft Deletes** untuk semua data penting
4. **Add Indexes** di migration untuk performance
5. **Test Permission** pastikan role-based access work

## 📞 Next Steps

1. ✅ Setup Filament ✅ DONE
2. ✅ Create Resources ✅ PARTIAL DONE
3. 🔄 Complete All Resources
4. 🔄 Create Policies
5. 🔄 Add Custom Actions
6. 🔄 Implement Export/Import
7. 🔄 Create Laporan Pages
8. 🔄 Add Email Notifications
9. 🔄 Implement Real-time Updates
10. 🔄 Testing & Bug Fixes

Happy Coding! 🚀
