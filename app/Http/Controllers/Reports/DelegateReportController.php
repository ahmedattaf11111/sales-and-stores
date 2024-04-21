<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Reports\DelegateReportRepository;

class DelegateReportController extends Controller
{
    private $delegateReportRepository;
    public function __construct(DelegateReportRepository $delegateReportRepository)
    {
        // $this->middleware("auth:admin");
        $this->delegateReportRepository = $delegateReportRepository;
    }

    public function getDelegates()
    {
        return $this->delegateReportRepository->getDelegates();
    }
    public function getTotalAccountStatementReport()
    {
        $delegate = $this->delegateReportRepository->getDelegate(request()->delegate_id);
        $saleInvoiceCount = $this->delegateReportRepository->getSaleInvoiceCount(
            $delegate->id,
            request()->from_date,
            request()->to_date
        );
        $saleInvoiceAmount = $this->delegateReportRepository->getSaleInvoiceAmount(
            $delegate->id,
            request()->from_date,
            request()->to_date
        );
        $saleReturnInvoiceCount = $this->delegateReportRepository->getSaleReturnInvoiceCount(
            $delegate->id,
            request()->from_date,
            request()->to_date
        );
        $saleReturnInvoiceAmount = $this->delegateReportRepository->getSaleReturnInvoiceAmount(
            $delegate->id,
            request()->from_date,
            request()->to_date
        );
        $start_balance = $delegate->account->start_balance;
        $exchangeAmount = $this->delegateReportRepository->getExchangeAmount(
            $delegate->account->id,
            request()->from_date,
            request()->to_date
        );
        $collectAmount = $this->delegateReportRepository->getCollectAmount(
            $delegate->account->id,
            request()->from_date,
            request()->to_date
        );
        return response()->json([[
            "delegate_id" => $delegate->id,
            "start_balance" => $start_balance,
            "sale_invoices_count" => $saleInvoiceCount,
            "sale_invoices_amount" => $saleInvoiceAmount,
            "sale_invoice_retruns_count" => $saleReturnInvoiceCount,
            "sale_invoice_retruns_amount" => $saleReturnInvoiceAmount,
            "total_exchange_amount" => $exchangeAmount,
            "total_collect_amount" => $collectAmount,
            "total_amount" => $start_balance + $saleInvoiceAmount + $exchangeAmount - $saleReturnInvoiceAmount + $collectAmount,
        ]]);
    }
    public function getSaleInvoiceReport()
    {
        return $this->delegateReportRepository->getSaleInvoiceReport(
            request()->delegate_id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getSaleReturnInvoiceReport()
    {

        return $this->delegateReportRepository->getSaleReturnInvoiceReport(
            request()->delegate_id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getCollectExchangeReport()
    {
        $delegate = $this->delegateReportRepository->getDelegate(request()->delegate_id);
        return $this->delegateReportRepository->getCollectExchangeReport(
            request()->$delegate->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
}
