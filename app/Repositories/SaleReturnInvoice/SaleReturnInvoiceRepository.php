<?php

namespace App\Repositories\SaleReturnInvoice;

use App\Commons\Consts\SaleInvoiceType;
use App\Commons\Consts\SalePriceType;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\Delegate;
use App\Models\InvoiceCategory;
use App\Models\Item;
use App\Models\SaleInvoice;
use App\Models\Store;

class SaleReturnInvoiceRepository
{
    public function getSaleInvoices($pageSize, $text)
    {
        return
            SaleInvoice::when($text, function ($query) use ($text) {
                $query->where("id", $text);
            })->where("type",SaleInvoiceType::RETURN_ON_GENERAL)->orderByDesc("id")->with("customer.account","delegate.account", "invoiceCategory", "added_by", "approved_by")->paginate($pageSize);
    }
    public function getCustomers()
    {
        return Customer::with("account")->get();
    }
    public function getDelegates()
    {
        return Delegate::with("account")->get();
    }
    public function getItems()
    {
        return Item::with(["mainUnitOfMeasure", "subUnitOfMeasure", "batches"])->get();
    }
    public function getStores()
    {
        return Store::get();
    }
    public function createSaleInvoice($input)
    {
        $delegate = Delegate::find($input["delegate_id"]);
        $input["is_delegate_commission_percent"] = $delegate->percent_type;
        if ($input["sale_price_type"] == SalePriceType::RETAIL) {
            $input["delegate_commission"] = $delegate->percent_sales_retail_commission;
        } else if ($input["sale_price_type"] == SalePriceType::WHOLESALE) {
            $input["delegate_commission"] = $delegate->percent_wholesale_commission;
        } else if ($input["sale_price_type"] == SalePriceType::HALF_WHOLESALE) {
            $input["delegate_commission"] = $delegate->percent_halfsale_commission;
        }
        return SaleInvoice::create($input);
    }
    public function update($input)
    {
        $saleInvoice = SaleInvoice::find($input["id"]);
        $saleInvoice->update($input);
        return $saleInvoice;
    }
    public function getInvoiceCategories()
    {
        return InvoiceCategory::get();
    }
    public function getInvoice($id)
    {
        return SaleInvoice::find($id);
    }
    public function delete($id)
    {
        SaleInvoice::destroy($id);
    }
}
