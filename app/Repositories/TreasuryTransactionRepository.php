<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Shift;
use App\Models\TreasuryTransaction;

class TreasuryTransactionRepository
{
    public function getTreasuryTransactions($pageSize, $type, $text)
    {
        return TreasuryTransaction::with([
            "moveType", "added_by", "shift.treasury", "account", "purchaseInvoice", "saleInvoice"
        ])
            ->when($type, function ($query) use ($type) {
                $query->where("type", $type);
            })->when($text, function ($query) use ($text) {
                $query->where("collect_receipt_number", $text)->orWhere("exchange_receipt_number", $text);
            })->orderByDesc("id")->paginate($pageSize);
    }
    public function getAccounts()
    {
        return Account::get();
    }
    public function getGeneralInfo($admin)
    {
        //Current shift
        $shift = Shift::with("treasury")->where("admin_id", $admin->id)->where("is_finished", 0)->first();
        $totalTreasuryAmount = TreasuryTransaction::sum("amount");
        return $shift ? ["shift" => $shift, "total_amount" => $totalTreasuryAmount] : null;
    }
}
