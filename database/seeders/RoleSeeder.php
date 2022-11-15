<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
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

        $role = Role::query()->create([
            'title' => 'Root',
        ]);

        $role->permissions()->attach(Permission::childrenIds());

        Role::query()->create([
            'title' => 'Admin',
        ]);

        Role::query()->create([
            'title' => 'Data Entry',
        ]);
    }
}
