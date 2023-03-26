<?php

    namespace Database\Seeders;

    use App\Models\AccountType;
    use Illuminate\Database\Seeder;

    class AccountTypeSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
            AccountType::insert(
                [
                    ["name" => "مورد", "active" => 1, "related_internal_account" => 1],
                    ["name" => "عميل", "active" => 1, "related_internal_account" => 1],
                    ["name" => "مندوب", "active" => 0, "related_internal_account" => 1],
                    ["name" => "بنكى", "active" => 1, "related_internal_account" => 0],
                    ["name" => "موظف", "active" => 1, "related_internal_account" => 1],
                    ["name" => "عام", "active" => 1, "related_internal_account" => 0],
                    ["name" => "مصروفات", "active" => 1, "related_internal_account" => 0],
                    ["name" => "قسم داخلي", "active" => 1, "related_internal_account" => 1],
                    ["name" => "راس مال", "active" => 1, "related_internal_account" => 0],
                ]
            );
        }
    }
