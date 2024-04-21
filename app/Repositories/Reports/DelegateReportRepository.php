<?php

namespace App\Repositories\Reports;

use App\Commons\Consts\SaleInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Models\Delegate;
use App\Models\TreasuryTransaction;

class DelegateReportRepository
{
    public function getDelegates()
    {
        return Delegate::get();
    }
    public function getDelegate($id)
    {
        return Delegate::with("account")->find($id);
    }
    public function getSaleInvoiceCount($delegateId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::SALE)
            ->whereRelation("saleInvoice", "delegate_id", $delegateId)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }
    public function getSaleInvoiceAmount($delegateId, $from_date, $to_date)
    {
        return $this->getInvoiceAmount(SaleInvoiceType::SALE, $delegateId, $from_date, $to_date);
    }

    public function getSaleReturnInvoiceCount($delegateId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->whereRelation("saleInvoice", "delegate_id", $delegateId)
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->count();
    }
    public function getSaleReturnInvoiceAmount($delegateId, $from_date, $to_date)
    {
        return $this->getInvoiceAmount(SaleInvoiceType::RETURN_ON_GENERAL, $delegateId, $from_date, $to_date);
    }
    public function getExchangeAmount($accountId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("sale_invoice_id")->whereNull("purchase_invoice_id")
            ->where("type", TreasuryTransactionType::EXCHANGE)
            ->where("account_id", $accountId)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getCollectAmount($accountId, $from_date, $to_date)
    {
        return TreasuryTransaction::whereNull("sale_invoice_id")->whereNull("purchase_invoice_id")
            ->where("type", TreasuryTransactionType::COLLECT)
            ->where("account_id", $accountId)
            ->whereBetween("created_at", [$from_date, $to_date])->sum("account_amount");
    }
    public function getSaleInvoiceReport($delegateId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->whereRelation("saleInvoice", "delegate_id", $delegateId)
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::SALE)
            ->whereBetween("created_at", [$from_date, $to_date])->with("saleInvoice")->paginate($page_size);
    }
    public function getSaleReturnInvoiceReport($delegateId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->whereRelation("saleInvoice", "delegate_id", $delegateId)
            ->whereRelation("saleInvoice", "type", SaleInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [$from_date, $to_date])->with("saleInvoice")->paginate($page_size);
    }
    public function getCollectExchangeReport($accountId, $from_date, $to_date, $page_size)
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")->with("moveType")
            ->where("account_id", $accountId)
            ->whereBetween("created_at", [$from_date, $to_date])->paginate($page_size);
    }
    //Commons
    private function getInvoiceAmount($saleInvoiceType, $delegateId, $from_date, $to_date)
    {
        $treasuryTransactions = TreasuryTransaction::whereNotNull("sale_invoice_id")
            ->whereRelation("saleInvoice", "delegate_id", $delegateId)
            ->whereRelation("saleInvoice", "type", $saleInvoiceType)
            ->with("saleInvoice")
            ->whereBetween("created_at", [$from_date, $to_date])
            ->get();
        return $this->getSumDelegateCommission($treasuryTransactions);
    }
    private function getSumDelegateCommission($treasuryTransactions)
    {
        return $treasuryTransactions->sum(function ($treasuryTransaction) {
            $saleInvoice = $treasuryTransaction->saleInvoice;
            if ($saleInvoice->is_delegate_commission_percent) {
                return $saleInvoice->total_sale_price * ($saleInvoice->delegate_commission / 100);
            } else {
                return $saleInvoice->delegate_commission;
            }
        });
    }
}
