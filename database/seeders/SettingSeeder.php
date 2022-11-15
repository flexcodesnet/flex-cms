<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->truncate();

        Setting::query()->create([
            'id' => '1',
            'title' => 'Flex CMS',
        ]);
    }
}
