<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Reports\CustomerReportRepository;

class CustomerReportController extends Controller
{
    private $customerReportRepository;
    public function __construct(CustomerReportRepository $customerReportRepository)
    {
        $this->middleware("auth:admin");
        $this->customerReportRepository = $customerReportRepository;
    }

    public function getCustomers()
    {
        return $this->customerReportRepository->getCustomers();
    }
    public function getTotalAccountStatementReport()
    {
        $customer = $this->customerReportRepository->getCustomer(request()->customer_id);
        $saleInvoiceCount = $this->customerReportRepository->getSaleInvoiceCount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        $saleInvoiceAmount = $this->customerReportRepository->getSaleInvoiceAmount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        $saleReturnInvoiceCount = $this->customerReportRepository->getSaleReturnInvoiceCount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        $saleReturnInvoiceAmount = $this->customerReportRepository->getSaleReturnInvoiceAmount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        $start_balance = $customer->account->start_balance;
        $exchangeAmount = $this->customerReportRepository->getExchangeAmount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        $collectAmount = $this->customerReportRepository->getCollectAmount(
            $customer->account->id,
            request()->from_date,
            request()->to_date
        );
        return response()->json([[
            "customer_id" => $customer->id,
            "start_balance" => $start_balance,
            "sale_invoices_count" => $saleInvoiceCount,
            "sale_invoices_amount" => $saleInvoiceAmount,
            "sale_invoice_retruns_count" => $saleReturnInvoiceCount,
            "sale_invoice_retruns_amount" => $saleReturnInvoiceAmount,
            "total_exchange_amount" => $exchangeAmount,
            "total_collect_amount" => $collectAmount,
            "total_amount" => $start_balance + $saleInvoiceAmount + $exchangeAmount + $saleReturnInvoiceAmount + $collectAmount,
        ]]);
    }
    public function getSaleInvoiceReport()
    {
        $customer = $this->customerReportRepository->getCustomer(request()->customer_id);
        return $this->customerReportRepository->getSaleInvoiceReport(
            $customer->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getSaleReturnInvoiceReport()
    {
        $customer = $this->customerReportRepository->getCustomer(request()->customer_id);
        $this->customerReportRepository->getSaleReturnInvoiceReport(
            $customer->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
    public function getCollectExchangeReport()
    {
        $customer = $this->customerReportRepository->getCustomer(request()->customer_id);
        return $this->customerReportRepository->getCollectExchangeReport(
            $customer->account->id,
            request()->from_date,
            request()->to_date,
            request()->page_size
        );
    }
}
