<?php

namespace App\Http\Controllers\PurchaseInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseInvoice\CreatePurchaseInvoiceItemRequest;
use App\Http\Requests\PurchaseInvoice\UpdatePurchaseInvoiceItemRequest;
use App\Services\PurchaseInvoice\PurchaseInvoiceItemService;

class PurchaseInvoiceItemController extends Controller
{
    private $purchaseInvoiceItemService;
    public function __construct(PurchaseInvoiceItemService $purchaseInvoiceItemService)
    {
        $this->middleware("auth:admin");
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
    public function create(CreatePurchaseInvoiceItemRequest $request)
    {
        $user = $request->user();
        return $this->purchaseInvoiceItemService->create($user, $request->validated());
    }
    public function update(UpdatePurchaseInvoiceItemRequest $request)
    {
        $user = $request->user();
        return $this->purchaseInvoiceItemService->update($user, $request->validated());
    }
    public function delete($purchaseInvoiceId, $itemId)
    {
        $this->purchaseInvoiceItemService->delete($purchaseInvoiceId, $itemId);
    }
    public function isPurchaseInvoiceApproved($id){
        return $this->purchaseInvoiceItemService->isPurchseInvoiceApproved($id);
    }
}
