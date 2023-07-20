<?php

namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
    private $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
    public function getCustomers($pageSize, $text)
    {
        return $this->customerRepository->getCustomers($pageSize, $text);
    }
    public function create($user, $input)
    {
        $customerParentAccountId = $this->customerRepository->getCustomerParentAccountId();
        $result = $this->customerRepository->create(
            $this->getAccountInput("create", $user, $customerParentAccountId, $input),
            $this->getCustomerInput("create", $input)
        );
        return [
            "customer" => $result["customer"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    public function update($user, $input)
    {
        $customerParentAccountId = $this->customerRepository->getCustomerParentAccountId();
        $result = $this->customerRepository->update(
            $this->getAccountInput("update", $user, $customerParentAccountId, $input),
            $this->getCustomerInput("update", $input)
        );
        return [
            "customer" => $result["customer"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    //Commons
    private function getAccountInput($action, $user, $customerParentAccountId, $input)
    {
        return [
            $action == "create" ? "added_by_id" : "updated_by_id" => $user->id,
            "name" => $input["name"],
            "account_type_id" => 2, //Client type
            "is_master" => 0,
            "parent_id" => $customerParentAccountId,
            "start_balance" => $input["start_balance"],
        ];
    }
    private function getCustomerInput($action, $input)
    {
        $cutomerInput = [
            "address" => $input["address"],
            "phone" => $input["phone"],
        ];
        if ($action == "update") $cutomerInput["id"] = $input["id"];
        return $cutomerInput;
    }
}
