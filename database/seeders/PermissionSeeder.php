<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->truncate();

        Permission::query()->create([
            'title' => 'Settings',
        ]);

        Permission::query()->create([
            'title' => 'Profile',
        ]);

        $permissions = [];

        $permissions[] = Permission::query()->create([
            'title' => 'Users',
        ]);

        $permissions[] = Permission::query()->create([
            'title' => 'Roles',
        ]);

        $permissions[] = Permission::query()->create([
            'title' => 'Permissions',
        ]);

        foreach ($permissions as $permission) {
            Permission::query()->create([
                'title' => 'View',
                'parent_id' => $permission->id,
            ]);

            Permission::query()->create([
                'title' => 'Add',
                'parent_id' => $permission->id,
            ]);

            Permission::query()->create([
                'title' => 'Edit',
                'parent_id' => $permission->id,
            ]);

            Permission::query()->create([
                'title' => 'Delete',
                'parent_id' => $permission->id,
            ]);

            Permission::query()->create([
                'title' => 'Export',
                'parent_id' => $permission->id,
            ]);
        }
    }
}
