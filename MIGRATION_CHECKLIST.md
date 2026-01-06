# 🔄 MIGRATION CHECKLIST: Policy → Filament Shield

## 📋 Pre-Migration Checklist

- [ ] Backup database
- [ ] Commit current code to git
- [ ] Test current system works properly
- [ ] Note current login credentials

---

## 🚀 MIGRATION STEPS

### ✅ STEP 1: Install Filament Shield

```bash
# 1.1 Install package
composer require bezhansalleh/filament-shield:"^3.0"

# 1.2 Publish config
php artisan vendor:publish --tag=filament-shield-config

# 1.3 Install Shield (akan membuat migration & seeder)
php artisan shield:install --fresh

# Jawab pertanyaan:
# - Panel: admin
# - Generate permissions?: yes
# - Generate super_admin?: yes (nanti kita re-seed)

# 1.4 Run migrations (Shield akan create permission tables)
php artisan migrate
```

**Expected Output:**
```
✔ Shield is installed successfully!
```

---

### ✅ STEP 2: Update Configuration Files

```bash
# 2.1 Copy config file
cp filament-shield/config/filament-shield.php config/

# 2.2 Copy updated User model
cp filament-shield/Models/User.php app/Models/

# 2.3 Copy updated AuthServiceProvider
cp filament-shield/Providers/AuthServiceProvider.php app/Providers/

# 2.4 Copy updated DatabaseSeeder
cp filament-shield/Seeders/DatabaseSeeder.php database/seeders/
```

**Verify:**
- [ ] User model has `HasPanelShield` trait
- [ ] User model implements `FilamentUser` interface
- [ ] AuthServiceProvider has custom gates
- [ ] DatabaseSeeder creates roles properly

---

### ✅ STEP 3: Delete Old Policy Files

```bash
# 3.1 Delete manual policy files
rm app/Policies/IbuHamilPolicy.php
rm app/Policies/PemeriksaanAncPolicy.php
rm app/Policies/PuskesmasPolicy.php

# 3.2 Check if Policies directory is empty (except .gitkeep)
ls -la app/Policies/

# 3.3 Git status to confirm deletion
git status
```

**Verify:**
- [ ] Policy files deleted
- [ ] Git shows deleted files
- [ ] No policy registration in AuthServiceProvider

---

### ✅ STEP 4: Update ALL Resources

#### Admin Panel Resources:

```bash
# Update these files to implement HasShieldPermissions:

app/Filament/Admin/Resources/
├── PuskesmasResource.php
├── IbuHamilResource.php
├── TenagaKesehatanResource.php
├── PemeriksaanAncResource.php
├── SkriningRisikoResource.php
├── ArtikelResource.php
└── VideoEdukasiResource.php
```

**For each file:**
1. Add: `use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;`
2. Update: `class XResource extends Resource implements HasShieldPermissions`
3. Add method:
```php
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
```
4. Keep ALL other methods unchanged!

#### Puskesmas Panel Resources:

```bash
app/Filament/Puskesmas/Resources/
├── IbuHamilResource.php
├── TenagaKesehatanResource.php
├── PemeriksaanAncResource.php
└── SkriningRisikoResource.php
```

**Same steps as Admin Panel resources**

**Verify each file:**
- [ ] Has `HasShieldPermissions` interface
- [ ] Has `getPermissionPrefixes()` method
- [ ] Has `getEloquentQuery()` for scoping (if applicable)
- [ ] All existing methods unchanged

---

### ✅ STEP 5: Update Panel Providers (Optional)

File: `app/Providers/Filament/AdminPanelProvider.php`

**BEFORE:**
```php
->authMiddleware([
    Authenticate::class,
    \App\Http\Middleware\EnsureSuperadmin::class,
])
```

**AFTER (Optional - Shield sudah cukup):**
```php
->authMiddleware([
    Authenticate::class,
    // Shield handles authorization
])
```

File: `app/Providers/Filament/PuskesmasPanelProvider.php`

**BEFORE:**
```php
->authMiddleware([
    Authenticate::class,
    \App\Http\Middleware\EnsurePuskesmas::class,
])
```

**AFTER (Optional):**
```php
->authMiddleware([
    Authenticate::class,
    // Shield handles authorization
])
```

**Note:** Middleware bisa tetap dipakai untuk extra security layer.

**Verify:**
- [ ] AdminPanelProvider updated
- [ ] PuskesmasPanelProvider updated

---

### ✅ STEP 6: Clear Cache & Generate Permissions

```bash
# 6.1 Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 6.2 Generate Shield permissions for ALL resources
php artisan shield:generate --all

# Akan generate permissions untuk:
# - view_puskesmas
# - view_any_puskesmas
# - create_puskesmas
# - update_puskesmas
# - delete_puskesmas
# - dll untuk semua resources

# 6.3 Verify permissions created
php artisan tinker
>>> \Spatie\Permission\Models\Permission::count()
>>> \Spatie\Permission\Models\Permission::pluck('name')
```

**Expected Output:**
```
✔ Generating permissions & policies...
✔ Permissions & Policies generated successfully!
```

**Verify:**
- [ ] Permissions generated (check database: `permissions` table)
- [ ] Should have ~70+ permissions

---

### ✅ STEP 7: Re-seed Database

```bash
# 7.1 Fresh migrate with seed
php artisan migrate:fresh --seed

# Atau jika sudah ada data:
php artisan db:seed --class=DatabaseSeeder

# 7.2 Generate permissions lagi (after seed)
php artisan shield:generate --all
```

**Verify:**
- [ ] Roles created (super_admin, puskesmas, tenaga_kesehatan, ibu_hamil)
- [ ] Users created with proper roles
- [ ] Permissions generated

---

### ✅ STEP 8: Assign Permissions via GUI

```bash
# 8.1 Start server
php artisan serve

# 8.2 Login as super_admin
# URL: http://localhost:8000/admin
# Email: admin@sikasih.id
# Password: password
```

#### Setup Role Permissions:

**1. Super Admin Role** → Already has all permissions (automatic)

**2. Puskesmas Role:**

Go to: **Shield → Roles → puskesmas → Edit**

Select permissions:
- ✅ `view_any_*` for all resources they should see
- ✅ `view_*` for all resources
- ✅ `create_*` for all resources they can create
- ✅ `update_*` for all resources they can update
- ✅ `delete_*` for all resources they can delete

Resources for Puskesmas:
- ✅ ibu_hamil (full CRUD)
- ✅ tenaga_kesehatan (full CRUD)
- ✅ pemeriksaan_anc (full CRUD)
- ✅ skrining_risiko (full CRUD)
- ✅ hasil_laboratorium (full CRUD)
- ❌ puskesmas (NO - only view own)
- ❌ artikel (NO)
- ❌ video_edukasi (NO)

**3. Tenaga Kesehatan Role:**

Select permissions:
- ✅ `view_any_ibu_hamil`
- ✅ `view_ibu_hamil`
- ✅ `create_ibu_hamil`
- ✅ `update_ibu_hamil`
- ❌ `delete_ibu_hamil` (NO!)

- ✅ `view_any_tenaga_kesehatan`
- ✅ `view_tenaga_kesehatan`
- ❌ `create_tenaga_kesehatan` (NO!)
- ❌ `update_tenaga_kesehatan` (NO!)
- ❌ `delete_tenaga_kesehatan` (NO!)

- ✅ `view_any_pemeriksaan_anc`
- ✅ `view_pemeriksaan_anc`
- ✅ `create_pemeriksaan_anc`
- ✅ `update_pemeriksaan_anc` (with 24h gate check)
- ❌ `delete_pemeriksaan_anc` (NO!)

- ✅ `view_any_skrining_risiko`
- ✅ `view_skrining_risiko`
- ✅ `create_skrining_risiko`
- ✅ `update_skrining_risiko`
- ❌ `delete_skrining_risiko` (NO!)

**Verify:**
- [ ] Super admin has all permissions
- [ ] Puskesmas has correct permissions
- [ ] Tenaga kesehatan has limited permissions
- [ ] Ibu hamil has view-only permissions

---

### ✅ STEP 9: Testing

#### Test 1: Super Admin Access

```
Login: admin@sikasih.id / password
Panel: /admin

✅ Should see:
- All menu items
- Shield → Roles menu
- Shield → Permissions menu
- Can CRUD all resources
- Can see all data (no scoping)

✅ Test:
- Create ibu hamil → Success
- Edit pemeriksaan → Success
- Delete records → Success
- Access Shield → Success
```

#### Test 2: Puskesmas Access

```
Login: puskesmas@sikasih.id / password
Panel: /puskesmas

✅ Should see:
- Ibu Hamil menu
- Tenaga Kesehatan menu
- Pemeriksaan ANC menu
- Skrining Risiko menu
- Laporan menu

❌ Should NOT see:
- Shield menu
- Puskesmas menu (only can edit own profile)
- Artikel menu
- Video menu

✅ Test:
- View ibu hamil → Only see own puskesmas data
- Create ibu hamil → Success
- Edit ibu hamil → Success
- Delete ibu hamil → Success
- View pemeriksaan → Only see own puskesmas data
- Create pemeriksaan → Success
- Edit pemeriksaan → Success
- Delete pemeriksaan → Success
```

#### Test 3: Tenaga Kesehatan Access

```
Login: bidan.linda@sikasih.id / password
Panel: /puskesmas

✅ Should see:
- Ibu Hamil menu (limited)
- Pemeriksaan ANC menu (limited)
- Skrining Risiko menu (limited)

❌ Should NOT see:
- Delete buttons on ibu hamil
- Delete buttons on pemeriksaan
- Tenaga kesehatan CRUD
- Shield menu

✅ Test:
- View ibu hamil → Only see own puskesmas data
- Create ibu hamil → Success
- Edit ibu hamil → Success
- Delete ibu hamil → ❌ FAIL (correct!)

- View pemeriksaan → Only see own puskesmas data
- Create pemeriksaan → Success
- Edit OWN pemeriksaan (< 24h) → Success
- Edit OWN pemeriksaan (> 24h) → ❌ FAIL (correct!)
- Edit OTHER pemeriksaan → ❌ FAIL (correct!)
- Delete pemeriksaan → ❌ FAIL (correct!)
```

#### Test 4: Authorization Edge Cases

```
✅ Test 24-hour rule:
1. Login as tenaga kesehatan
2. Create pemeriksaan → Note time
3. Edit immediately → Should work
4. Wait 24+ hours (or change created_at in DB)
5. Try edit → Should show "Hanya dapat diedit dalam 24 jam pertama"
6. Logout, login as puskesmas
7. Edit same pemeriksaan → Should work (puskesmas bypass 24h rule)

✅ Test data scoping:
1. Create 2nd puskesmas
2. Create ibu hamil di puskesmas 2
3. Login as puskesmas 1
4. Try view ibu hamil → Should NOT see puskesmas 2 data
5. Login as super_admin
6. View ibu hamil → Should see ALL data
```

---

### ✅ STEP 10: Cleanup & Finalize

```bash
# 10.1 Remove unused middleware files (optional)
# If not using EnsureSuperadmin & EnsurePuskesmas anymore:
# rm app/Http/Middleware/EnsureSuperadmin.php
# rm app/Http/Middleware/EnsurePuskesmas.php

# 10.2 Update bootstrap/app.php to remove middleware aliases (if deleted)

# 10.3 Git commit
git add .
git commit -m "feat: Migrate from manual Policy to Filament Shield v3"

# 10.4 Clear all caches one more time
php artisan optimize:clear
```

**Verify:**
- [ ] All tests passed
- [ ] No errors in logs
- [ ] Authorization working properly
- [ ] Data scoping working properly
- [ ] 24h rule working properly
- [ ] Code committed

---

## ✅ POST-MIGRATION CHECKLIST

### Functionality:
- [ ] All users can login
- [ ] Super admin has full access
- [ ] Puskesmas has scoped access
- [ ] Tenaga kesehatan has limited access
- [ ] Data scoping works correctly
- [ ] 24h edit rule works correctly
- [ ] Delete restrictions work correctly

### UI:
- [ ] Shield menu appears for super_admin
- [ ] Unauthorized menus hidden for users
- [ ] Action buttons hidden based on permissions
- [ ] Tooltips show for 24h rule
- [ ] Navigation badges show correct counts

### Database:
- [ ] Roles table populated
- [ ] Permissions table populated
- [ ] Role_has_permissions populated
- [ ] Model_has_roles populated
- [ ] No orphaned records

### Code:
- [ ] No policy files remaining
- [ ] All Resources implement HasShieldPermissions
- [ ] All Resources have getPermissionPrefixes()
- [ ] AuthServiceProvider has custom gates
- [ ] User model has HasPanelShield trait

---

## 🎯 SUCCESS CRITERIA

✅ System is successfully migrated when:

1. **All old policy files deleted**
2. **All Resources using Shield interface**
3. **Permissions generated for all resources**
4. **Roles configured with correct permissions**
5. **All authorization tests passed**
6. **No errors in application logs**
7. **Data scoping working correctly**
8. **Custom rules (24h) working correctly**
9. **GUI permission management working**
10. **Production ready**

---

## 🚨 TROUBLESHOOTING

### Issue 1: Permissions not working

```bash
# Clear cache
php artisan optimize:clear

# Re-generate permissions
php artisan shield:generate --all

# Check permissions
php artisan tinker
>>> User::find(1)->getAllPermissions()
```

### Issue 2: Access denied for super_admin

```bash
# Check super_admin role
php artisan tinker
>>> User::find(1)->roles
>>> Role::where('name', 'super_admin')->first()

# Verify Gate::before in AuthServiceProvider
# Should return true for super_admin
```

### Issue 3: Data scoping not working

```bash
# Check getEloquentQuery() method in Resource
# Should have proper scoping logic
# Example:
if (!auth()->user()->hasRole('super_admin')) {
    $query->where('puskesmas_id', $puskesmasId);
}
```

### Issue 4: 24h rule not working

```bash
# Check custom gate in AuthServiceProvider
# Check visible() in EditAction
# Check tooltip message
```

---

## 📊 BEFORE vs AFTER

### BEFORE (Manual Policy):
- ❌ 3 policy files to maintain
- ❌ Manual permission checks
- ❌ No GUI for permissions
- ❌ Hard to add new permissions
- ❌ Time-consuming setup

### AFTER (Filament Shield):
- ✅ 0 policy files
- ✅ Auto permission checks
- ✅ GUI for permissions
- ✅ Easy to add permissions
- ✅ Quick setup
- ✅ Better UX
- ✅ Production ready

---

## 🎉 MIGRATION COMPLETE!

Congratulations! Your system now uses Filament Shield v3 for authorization.

**Benefits:**
- 🎨 Beautiful GUI for managing permissions
- ⚡ Faster development & maintenance
- 🔒 More secure with proper authorization
- 📊 Better admin UX
- 🚀 Production ready

**Next Steps:**
- [ ] Train admin to use Shield GUI
- [ ] Document permission structure
- [ ] Setup production roles
- [ ] Deploy to staging
- [ ] Deploy to production

---

**STATUS: ✅ READY FOR PRODUCTION!** 🚀
