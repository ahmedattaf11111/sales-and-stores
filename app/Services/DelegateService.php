<?php

namespace App\Services;

use App\Repositories\DelegateRepository;

class DelegateService
{
    private $delegateRepository;
    public function __construct(DelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }
    public function getDelegates($pageSize, $text)
    {
        return $this->delegateRepository->getDelegates($pageSize, $text);
    }
    public function create($user, $input)
    {
        $delegateParentAccountId = $this->delegateRepository->getDelegateParentAccountId();
        $result = $this->delegateRepository->create(
            $this->getAccountInput("create", $user, $delegateParentAccountId, $input),
            $this->getDelegateInput("create", $input)
        );
        return [
            "delegate" => $result["delegate"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    public function update($user, $input)
    {
        $delegateParentAccountId = $this->delegateRepository->getDelegateParentAccountId();
        $result = $this->delegateRepository->update(
            $this->getAccountInput("update", $user, $delegateParentAccountId, $input),
            $this->getDelegateInput("update", $input)
        );
        return [
            "delegate" => $result["delegate"],
            "account" => $result["account"],
            "user" => $user
        ];
    }
    //Commons
    private function getAccountInput($action, $user, $delegateParentAccountId, $input)
    {
        return [
            $action == "create" ? "added_by_id" : "updated_by_id" => $user->id,
            "name" => $input["name"],
            "account_type_id" => 2, //Client type
            "is_master" => 0,
            "parent_id" => $delegateParentAccountId,
            "start_balance" => $input["start_balance"],
        ];
    }
    private function getDelegateInput($action, $input)
    {
        $delegateInput = [
            "address" => $input["address"],
            "phone" => $input["phone"],
            "percent_type"=>$input["percent_type"],
            "percent_collect_commission" => $input["percent_collect_commission"],
            "percent_sales_retail_commission" =>$input["percent_sales_retail_commission"],
            "percent_halfsale_commission" => $input["percent_halfsale_commission"],
            "percent_wholesale_commission" => $input["percent_wholesale_commission"],
        ];
        if ($action == "update") $delegateInput["id"] = $input["id"];
        return $delegateInput;
    }
}
