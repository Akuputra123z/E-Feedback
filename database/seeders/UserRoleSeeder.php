<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $irbanRole = Role::firstOrCreate(['name' => 'irban']);

        // 🔹 Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // 🔹 Irban 1
        $irban1 = User::firstOrCreate(
            ['email' => 'irban1@test.com'],
            [
                'name' => 'Irban 1',
                'password' => Hash::make('password'),
            ]
        );
        $irban1->assignRole($irbanRole);

        // 🔹 Irban 2
        $irban2 = User::firstOrCreate(
            ['email' => 'irban2@test.com'],
            [
                'name' => 'Irban 2',
                'password' => Hash::make('password'),
            ]
        );
        $irban2->assignRole($irbanRole);
    }
}
