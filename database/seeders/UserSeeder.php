<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@absensi.local',
            'password' => bcrypt('password123'),
            'role_id' => $adminRole->id,
            'nip' => 'ADM001',
            'phone' => '081234567890',
            'department' => 'IT'
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@absensi.local',
            'password' => bcrypt('password123'),
            'role_id' => $managerRole->id,
            'nip' => 'MGR001',
            'phone' => '081234567891',
            'department' => 'HR'
        ]);

        // Create 5 employee users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Employee ' . $i,
                'email' => 'employee' . $i . '@absensi.local',
                'password' => bcrypt('password123'),
                'role_id' => $employeeRole->id,
                'nip' => 'EMP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'phone' => '08123456789' . $i,
                'department' => 'Operations'
            ]);
        }
    }
}
