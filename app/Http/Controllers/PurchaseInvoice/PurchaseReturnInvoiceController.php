<?php

namespace App\Http\Controllers\PurchaseInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePurchaseInvoiceRequest;
use App\Http\Requests\CreatePurchaseReturnInvoiceRequest;
use App\Http\Requests\UpdatePurchaseReturnInvoiceRequest;
use App\Models\Batch;
use App\Models\Item;
use App\Models\PurchaseInvoiceItem;
use App\Models\UnitOfMeasure;
use App\Services\PurchaseInvoice\PurchaseReturnInvoiceService;

class PurchaseReturnInvoiceController extends Controller
{
    private $purchaseReturnInvoiceService;
    public function __construct(PurchaseReturnInvoiceService $purchaseReturnInvoiceService)
    {
        $this->middleware("auth:admin");
        $this->purchaseReturnInvoiceService = $purchaseReturnInvoiceService;
    }
    public function index()
    {
        return $this->purchaseReturnInvoiceService->getPurchaseReturnInvoices(
            request()->page_size,
            request()->text,
            request()->store_id,
            request()->supplier_id,
        );
    }
    public function getStores()
    {
        return $this->purchaseReturnInvoiceService->getStores();
    }
    public function getSuppliers()
    {
        return $this->purchaseReturnInvoiceService->getSuppliers();
    }
    public function create(CreatePurchaseReturnInvoiceRequest $request)
    {
        $user = $request->user();
        return $this->purchaseReturnInvoiceService->create($user, $request->validated());
    }
    public function update(UpdatePurchaseReturnInvoiceRequest $request)
    {
        $user = $request->user();
        return $this->purchaseReturnInvoiceService->update($user, $request->validated());
    }
    public function approve(ApprovePurchaseInvoiceRequest $request)
    {
        return $this->purchaseReturnInvoiceService->approve($request->user(), $request->validated());
    }
    public function delete($id)
    {
        $items = PurchaseInvoiceItem::where("purchase_invoice_id", $id)->get();
        foreach($items as $item){   
            $this->increaseBatch($item);
        }
        $this->purchaseReturnInvoiceService->delete($id);
    }
    public function getCurrentShift()
    {
        return $this->purchaseReturnInvoiceService->getCurrentShift(request()->user());
    }
    //Commons
    public function increaseBatch($purchseInvoiceItem)
    {
        $item = Item::find($purchseInvoiceItem->item_id);
        $unit_of_measure = UnitOfMeasure::find($purchseInvoiceItem->unit_of_measure_id);
        $quantity = $unit_of_measure->is_master ? $purchseInvoiceItem->quantity : $purchseInvoiceItem->quantity / $item->sub_to_main_quantity;
        $batch = Batch::find($purchseInvoiceItem->batch_id);
        $batch->update(["quantity" => ($batch->quantity + $quantity)]);
    }
}
