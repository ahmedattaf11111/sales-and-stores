<?php

namespace App\Http\Controllers\Reports;

use App\Commons\Consts\PurchaseInvoiceType;
use App\Commons\Consts\TreasuryTransactionType;
use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\TreasuryTransaction;

class SupplierReportController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function getSuppliers()
    {
        return Supplier::with("account")->get();
    }
    public function getTotalAccountStatementReport()
    {
        $supplier = Supplier::find(request()->supplier_id);
        $purchaseInvoiceCount = TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", $supplier->id)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::PURCHASE)
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->count();
        $purchaseInvoiceAmount = TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", $supplier->id)->whereRelation(
                "purchaseInvoice",
                "type",
                PurchaseInvoiceType::PURCHASE
            )->whereBetween("created_at", [request()->from_date, request()->to_date])->sum("account_amount");
        $purchaseReturnInvoiceCount = TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", $supplier->id)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::RETURN_ON_GENERAL)->whereBetween("created_at", [request()->from_date, request()->to_date])->count();
        $purchaseReturnInvoiceAmount = TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", $supplier->id)->whereRelation(
                "purchaseInvoice",
                "type",
                PurchaseInvoiceType::RETURN_ON_GENERAL
            )->whereBetween("created_at", [request()->from_date, request()->to_date])->sum("account_amount");
        $start_balance = $supplier->account->start_balance;
        $exchangeAmount = TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")
            ->where("type", TreasuryTransactionType::EXCHANGE)
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->sum("account_amount");
        $collectAmount = TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")
            ->where("type", TreasuryTransactionType::COLLECT)
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->sum("account_amount");
        return response()->json([[
            "supplier_id" => $supplier->id,
            "start_balance" => $start_balance,
            "purchase_invoices_count" => $purchaseInvoiceCount,
            "purchase_invoices_amount" => $purchaseInvoiceAmount,
            "purchase_invoice_retruns_count" => $purchaseReturnInvoiceCount,
            "purchase_invoice_retruns_amount" => $purchaseReturnInvoiceAmount,
            "total_exchange_amount" => $exchangeAmount,
            "total_collect_amount" => $collectAmount,
            "total_amount" => $start_balance + $purchaseInvoiceAmount + $exchangeAmount + $purchaseReturnInvoiceAmount + $collectAmount,
        ]]);
    }
    public function getPurchaseInvoiceReport()
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", request()->supplier_id)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::PURCHASE)
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->with("purchaseInvoice")->paginate(request()->page_size);
    }
    public function getPurchaseReturnInvoiceReport()
    {
        return TreasuryTransaction::whereNotNull("purchase_invoice_id")
            ->whereRelation("purchaseInvoice", "supplier_id", request()->supplier_id)->whereRelation("purchaseInvoice", "type", PurchaseInvoiceType::RETURN_ON_GENERAL)
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->with("purchaseInvoice")->paginate(request()->page_size);
    }
    public function getCollectExchangeReport()
    {
        return TreasuryTransaction::whereNull("purchase_invoice_id")->whereNull("sale_invoice_id")->with("moveType")
            ->whereBetween("created_at", [request()->from_date, request()->to_date])->paginate(request()->page_size);
    }
}
