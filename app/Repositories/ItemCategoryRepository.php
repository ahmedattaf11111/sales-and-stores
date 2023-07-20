<?php

namespace App\Repositories;

use App\Models\ItemCategory;

class ItemCategoryRepository
{
    public function getItemCategories($pageSize, $text)
    {
        return ItemCategory::where("name", "like", "%$text%")->with(["added_by", "updated_by"])
        ->orderByDesc("id")->paginate($pageSize);
    }
    public function create($input)
    {
        return ItemCategory::create($input);
    }
    public function update($input)
    {
        $itemCategory = ItemCategory::find($input["id"]);
        $itemCategory->update($input);
        return $itemCategory;
    }
    public function delete($id)
    {
        ItemCategory::destroy($id);
    }
}
