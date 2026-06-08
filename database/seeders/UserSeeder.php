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

        $rootPassword = env('SEED_ROOT_PASSWORD');
        $adminPassword = env('SEED_ADMIN_PASSWORD');

        if (empty($rootPassword)) {
            $rootPassword = 'ChangeMe!'.bin2hex(random_bytes(4));
            $this->command?->warn("SEED_ROOT_PASSWORD not set. Root password: {$rootPassword}");
        }

        if (empty($adminPassword)) {
            $adminPassword = 'ChangeMe!'.bin2hex(random_bytes(4));
            $this->command?->warn("SEED_ADMIN_PASSWORD not set. Admin password: {$adminPassword}");
        }

        User::query()->forceCreate([
            'name' => 'Root',
            'email' => 'root@flexcodes.net',
            'password' => $rootPassword,
            'role_id' => 1,
            'active' => true,
        ]);

        User::query()->forceCreate([
            'name' => 'Admin',
            'email' => 'admin@flexcodes.net',
            'password' => $adminPassword,
            'role_id' => 2,
            'active' => true,
        ]);
    }
}
