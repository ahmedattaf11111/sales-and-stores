<?php

namespace App\Services\Supplier;

use App\Repositories\Supplier\SupplierRepository;

class SupplierService
{
    private $supplierRepository;
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }
    public function getSuppliers($pageSize, $text)
    {
        return $this->supplierRepository->getSuppliers($pageSize, $text);
    }
    public function getCategories(){
        return $this->supplierRepository->getCategories();
    }
    public function create($user, $input)
    {
        $supplierParentAccountId = $this->supplierRepository->getSupplierParentAccountId();
        $result = $this->supplierRepository->create(
            $this->getAccountInput("create", $user, $supplierParentAccountId, $input),
            $this->getSupplierInput("create", $input)
        );
        return [
            "supplier" => $result["supplier"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    public function update($user, $input)
    {
        $supplierParentAccountId = $this->supplierRepository->getSupplierParentAccountId();
        $result = $this->supplierRepository->update(
            $this->getAccountInput("update", $user, $supplierParentAccountId, $input),
            $this->getSupplierInput("update", $input)
        );
        return [
            "supplier" => $result["supplier"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    //Commons
    private function getAccountInput($action, $user, $supplierParentAccountId, $input)
    {
        return [
            $action == "create" ? "added_by_id" : "updated_by_id" => $user->id,
            "name" => $input["name"],
            "note" => isset($input["note"]) ? $input["note"] : "",
            "active" => $input["active"],
            "account_type_id" => 1, //Supplier type
            "is_master" => 0,
            "parent_id" => $supplierParentAccountId,
            "start_balance" => $input["start_balance"],
        ];
    }
    private function getSupplierInput($action, $input)
    {
        $supplierInput = [
            "address" => $input["address"],
            "phone" => $input["phone"],
            "supplier_category_id" => $input["supplier_category_id"]
        ];
        if ($action == "update") $supplierInput["id"] = $input["id"];
        return $supplierInput;
    }
}
