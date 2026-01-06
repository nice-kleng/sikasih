<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Puskesmas;
use App\Models\TenagaKesehatan;
use App\Models\IbuHamil;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // 1. CREATE ROLES
        // ============================================

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $puskesmasRole = Role::firstOrCreate(['name' => 'puskesmas', 'guard_name' => 'web']);
        $tenagaKesehatanRole = Role::firstOrCreate(['name' => 'tenaga_kesehatan', 'guard_name' => 'web']);
        $ibuHamilRole = Role::firstOrCreate(['name' => 'ibu_hamil', 'guard_name' => 'web']);

        // ============================================
        // 2. CREATE SUPER ADMIN USER
        // ============================================

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@sikasih.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'status' => 'aktif',
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // ============================================
        // 3. CREATE PUSKESMAS
        // ============================================

        $userPuskesmas = User::firstOrCreate(
            ['email' => 'puskesmas@sikasih.id'],
            [
                'name' => 'Admin Puskesmas Sukolilo',
                'password' => bcrypt('password'),
                'no_telepon' => '081234567890',
                'status' => 'aktif',
            ]
        );
        $userPuskesmas->assignRole($puskesmasRole);

        $puskesmas = Puskesmas::firstOrCreate(
            ['kode_puskesmas' => 'PKM-SBY-001'],
            [
                'user_id' => $userPuskesmas->id,
                'nama_puskesmas' => 'Puskesmas Sukolilo',
                'alamat' => 'Jl. Raya Sukolilo No. 100',
                'kecamatan' => 'Sukolilo',
                'kabupaten' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60111',
                'no_telepon' => '031-5993456',
                'email' => 'puskesmas.sukolilo@surabaya.go.id',
                'kepala_puskesmas' => 'dr. Ahmad Fauzi',
                'fasilitas' => ['Ruang KIA', 'Laboratorium', 'Apotik', 'Ruang Persalinan'],
                'tipe' => 'poned',
                'status' => 'aktif',
            ]
        );

        // ============================================
        // 4. CREATE TENAGA KESEHATAN
        // ============================================

        // Bidan 1
        $userBidan1 = User::firstOrCreate(
            ['email' => 'bidan.linda@sikasih.id'],
            [
                'name' => 'Linda Wati',
                'password' => bcrypt('password'),
                'no_telepon' => '081234567891',
                'status' => 'aktif',
            ]
        );
        $userBidan1->assignRole($tenagaKesehatanRole);

        TenagaKesehatan::firstOrCreate(
            ['str' => 'STR-BD-001-2024'],
            [
                'user_id' => $userBidan1->id,
                'puskesmas_id' => $puskesmas->id,
                'nip' => '198505152010012001',
                'jenis_tenaga' => 'bidan',
                'pendidikan_terakhir' => 'D4 Kebidanan',
                'tanggal_lahir' => '1985-05-15',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Kenjeran No. 25, Surabaya',
                'tanggal_mulai_kerja' => '2010-01-01',
                'status_kepegawaian' => 'PNS',
                'status' => 'aktif',
            ]
        );

        // Bidan 2
        $userBidan2 = User::firstOrCreate(
            ['email' => 'bidan.sari@sikasih.id'],
            [
                'name' => 'Sari Dewi',
                'password' => bcrypt('password'),
                'no_telepon' => '081234567892',
                'status' => 'aktif',
            ]
        );
        $userBidan2->assignRole($tenagaKesehatanRole);

        TenagaKesehatan::firstOrCreate(
            ['str' => 'STR-BD-002-2024'],
            [
                'user_id' => $userBidan2->id,
                'puskesmas_id' => $puskesmas->id,
                'nip' => '199003202015032002',
                'jenis_tenaga' => 'bidan',
                'pendidikan_terakhir' => 'Profesi Bidan',
                'tanggal_lahir' => '1990-03-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Manyar No. 45, Surabaya',
                'tanggal_mulai_kerja' => '2015-03-01',
                'status_kepegawaian' => 'PPPK',
                'status' => 'aktif',
            ]
        );

        // ============================================
        // 5. CREATE IBU HAMIL
        // ============================================

        // Ibu Hamil 1
        $userIbu1 = User::firstOrCreate(
            ['email' => 'siti.nurhaliza@gmail.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => bcrypt('password'),
                'no_telepon' => '081234567893',
                'status' => 'aktif',
            ]
        );
        $userIbu1->assignRole($ibuHamilRole);

        IbuHamil::firstOrCreate(
            ['nik' => '3578125505950001'],
            [
                'user_id' => $userIbu1->id,
                'puskesmas_id' => $puskesmas->id,
                'no_rm' => 'RM-2024-0001',
                'nama_lengkap' => 'Siti Nurhaliza',
                'tanggal_lahir' => '1995-05-15',
                'umur' => 29,
                'alamat_lengkap' => 'Jl. Raya Sukolilo No. 123',
                'rt' => '005',
                'rw' => '002',
                'kelurahan' => 'Sukolilo',
                'kecamatan' => 'Sukolilo',
                'kabupaten' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60111',
                'golongan_darah' => 'A',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan' => 'Guru',
                'nama_suami' => 'Budi Santoso',
                'umur_suami' => 32,
                'pekerjaan_suami' => 'Wiraswasta',
                'pendidikan_suami' => 'S1',
                'gravida' => 1,
                'para' => 0,
                'abortus' => 0,
                'anak_hidup' => 0,
                'usia_menikah' => 27,
                'usia_hamil_pertama' => 29,
                'memiliki_bpjs' => 'Ya',
                'no_bpjs' => '0001234567890',
                'hpht' => now()->subWeeks(16),
                'hpl' => now()->addWeeks(24),
                'usia_kehamilan_minggu' => 16,
                'berat_badan_awal' => 55.5,
                'tinggi_badan' => 158,
                'status_kehamilan' => 'hamil',
                'status' => 'aktif',
            ]
        );

        // Ibu Hamil 2
        $userIbu2 = User::firstOrCreate(
            ['email' => 'rina.wijaya@gmail.com'],
            [
                'name' => 'Rina Wijaya',
                'password' => bcrypt('password'),
                'no_telepon' => '081234567894',
                'status' => 'aktif',
            ]
        );
        $userIbu2->assignRole($ibuHamilRole);

        IbuHamil::firstOrCreate(
            ['nik' => '3578126610920002'],
            [
                'user_id' => $userIbu2->id,
                'puskesmas_id' => $puskesmas->id,
                'no_rm' => 'RM-2024-0002',
                'nama_lengkap' => 'Rina Wijaya',
                'tanggal_lahir' => '1992-10-26',
                'umur' => 32,
                'alamat_lengkap' => 'Jl. Menur No. 78',
                'rt' => '003',
                'rw' => '001',
                'kelurahan' => 'Menur',
                'kecamatan' => 'Sukolilo',
                'kabupaten' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60118',
                'golongan_darah' => 'B',
                'pendidikan_terakhir' => 'SMA/SMK',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'nama_suami' => 'Agus Setiawan',
                'umur_suami' => 35,
                'pekerjaan_suami' => 'PNS',
                'pendidikan_suami' => 'S1',
                'gravida' => 2,
                'para' => 1,
                'abortus' => 0,
                'anak_hidup' => 1,
                'usia_menikah' => 25,
                'usia_hamil_pertama' => 26,
                'memiliki_bpjs' => 'Ya',
                'no_bpjs' => '0009876543210',
                'hpht' => now()->subWeeks(28),
                'hpl' => now()->addWeeks(12),
                'usia_kehamilan_minggu' => 28,
                'berat_badan_awal' => 60.0,
                'tinggi_badan' => 160,
                'status_kehamilan' => 'hamil',
                'status' => 'aktif',
            ]
        );

        $this->command->info('✅ Database seeded successfully with Shield roles!');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Super Admin: admin@sikasih.id / password');
        $this->command->info('   Puskesmas: puskesmas@sikasih.id / password');
        $this->command->info('   Bidan 1: bidan.linda@sikasih.id / password');
        $this->command->info('   Bidan 2: bidan.sari@sikasih.id / password');
        $this->command->info('');
        $this->command->info('🚀 Next: Run php artisan shield:generate --all');
    }
}
