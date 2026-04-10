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
        $editor = Role::create(['name' => 'editor']);

        $permissions = ['create-post', 'edit-post', 'delete-post', 'view-post'];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $admin->syncPermissions($permissions);
        $editor->syncPermissions(['create-post', 'edit-post', 'view-post']);
    }
}