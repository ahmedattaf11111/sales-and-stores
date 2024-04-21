<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Reports\SupplierReportRepository;

class SupplierReportController extends Controller
{
    private $supplierReportRepository;
    public function __construct(SupplierReportRepository $supplierReportRepository)
    {
        $this->middleware("auth:admin");
        $this->supplierReportRepository = $supplierReportRepository;
    }

    public function getSuppliers()
    {
        return $this->supplierReportRepository->getSuppliers();
    }
    public function getTotalAccountStatementReport()
    {
        $supplier = $this->supplierReportRepository->getSupplier(request()->supplier_id);
        $purchaseInvoiceCount = $this->supplierReportRepository->getPurchaseInvoiceCount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
        $purchaseInvoiceAmount = $this->supplierReportRepository->getPurchaseInvoiceAmount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
        $purchaseReturnInvoiceCount = $this->supplierReportRepository->getPurchaseReturnInvoiceCount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
        $purchaseReturnInvoiceAmount = $this->supplierReportRepository->getPurchaseReturnInvoiceAmount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
        $start_balance = $supplier->account->start_balance;
        $exchangeAmount = $this->supplierReportRepository->getExchangeAmount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
        $collectAmount = $this->supplierReportRepository->getCollectAmount(
            $supplier->account->id,
            request()->from_date,
            request()->to_date
        );
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
        $supplier = $this->supplierReportRepository->getSupplier(request()->supplier_id);
        return $this->supplierReportRepository->getPurchaseInvoiceReport(
            $supplier->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getPurchaseReturnInvoiceReport()
    {
        $supplier = $this->supplierReportRepository->getSupplier(request()->supplier_id);
        return $this->supplierReportRepository->getPurchaseReturnInvoiceReport(
            $supplier->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getCollectExchangeReport()
    {
        $supplier = $this->supplierReportRepository->getSupplier(request()->supplier_id);
        return $this->supplierReportRepository->getCollectExchangeReport(
            $supplier->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
}
