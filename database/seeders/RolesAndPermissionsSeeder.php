<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $admin  = Role::create(['name' => 'admin']);

        $permissions = ['create-post', 'edit-post', 'delete-post', 'view-post', 
            'create-user', 'edit-user', 'delete-user', 'view-user', 
            'create-role', 'edit-role', 'delete-role', 'view-role'
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $admin->syncPermissions($permissions);
    }
}