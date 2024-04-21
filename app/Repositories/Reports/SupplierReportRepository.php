<?php

namespace App\Repositories\Reports;

use App\Commons\Consts\PurchaseInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Models\Supplier;
use App\Models\TreasuryTransaction;

class SupplierReportRepository
{
    public function getSuppliers()
    {
        return Supplier::with("account")->get();
    }
    public function getSupplier($id)
    {
        return Supplier::with("account")->find($id);
    }
    public function getPurchaseInvoiceCount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)
            ->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::PURCHASE)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }
    public function getPurchaseInvoiceAmount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)
            ->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::PURCHASE)
            ->whereBetween("created_at", [$from_date, $to_date])
            ->sum("account_amount");
    }
    public function getPurchaseReturnInvoiceCount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)
            ->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }

    public function getPurchaseReturnInvoiceAmount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)
            ->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getExchangeAmount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")
            ->where("type", TreasuryTransactionType::EXCHANGE)
            ->where("account_id", $supplierId)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getCollectAmount($supplierId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")
            ->where("type", TreasuryTransactionType::COLLECT)
            ->where("account_id", $supplierId)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getPurchaseInvoiceReport($supplierId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::PURCHASE)
            ->whereBetween("created_at", [$from_date, $to_date])->with("purchaseInvoice")->paginate($page_size);
    }
    public function getPurchaseReturnInvoiceReport($supplierId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->where("account_id", $supplierId)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->with("purchaseInvoice")->paginate($page_size);
    }
    public function getCollectExchangeReport($supplierId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")->with("moveType")
            ->where("account_id", $supplierId)
            ->whereBetween("created_at", [$from_date, $to_date])->paginate($page_size);
    }
}
