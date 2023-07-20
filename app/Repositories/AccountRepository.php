<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AccountType;

class AccountRepository
{
    public function getAccounts($pageSize, $text, $accountTypeId, $isMaster)
    {
        return
            Account::when($text, function ($query) use ($text) {
                $query->where(function ($query) use ($text) {
                    $query->where("name", "like", "%$text%")->orWhere("id", $text);
                });
            })
            ->when($accountTypeId, function ($query) use ($accountTypeId) {
                $query->where("account_type_id", $accountTypeId);
            })
            ->when($isMaster === "1" || $isMaster === "0", function ($query) use ($isMaster) {
                $query->where("is_master", $isMaster);
            })
            ->whereRelation("accountType", "related_internal_account", 0)
            ->with(["added_by", "updated_by","accountType","parent"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function getAllAccounts()
    {
        return Account::where("is_master", 1)->get();
    }
    public function getTypes()
    {
        return AccountType::where("active", 1)->where("related_internal_account", 0)->get();
    }
    public function create($input)
    {
        $account = Account::create($input);
        return $account;
    }
    public function update($input)
    {
        $account = Account::find($input["id"]);
        $account->update($input);
        return $account;
    }
}
