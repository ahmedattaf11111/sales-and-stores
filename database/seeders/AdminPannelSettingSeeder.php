<?php

namespace Database\Seeders;

use App\Models\AdminPanelSetting;
use Illuminate\Database\Seeder;

class AdminPannelSettingSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        AdminPanelSetting::create(["system_name" => "شركة"]);
    }
}
