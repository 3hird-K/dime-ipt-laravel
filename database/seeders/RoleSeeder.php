<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
        $chairmanRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Chairman']);
        $teacherRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Teacher']);
        $studentRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Student']);

        // Create permissions
        $permissions = [
            'create_users',
            'read_users',
            'update_users',
            'delete_users',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to Admin and Chairman (All permissions)
        $adminRole->syncPermissions($permissions);
        $chairmanRole->syncPermissions($permissions);

        // Assign permissions to Teacher (Read and Update only)
        $teacherRole->syncPermissions(['read_users', 'update_users']);
        
        // Student role doesn't need global permissions for updating their own data,
        // this is best handled in a Laravel Policy (e.g., UserPolicy).
    }
}
