<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@sikasih.com',
            'password' => bcrypt('sikasih'),
        ]);

        $puskesmas = \App\Models\User::create([
            'name' => 'Puskesmas User',
            'email' => 'puskesmas@sikasih.com',
            'password' => bcrypt('sikasih'),
        ]);
    }
}
