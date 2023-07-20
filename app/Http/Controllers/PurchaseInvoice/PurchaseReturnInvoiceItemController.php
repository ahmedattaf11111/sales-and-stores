<?php

namespace App\Http\Controllers\PurchaseInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReturnPurchaseInvoiceItemRequest;
use App\Http\Requests\PurchaseInvoice\UpdatePurchaseInvoiceItemRequest;
use App\Models\Batch;
use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\UnitOfMeasure;
use App\Services\PurchaseInvoice\PurchaseInvoiceItemService;

class PurchaseReturnInvoiceItemController extends Controller
{
    private $purchaseInvoiceItemService;
    public function __construct(PurchaseInvoiceItemService $purchaseInvoiceItemService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create purchase_return_invoice")->only("create");
        $this->middleware("permission:super admin|update purchase_return_invoice")->only("update");
        $this->middleware("permission:super admin|delete purchase_return_invoice")->only("delete");
        $this->middleware("permission:super admin|view purchase_return_invoice")->only([
            "index",
            "getBatches", "getItems", "isPurchaseInvoiceApproved"
        ]);
        $this->purchaseInvoiceItemService = $purchaseInvoiceItemService;
    }

    public function index($purchaseInvoiceId)
    {
        return $this->purchaseInvoiceItemService->getPurchaseInvoiceItems(
            request()->page_size,
            $purchaseInvoiceId,
            request()->text,
        );
    }

    public function getItems()
    {
        return $this->purchaseInvoiceItemService->getItems();
    }

    public function create(CreateReturnPurchaseInvoiceItemRequest $request)
    {
        $user = $request->user();
        $input = $request->validated();
        $model = $this->purchaseInvoiceItemService->create($user, $input);
        $input["store_id"] = PurchaseInvoice::find($request->purchase_invoice_id)->store_id;
        $this->decreaseBatch($input);
        return $model;
    }


    public function update(UpdatePurchaseInvoiceItemRequest $request)
    {
        $user = $request->user();
        return $this->purchaseInvoiceItemService->update($user, $request->validated());
    }

    public function delete($id)
    {
        $purchseInvoiceItem = PurchaseInvoiceItem::find($id);
        $purchseInvoiceItem->delete();
        $this->increaseBatch($purchseInvoiceItem);
    }

    public function isPurchaseInvoiceApproved($id)
    {
        return $this->purchaseInvoiceItemService->isPurchseInvoiceApproved($id);
    }

    public function getBatches()
    {
        $storeId = PurchaseInvoice::find(request()->purchase_id)->store_id;
        return Batch::where("item_id", request()->item_id)
            ->where("store_id", $storeId)->get();
    }
    //Commons
    public function decreaseBatch($input)
    {
        $item = Item::find($input["item_id"]);
        $unit_of_measure = UnitOfMeasure::find($input["unit_of_measure_id"]);
        $quantity = $unit_of_measure->is_master ? $input["quantity"] : $input["quantity"] / $item->sub_to_main_quantity;
        $batch = Batch::where([
            "store_id" => $input["store_id"], "item_id" => $input["item_id"], "id" => $input["batch_id"]
        ])->first();
        $batch->update(["quantity" => ($batch->quantity - $quantity)]);
    }
    public function increaseBatch($purchseInvoiceItem)
    {
        $item = Item::find($purchseInvoiceItem->item_id);
        $unit_of_measure = UnitOfMeasure::find($purchseInvoiceItem->unit_of_measure_id);
        $quantity = $unit_of_measure->is_master ? $purchseInvoiceItem->quantity : $purchseInvoiceItem->quantity / $item->sub_to_main_quantity;
        $batch = Batch::find($purchseInvoiceItem->batch_id);
        $batch->update(["quantity" => ($batch->quantity + $quantity)]);
    }
}
