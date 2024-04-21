<?php

namespace App\Http\Controllers\SaleInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Http\Requests\SaleInvoice\EditSaleReturnInvoiceRequest;
use App\Http\Requests\SaleReturnInvoice\SaleReturnInvoiceRequest;
use App\Services\SaleReturnInvoice\SaleReturnInvoiceService;

class SaleReturnInvoiceController extends Controller
{
    private $saleInvoiceService;
    public function __construct(SaleReturnInvoiceService $saleInvoiceService)
    {
        $this->middleware("auth:admin");
        $this->saleInvoiceService = $saleInvoiceService;
        $this->middleware("permission:super admin|create sale_invoice")->only("createSaleInvoice");
        $this->middleware("permission:super admin|update sale_invoice")->only("update");
        $this->middleware("permission:super admin|delete sale_invoice")->only("delete");
        $this->middleware("permission:super admin|view sale_invoice")->only(["index","getCustomers","getDelegates",
    "getItems","getStores","getInvoiceCategories"]);
    }

    public function index()
    {
        return $this->saleInvoiceService->getSaleInvoices(
            request()->page_size,
            request()->text,
        );
    }
    public function getCustomers()
    {
        return $this->saleInvoiceService->getCustomers();
    }
    public function getDelegates()
    {
        return $this->saleInvoiceService->getDelegates();
    }
    public function getItems()
    {
        return $this->saleInvoiceService->getItems();
    }
    public function getStores()
    {
        return $this->saleInvoiceService->getStores();
    }
    public function createSaleInvoice(SaleReturnInvoiceRequest $request)
    {
        return $this->saleInvoiceService->createSaleInvoice($request->validated(),request()->user());
    }
    public function update(EditSaleReturnInvoiceRequest $request)
    {
        return $this->saleInvoiceService->update($request->validated(),request()->user());
    }
    public function updateDiscount(DiscountRequest $request)
    {
        return "";
    }
    public function getInvoiceCategories(){
        return $this->saleInvoiceService->getInvoiceCategories();
    }
    public function delete($id){
        $this->saleInvoiceService->delete($id);
    }
}
