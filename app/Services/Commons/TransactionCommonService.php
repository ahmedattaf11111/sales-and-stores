<?php

namespace App\Services\Commons;

use App\Commons\Consts\TreasuryTransactionType;
use App\Models\MoveType;
use App\Repositories\Commons\TransactionCommonRepository;

class TransactionCommonService
{
    private $transactionCommonRepository;
    public function __construct(TransactionCommonRepository $transactionCommonRepository)
    {
        $this->transactionCommonRepository = $transactionCommonRepository;
    }
    public function createTransaction($admin, $input)
    {
        $shift = $this->transactionCommonRepository->getShift($admin);
        //isset($input["move_type_id"]) mean If manually set move_type_id and not selected according to 
        //acount type , and this happen in case of approve pruchases invoice
        if (!isset($input["move_type_id"])) {
            $account = $this->transactionCommonRepository->getAccount($input["account_id"]);
            $moveType = $this->getMoveType($account, $input["type"]);
        }
        $moveTypeId=!isset($input["move_type_id"]) ? $moveType->id : $input["move_type_id"];
        $treasuryTransaction = $this->transactionCommonRepository
            ->create($this->getTransactionInput($input, $admin, $shift, $moveTypeId));
        if (!isset($input["move_type_id"])) {
            return [
                "treasury_transaction" => $treasuryTransaction,
                "user" => $admin,
                "shift" => $shift, //Shift has its treasury
                "account" => isset($account) ? $account : null,
                "move_type" => isset($moveType) ? $moveType : null,
            ];
        }
    }
    //Commons
    private function getTransactionInput($input, $admin, $shift, $moveTypeId)
    {
        $input["added_by_id"] = $admin->id;
        $input["shift_id"] = $shift->id;
        $input["move_type_id"] = $moveTypeId;
        $input["amount"] = $input["type"] == TreasuryTransactionType::COLLECT ? $input["amount"]
            : -1 * $input["amount"];
        $input["account_amount"] = -1 * $input["amount"];
        $this->setReceiptNumber($input, $shift->treasury);
        return $input;
    }
    private function setReceiptNumber(&$input, $treasury)
    {
        if ($input["type"] == TreasuryTransactionType::COLLECT) {
            $input["collect_receipt_number"] = $this->transactionCommonRepository
                ->inecrementLastCollectionReceipt($treasury);
        }
        if ($input["type"] == TreasuryTransactionType::EXCHANGE) {
            $input["exchange_receipt_number"] = $this->transactionCommonRepository
                ->inecrementLastExchangeReceipt($treasury);
        }
    }
    private function getMoveType($account, $type)
    {
        if ($type == TreasuryTransactionType::COLLECT) {
            //Supplier Account
            if ($account->account_type_id == 1) return MoveType::find(10);
            //Customer Account
            else if ($account->account_type_id == 2) return MoveType::find(11);
            //Bank Account
            else if ($account->account_type_id == 4) return MoveType::find(8);
            else return MoveType::find(7);
        }
        if ($type == TreasuryTransactionType::EXCHANGE) {
            //Supplier Account
            if ($account->account_type_id == 1) return MoveType::find(3);
            //Customer Account
            else if ($account->account_type_id == 2) return MoveType::find(4);
            //Bank Account
            else if ($account->account_type_id == 4) return MoveType::find(2);
            else return MoveType::find(5);
        }
    }
}
