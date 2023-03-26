<?php

namespace App\Repositories\Commons;

use App\Models\Account;
use App\Models\Shift;
use App\Models\TreasuryTransaction;

class TransactionCommonRepository
{
    public function create($input)
    {
        return TreasuryTransaction::create($input);
    }
    public function getShift($admin)
    {
        return Shift::with("treasury")->where("admin_id", $admin->id)->where("is_finished", 0)->first();
    }
    public function getAccount($accountId)
    {
        return Account::find($accountId);
    }
    public function inecrementLastCollectionReceipt($treasury)
    {
        $treasury->last_collection_receipt++;
        $treasury->save();
        return $treasury->last_collection_receipt;
    }
    public function inecrementLastExchangeReceipt($treasury)
    {
        $treasury->last_exchange_receipt++;
        $treasury->save();
        return $treasury->last_exchange_receipt;
    }
}
