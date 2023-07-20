<?php

namespace App\Repositories\SaleReturnInvoice;

use App\Models\Batch;
use App\Models\Item;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceItem;
use App\Models\UnitOfMeasure;

class SaleReturnInvoiceItemRepository
{
    public function create($input)
    {
        return  SaleInvoiceItem::create($input);
    }
    public function incrementTotalSale($saleInvoiceId, $itemTotalPrice)
    {
        $invoiceSale = SaleInvoice::find($saleInvoiceId);
        $invoiceSale->total_sale_price += $itemTotalPrice;
        $invoiceSale->save();
    }
    public function decreaseBatchQuantity($input)
    {
        $item = Item::find($input["item_id"]);
        $unit_of_measure = UnitOfMeasure::find($input["unit_of_measure_id"]);
        $quantity = $unit_of_measure->is_master ? $input["quantity"] : $input["quantity"] / $item->sub_to_main_quantity;
        $batch = Batch::where([
            "store_id" => $input["store_id"], "item_id" => $input["item_id"], "id" => $input["batch_id"]
        ])->first();
        $batch->update(["quantity" => ($batch->quantity - $quantity)]);
    }

    public function getInvoiceItems($saleInvoiceId)
    {
        return SaleInvoiceItem::where("sale_invoice_id", $saleInvoiceId)->with(
            "store",
            "unitOfMeasure",
            "item"
        )->get();
    }
    public function deleteItem($id)
    {
        //Delete item
        SaleInvoiceItem::where("id", $id)->delete();
    }
    public function incrementBatchQuantity($input)
    {
        $item = Item::find($input["item_id"]);
        $unit_of_measure = UnitOfMeasure::find($input["unit_of_measure_id"]);
        $quantity = $unit_of_measure->is_master ? $input["quantity"] : $input["quantity"] / $item->sub_to_main_quantity;
        $batch = Batch::where([
            "store_id" => $input["store_id"], "item_id" => $input["item_id"], "id" => $input["batch_id"]
        ])->first();
        $batch->update(["quantity" => ($batch->quantity + $quantity)]);
    }
    public function decrementTotalSale($saleInvoiceId, $itemTotalPrice)
    {
        $invoiceSale = SaleInvoice::find($saleInvoiceId);
        $invoiceSale->total_sale_price -= $itemTotalPrice;
        $invoiceSale->save();
    }
}
