<?php

namespace Database\Seeders;

use FXC\Base\Models\Permission;
use FXC\Base\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->truncate();
        DB::table('permission_role')->truncate();
        $roles = [
            'Root',
            'Super Admin',
            'Admin',
            'Editor',
            'User',
        ];
        foreach ($roles as $role) {
            $role = Role::query()->create([
                'title' => $role,
            ]);
            $role->permissions()->attach(Permission::childrenIds());
        }
    }
}

