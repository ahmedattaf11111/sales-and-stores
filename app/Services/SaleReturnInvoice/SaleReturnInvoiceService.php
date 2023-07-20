<?php

namespace App\Services\SaleReturnInvoice;

use App\Commons\Consts\SaleInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Repositories\SaleReturnInvoice\SaleReturnInvoiceRepository;
use App\Services\Commons\TransactionCommonService;

class SaleReturnInvoiceService
{
    private $saleInvoiceRepository;
    private $transactionCommonService;
    public function __construct(
        SaleReturnInvoiceRepository $saleInvoiceRepository,
        TransactionCommonService $transactionCommonService
    ) {
        $this->saleInvoiceRepository = $saleInvoiceRepository;
        $this->transactionCommonService = $transactionCommonService;
    }
    public function getSaleInvoices($pageSize, $text)
    {
        return $this->saleInvoiceRepository->getSaleInvoices(
            $pageSize,
            $text
        );
    }
    public function getCustomers()
    {
        return $this->saleInvoiceRepository->getCustomers();
    }
    public function getDelegates()
    {
        return $this->saleInvoiceRepository->getDelegates();
    }
    public function getItems()
    {
        return $this->saleInvoiceRepository->getItems();
    }
    public function getStores()
    {
        return $this->saleInvoiceRepository->getStores();
    }
    public function createSaleInvoice($input, $user)
    {
        $input["added_by_id"] = $user->id;
        $input["type"] = SaleInvoiceType::RETURN_ON_GENERAL;
        return [
            "user" => $user,
            "sale_invoice" => $this->saleInvoiceRepository->createSaleInvoice($input),
        ];
    }
    public function delete($id){
        $this->saleInvoiceRepository->delete($id);
    }
    public function update($input, $user)
    {
        $input["is_approved"] = 1;
        $input["approved_by_id"] = $user->id;
        $input["total_sale_price"] = $this->saleInvoiceRepository->getInvoice($input["id"])
            ->total_sale_price;
        $this->transactionCommonService->createTransaction(
            $user,
            $this->getTransactionInput($input)
        );
        return [
            "user" => $user,
            "sale_invoice" => $this->saleInvoiceRepository->update($input),
        ];
    }
    public function getInvoiceCategories()
    {
        return $this->saleInvoiceRepository->getInvoiceCategories();
    }
    //Commons
    private function getTransactionInput($input)
    {
        return [
            "type" => TreasuryTransactionType::EXCHANGE,
            "account_id" => $input["customer_id"],
            "amount" => $input["is_deferred"] ? $input["paid_amount"]
                : $this->getTotalAmount($input),
            "sale_invoice_id" => $input["id"],
            "move_type_id" => 4 //صرف نظير مرتجع مبيعات
        ];
    }
    private function getTotalAmount($input)
    {
        return $input["total_sale_price"] + $this->getTaxValue($input)
            - $this->getDiscountValue($input);
    }
    private function getTaxValue($input)
    {
        return $input["is_tax_percent"]
            ? ($input["tax"]/ 100.00) * $input["total_sale_price"]
            : $input["tax"];
    }
    private function getDiscountValue($input)
    {
        return $input["is_discount_percent"]
            ? ($input["discount"]/ 100.00) * $input["total_sale_price"]
            : $input["discount"];
    }
}
