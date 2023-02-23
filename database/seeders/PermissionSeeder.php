<?php

namespace Database\Seeders;

use FXC\Base\Models\Permission;
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
        $crud_permissions = [
            'View',
            'Add',
            'Edit',
            'Seo',
            'Delete',
            'Export',
        ];
        $single_permissions = config('settings.permissions.single',[]);

        $module_permissions = config('settings.permissions.modules',[]);

        Permission::query()->truncate();

        foreach ($single_permissions as $permissionTitle) {
            Permission::query()->create([
                'title' => $permissionTitle,
            ]);
        }

        $permissions = [];
        foreach ($module_permissions as $permissionTitle) {
            $permissions[] = Permission::query()->create([
                'title' => $permissionTitle,
            ]);
        }

        foreach ($permissions as $permission) {
            foreach ($crud_permissions as $crudItem) {
                Permission::query()->create([
                    'title'     => $crudItem, // view, add, edit, delete
                    'parent_id' => $permission->id,
                ]);
            }
        }
    }
}
