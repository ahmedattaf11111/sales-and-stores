<?php

    namespace Database\Seeders;

use App\Commons\Consts\TreasuryTransactionType;
use App\Models\MoveType;
use Illuminate\Database\Seeder;

    class MoveTypeSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
            MoveType::insert(
                [
                    ["name" => "صرف لرد رأس المال","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "صرف للإيداع البنكي","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "صرف نظير مشتريات من مورد","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "صرف نظير مرتجع مبيعات","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "صرف مبلغ لحساب مالي","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "مصاريف شراء مثل النولون","treasury_transaction_type" => TreasuryTransactionType::EXCHANGE],
                    ["name" => "تحصيل مبلغ من حساب مالي","treasury_transaction_type" => TreasuryTransactionType::COLLECT],
                    ["name" => "سحب من البنك","treasury_transaction_type" => TreasuryTransactionType::COLLECT],
                    ["name" => "ايراد زيادة راس المال","treasury_transaction_type" => TreasuryTransactionType::COLLECT],
                    ["name" => "تحصيل نظير مرتجع مشتريات الي مورد","treasury_transaction_type" => TreasuryTransactionType::COLLECT],
                    ["name" => "تحصيل ايراد مبيعات","treasury_transaction_type" => TreasuryTransactionType::COLLECT],
                ]
            );
        }
    }
