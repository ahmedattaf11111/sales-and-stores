<?php

namespace App\Repositories\Supplier;

use App\Models\Account;
use App\Models\AdminPanelSetting;
use App\Models\Supplier;
use App\Models\SupplierCategory;

class SupplierRepository
{
    public function getSuppliers($pageSize, $text)
    {
        return
            Supplier::when($text, function ($query) use ($text) {
                $query
                    ->where("id", $text)
                    ->OrwhereHas("account", function ($query) use ($text) {
                        $query->where("id", $text)->orWhere("name", "like", "%$text%");
                    });
            })
            ->with(["account.updated_by", "account.added_by", "supplierCategory"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function getCategories()
    {
        return SupplierCategory::get();
    }
    public function create($accountInput, $supplierInput)
    {
        $account = Account::create($accountInput);
        $supplierInput["account_id"] = $account->id;
        $supplier = Supplier::create($supplierInput);
        return ["supplier" => $supplier, "account" => $account];
    }
    public function update($accountInput, $supplierInput)
    {
        $supplier = Supplier::find($supplierInput["id"]);
        $supplier->update($supplierInput);
        $account = Account::find($supplier->account_id);
        $account->update($accountInput);
        return ["supplier" => $supplier, "account" => $account];
    }
    public function getSupplierParentAccountId()
    {
        $adminPanelSetting = AdminPanelSetting::first();
        return $adminPanelSetting ? $adminPanelSetting->supplier_parent_account_id : null;
    }
}
