<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator - Penuh akses ke sistem']
        );

        Role::firstOrCreate(
            ['name' => 'manager'],
            ['description' => 'Manager - Dapat mengelola karyawan dan melihat laporan']
        );

        Role::firstOrCreate(
            ['name' => 'employee'],
            ['description' => 'Employee - Hanya dapat scan absensi']
        );
    }
}
