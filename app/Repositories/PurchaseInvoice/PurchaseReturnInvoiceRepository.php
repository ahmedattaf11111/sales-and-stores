<?php

namespace App\Repositories\PurchaseInvoice;

use App\Commons\Consts\ItemType;
use App\Commons\Consts\PurchaseInvoiceType;
use App\Models\Batch;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Shift;
use App\Models\Store;
use App\Models\Supplier;

class PurchaseReturnInvoiceRepository
{
    public function getPurchaseReturnInvoices($pageSize, $text, $storeId, $supplierId)
    {
        return
            PurchaseInvoice::when($text, function ($query) use ($text) {
                $query->where("id", $text);
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where("store_id", $storeId);
            })
            ->when($supplierId, function ($query) use ($supplierId) {
                $query->where("supplier_id", $supplierId);
            })
            ->where("type", PurchaseInvoiceType::RETURN_ON_GENERAL)
            ->with(["updated_by", "added_by", "approved_by"])
            ->paginate($pageSize);
    }
    public function getStores()
    {
        return Store::get();
    }
    public function getSuppliers()
    {
        return Supplier::with("account")->get();
    }
    public function create($input)
    {
        return PurchaseInvoice::create($input);
    }
    public function update($input)
    {
        $purchaseInvoice = PurchaseInvoice::with("supplier")->find($input["id"]);
        $purchaseInvoice->update($input);
        return $purchaseInvoice;
    }
    public function delete($id)
    {
        PurchaseInvoice::destroy($id);
    }
    public function getCurrentShift($admin)
    {
        return Shift::with("treasury")->where("admin_id", $admin->id)->where("is_finished", 0)->first();
    }
    public function getPurchaseInvoiceItems($purchaseInvoiceId)
    {
        return PurchaseInvoiceItem::with(["item.mainUnitOfMeasure", "unitOfMeasure"])
            ->where("purchase_invoice_id", $purchaseInvoiceId)->get();
    }
    public function insertOrUpdateBatch($user, $itemType, $input)
    {
        $batch = $this->getBatch($input, $itemType);
        if ($batch) {
            $input["quantity"] += $batch->quantity;
            $input["updated_by_id"] = $user->id;
            $batch->update($input);
        } else {
            $input["added_by_id"] = $user->id;
            Batch::create($input);
        }
    }
    //Commons
    private function getBatch($input, $itemType)
    {
        return Batch::where("store_id", $input["store_id"])
            ->where("item_id", $input["item_id"])
            ->where("purchase_price", $input["purchase_price"])
            ->when($itemType == ItemType::HAS_EXPIRATION_DATE, function ($query) use ($input) {
                $query->where("production_date", $input["production_date"])
                    ->where("expiration_date", $input["expiration_date"]);
            })->first();
    }
}
