<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ChatRoom::create(["name" => "Room 1"]);
        ChatRoom::create(["name" => "Room 2"]);
        // \App\Models\User::factory(10)->create();
    }
}
