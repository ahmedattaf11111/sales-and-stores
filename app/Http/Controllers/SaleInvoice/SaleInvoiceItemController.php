<?php

namespace App\Http\Controllers\SaleInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleInvoice\CreateSaleInvoiceItemRequest;
use App\Models\Batch;
use App\Services\SaleInvoice\SaleInvoiceItemService;
use Illuminate\Http\Request;

class SaleInvoiceItemController extends Controller
{
    private $saleInvoiceItemService;
    public function __construct(SaleInvoiceItemService $saleInvoiceItemService)
    {
        $this->middleware("auth:admin");
        $this->saleInvoiceItemService = $saleInvoiceItemService;
        $this->middleware("permission:super admin|create sale_invoice")->only("create");
        $this->middleware("permission:super admin|delete sale_invoice")->only("deleteItem");
        $this->middleware("permission:super admin|view sale_invoice")->only(["getInvoiceItems","getBatches"]);
    }

    public function create(CreateSaleInvoiceItemRequest $request)
    {
        return $this->saleInvoiceItemService->create($request->user(), $request->validated());
    }

    public function getInvoiceItems($saleInvoiceId)
    {
        return $this->saleInvoiceItemService->getInvoiceItems($saleInvoiceId);
    }

    public function deleteItem(Request $request)
    {
        return $this->saleInvoiceItemService->deleteItem($request->input());
    }

    public function getBatches($storeId, $itemId)
    {
        return Batch::where("item_id", $itemId)
            ->where("store_id", $storeId)->get();
    }
    
}
