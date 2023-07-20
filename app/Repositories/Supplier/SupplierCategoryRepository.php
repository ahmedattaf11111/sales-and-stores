<?php

namespace App\Repositories\Supplier;

use App\Models\SupplierCategory;

class SupplierCategoryRepository
{
    public function getSupplierCategories($pageSize, $text)
    {
        return SupplierCategory::where("name", "like", "%$text%")->with(["added_by", "updated_by"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function create($input)
    {
        return SupplierCategory::create($input);
    }
    public function update($input)
    {
        $supplierCategory = SupplierCategory::find($input["id"]);
        $supplierCategory->update($input);
        return $supplierCategory;
    }
}
