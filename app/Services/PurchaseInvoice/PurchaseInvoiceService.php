<?php

namespace App\Services\PurchaseInvoice;

use App\Commons\Consts\ItemType;
use App\Commons\Consts\PurchaseInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceRepository;
use App\Services\Commons\TransactionCommonService;

class PurchaseInvoiceService
{
    private $purchaseInvoiceRepository;
    private $transactionCommonService;
    public function __construct(
        PurchaseInvoiceRepository $purchaseInvoiceRepository,
        TransactionCommonService $transactionCommonService
    ) {
        $this->purchaseInvoiceRepository = $purchaseInvoiceRepository;
        $this->transactionCommonService = $transactionCommonService;
    }
    public function getPurchaseInvoices($pageSize, $text, $storeId, $supplierId)
    {
        return $this->purchaseInvoiceRepository->getPurchaseInvoices(
            $pageSize,
            $text,
            $storeId,
            $supplierId
        );
    }
    public function getSuppliers()
    {
        return $this->purchaseInvoiceRepository->getSuppliers();
    }
    public function getStores()
    {
        return $this->purchaseInvoiceRepository->getStores();
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        $input["type"] = PurchaseInvoiceType::PURCHASE;
        return [
            "purchase_invoice" =>  $this->purchaseInvoiceRepository->create($input),
            "user" => $user,
        ];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return [
            "purchase_invoice" =>  $this->purchaseInvoiceRepository->update($input),
            "user" => $user
        ];
    }
    public function approve($user, $input)
    {
        $input["is_approved"] = 1;
        $input["approved_by_id"] = $user->id;
        $purchaseInvoice = $this->purchaseInvoiceRepository->update($input);
        $this->transactionCommonService->createTransaction(
            $user,
            $this->getTransactionInput($purchaseInvoice)
        );
        $this->insertOrUpdateBatches($user, $purchaseInvoice);
        return [
            "user" => $user,
            "purchase_invoice" => $purchaseInvoice,
        ];
    }
    public function delete($id)
    {
        $this->purchaseInvoiceRepository->delete($id);
    }
    public function getCurrentShift($admin)
    {
        return $this->purchaseInvoiceRepository->getCurrentShift($admin);
    }
    //Commons
    private function getTransactionInput($purchaseInvoice)
    {
        return [
            "type" => TreasuryTransactionType::EXCHANGE,
            "account_id" => $purchaseInvoice->supplier->account_id,
            "amount" => $purchaseInvoice->is_deferred ? $purchaseInvoice->paid_amount
                : $this->getTotalAmount($purchaseInvoice),
            "purchase_invoice_id" => $purchaseInvoice->id,
            "move_type_id" => 3 //صرف نظير مشتريات من مورد
        ];
    }
    private function getTotalAmount($purchaseInvoice)
    {
        return $purchaseInvoice->total_purchase_price + $this->getTaxValue($purchaseInvoice)
            - $this->getDiscountValue($purchaseInvoice);
    }
    private function getTaxValue($purchaseInvoice)
    {
        return $purchaseInvoice->is_tax_percent
            ? ($purchaseInvoice->tax / 100.00) * $purchaseInvoice->total_purchase_price
            : $purchaseInvoice->tax;
    }
    private function getDiscountValue($purchaseInvoice)
    {
        return $purchaseInvoice->is_discount_percent
            ? ($purchaseInvoice->discount / 100.00) * $purchaseInvoice->total_purchase_price
            : $purchaseInvoice->discount;
    }
    private function insertOrUpdateBatches($user, $purchaseInvoice)
    {
        $purchaseInvoiceItems = $this->purchaseInvoiceRepository
            ->getPurchaseInvoiceItems($purchaseInvoice->id);
        foreach ($purchaseInvoiceItems as $purchaseInvoiceItem) {
            $this->purchaseInvoiceRepository->insertOrUpdateBatch(
                $user,
                $purchaseInvoiceItem->item->type,
                $this->getBatchInput(
                    $purchaseInvoiceItem,
                    $purchaseInvoice->store_id
                )
            );
        }
    }
    private function getBatchInput($purchaseInvoiceItem, $storeId)
    {
        if (!$purchaseInvoiceItem->unitOfMeasure->is_master) {
            $input = $this->convertsubToMainUnit($purchaseInvoiceItem);
        } else {
            $input["unit_of_measure_id"] = $purchaseInvoiceItem->unit_of_measure_id;
            $input["quantity"] = $purchaseInvoiceItem->quantity;
            $input["purchase_price"] = $purchaseInvoiceItem->purchase_price;
        }
        $input["production_date"] = $purchaseInvoiceItem->item->type == ItemType::HAS_EXPIRATION_DATE ?
            $purchaseInvoiceItem->production_date : null;
        $input["expiration_date"] = $purchaseInvoiceItem->item->type == ItemType::HAS_EXPIRATION_DATE ?
            $purchaseInvoiceItem->expiration_date : null;
        $input["store_id"] = $storeId;
        $input["item_id"] = $purchaseInvoiceItem->item_id;
        $input["total_purchase_price"] = $input["purchase_price"] * $input["quantity"];
        return $input;
    }
    private function convertsubToMainUnit($purchaseInvoiceItem)
    {
        $input["unit_of_measure_id"] = $purchaseInvoiceItem->item->mainUnitOfMeasure->id;
        $input["quantity"] = $purchaseInvoiceItem->quantity / $purchaseInvoiceItem->item->sub_to_main_quantity;
        $input["purchase_price"] = $purchaseInvoiceItem->item->sub_to_main_quantity * $purchaseInvoiceItem->purchase_price;
        return $input;
    }
}
