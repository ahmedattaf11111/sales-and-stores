<?php

namespace App\Repositories\Reports;

use App\Commons\Consts\SaleInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Models\Customer;
use App\Models\TreasuryTransaction;

class CustomerReportRepository
{
    public function getCustomers()
    {
        return Customer::get();
    }
    public function getCustomer($id)
    {
        return Customer::with("account")->find($id);
    }
    public function getSaleInvoiceCount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::SALE)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }
    public function getSaleInvoiceAmount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)
            ->whereRelation(
                "saleInvoice",
                "type",
                SaleInvoiceType::SALE
            )->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }

    public function getSaleReturnInvoiceCount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }

    public function getSaleReturnInvoiceAmount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)
            ->whereRelation(
                "saleInvoice",
                "type",
                SaleInvoiceType::RETURN_ON_GENERAL
            )->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getExchangeAmount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("sale_invoice_id")->whereNull("purchase_invoice_id")
            ->where("type", TreasuryTransactionType::EXCHANGE)
            ->where("account_id", $customerId)

            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getCollectAmount($customerId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("sale_invoice_id")->whereNull("purchase_invoice_id")
            ->where("type", TreasuryTransactionType::COLLECT)
            ->where("account_id", $customerId)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    ////
    public function getSaleInvoiceReport($customerId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)->whereRelation("saleInvoice", "type", SaleInvoiceType::SALE)
            ->whereBetween("created_at", [$from_date, $to_date])->with("saleInvoice")->paginate($page_size);
    }
    public function getSaleReturnInvoiceReport($customerId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->where("account_id", $customerId)->whereRelation("saleInvoice", "type", SaleInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->with("saleInvoice")->paginate($page_size);
    }
    public function getCollectExchangeReport($customerId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")->with("moveType")
            ->where("account_id", $customerId)
            ->whereBetween("created_at", [$from_date, $to_date])->paginate($page_size);
    }
}
