<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AdminPanelSetting;
use App\Models\Delegate;

class DelegateRepository
{
    public function getDelegates($pageSize, $text)
    {
        return
            Delegate::when($text, function ($query) use ($text) {
                $query
                    ->where("id", $text)
                    ->OrwhereHas("account", function ($query) use ($text) {
                        $query->where("id", $text)->orWhere("name", "like", "%$text%");
                    });
            })
            ->with(["account.updated_by", "account.added_by"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function create($accountInput, $delegateInput)
    {
        $account = Account::create($accountInput);
        $delegateInput["account_id"] = $account->id;
        $delegate = Delegate::create($delegateInput);
        return ["delegate" => $delegate, "account" => $account];
    }
    public function update($accountInput, $delegateInput)
    {
        $delegate = Delegate::find($delegateInput["id"]);
        $delegate->update($delegateInput);
        $account = Account::find($delegate->account_id);
        $account->update($accountInput);
        return ["delegate" => $delegate, "account" => $account];
    }
    public function getDelegateParentAccountId()
    {
        $adminPanelSetting = AdminPanelSetting::first();
        return $adminPanelSetting ? $adminPanelSetting->delegate_parent_account_id : null;
    }
}
