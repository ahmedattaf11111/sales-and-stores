<?php

namespace App\Services\PurchaseInvoice;

use App\Commons\Consts\ItemType;
use App\Commons\Consts\PurchaseInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Repositories\PurchaseInvoice\PurchaseReturnInvoiceRepository;
use App\Services\Commons\TransactionCommonService;

class PurchaseReturnInvoiceService
{
    private $purchaseReturnInvoiceRepository;
    private $transactionCommonService;
    public function __construct(
        PurchaseReturnInvoiceRepository $purchaseReturnInvoiceRepository,
        TransactionCommonService $transactionCommonService
    ) {
        $this->purchaseReturnInvoiceRepository = $purchaseReturnInvoiceRepository;
        $this->transactionCommonService = $transactionCommonService;
    }
    public function getPurchaseReturnInvoices($pageSize, $text, $storeId, $supplierId)
    {
        return $this->purchaseReturnInvoiceRepository->getPurchaseReturnInvoices(
            $pageSize,
            $text,
            $storeId,
            $supplierId
        );
    }
    public function getSuppliers()
    {
        return $this->purchaseReturnInvoiceRepository->getSuppliers();
    }
    public function getStores()
    {
        return $this->purchaseReturnInvoiceRepository->getStores();
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        $input["type"] = PurchaseInvoiceType::RETURN_ON_GENERAL;
        return [
            "purchase_return_invoice" =>  $this->purchaseReturnInvoiceRepository->create($input),
            "user" => $user,
        ];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return [
            "purchase_return_invoice" =>  $this->purchaseReturnInvoiceRepository->update($input),
            "user" => $user
        ];
    }
    public function approve($user, $input)
    {
        $input["is_approved"] = 1;
        $input["approved_by_id"] = $user->id;
        $purchaseInvoice = $this->purchaseReturnInvoiceRepository->update($input);
        $this->transactionCommonService->createTransaction(
            $user,
            $this->getTransactionInput($purchaseInvoice)
        );
        return [
            "user" => $user,
            "purchase_invoice" => $purchaseInvoice,
        ];
    }
    public function delete($id)
    {
        $this->purchaseReturnInvoiceRepository->delete($id);
    }
    public function getCurrentShift($admin)
    {
        return $this->purchaseReturnInvoiceRepository->getCurrentShift($admin);
    }
    //Commons
    private function getTransactionInput($purchaseInvoice)
    {
        return [
            "type" => TreasuryTransactionType::COLLECT,
            "account_id" => $purchaseInvoice->supplier->account_id,
            "amount" => $purchaseInvoice->is_deferred ? $purchaseInvoice->paid_amount
                : $this->getTotalAmount($purchaseInvoice),
            "purchase_invoice_id" => $purchaseInvoice->id,
            "move_type_id" => 10 //تحصيل نظير مرتجع مشتريات الي مورد
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
}
