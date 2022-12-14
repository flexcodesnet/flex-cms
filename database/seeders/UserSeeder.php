<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();

        User::query()->create([
            'name' => 'Root',
            'email' => 'root@flexcodes.net',
            'password' => '123456',
            'role_id' => 1,
        ]);

        User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@flexcodes.net',
            'password' => '123456',
            'role_id' => 2,
        ]);
    }
}
