<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            "name" => "Super Admin",
            "email" => "superadmin@admin.com",
            "password" => "123456"
        ]);
        Permission::create(
            ["name" => "super admin", "guard_name" => "admin"],
        );
        $admin->givePermissionTo("super admin");
    }
}
