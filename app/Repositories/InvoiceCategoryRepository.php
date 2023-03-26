<?php

namespace App\Repositories;

use App\Models\InvoiceCategory;

class InvoiceCategoryRepository
{
    public function getInvoiceCategories($pageSize, $text)
    {
        return InvoiceCategory::where("name", "like", "%$text%")->with(["added_by", "updated_by"])
            ->paginate($pageSize);
    }
    public function create($input)
    {
        return InvoiceCategory::create($input);
    }
    public function update($input)
    {
        $invoiceCategory = InvoiceCategory::find($input["id"]);
        $invoiceCategory->update($input);
        return $invoiceCategory;
    }
    public function delete($id)
    {
        InvoiceCategory::destroy($id);
    }
}
