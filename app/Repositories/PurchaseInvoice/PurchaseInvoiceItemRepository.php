<?php

namespace App\Repositories\PurchaseInvoice;

use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;

class PurchaseInvoiceItemRepository
{
    public function getPurchaseInvoiceItems($pageSize, $purchaseInvoiceId, $text)
    {
        return
            PurchaseInvoiceItem::when($text, function ($query) use ($text) {
                $query->whereRelation("item", "name", "like", "%$text%");
            })
            ->where("purchase_invoice_id", $purchaseInvoiceId)
            ->with(["updated_by", "added_by", "item","unitOfMeasure"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function getItems()
    {
        return Item::with(["mainUnitOfMeasure", "subUnitOfMeasure"])->get();
    }
    public function create($input)
    {
        return  PurchaseInvoiceItem::create($input);
    }
    public function update($input)
    {
        $purchaseInvoiceItem = PurchaseInvoiceItem::find($input["id"]);
        $purchaseInvoiceItem->update($input);
        return $purchaseInvoiceItem;
    }
    public function updatePurchaseInvoiceTotalPrice($purchaseInvoiceId, $itemTotalPrice)
    {
        $invoicePurchase = PurchaseInvoice::find($purchaseInvoiceId);
        $invoicePurchase->total_purchase_price += $itemTotalPrice;
        $invoicePurchase->save();
    }
    public function delete($id)
    {
        PurchaseInvoiceItem::destroy($id);
    }
    public function isPurchaseInvoiceApproved($id)
    {
        return PurchaseInvoice::find($id)->is_approved;
    }
}
