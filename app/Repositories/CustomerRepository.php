<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AdminPanelSetting;
use App\Models\Customer;

class CustomerRepository
{
    public function getCustomers($pageSize, $text)
    {
        return
            Customer::when($text, function ($query) use ($text) {
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
    public function create($accountInput, $customerInput)
    {
        $account = Account::create($accountInput);
        $customerInput["account_id"] = $account->id;
        $customer = Customer::create($customerInput);
        return ["customer" => $customer, "account" => $account];
    }
    public function update($accountInput, $customerInput)
    {
        $customer = Customer::find($customerInput["id"]);
        $customer->update($customerInput);
        $account = Account::find($customer->account_id);
        $account->update($accountInput);
        return ["customer" => $customer, "account" => $account];
    }
    public function getCustomerParentAccountId()
    {
        $adminPanelSetting = AdminPanelSetting::first();
        return $adminPanelSetting ? $adminPanelSetting->customer_parent_account_id : null;
    }
}
