<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            "name" => "Super Admin",
            "email" => "superadmin@admin.com",
            "password" => "123456"
        ]);
    }
}
