<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UnitOfMeasure;

class ItemRepository
{
    public function getItems($pageSize, $text, $itemCategoryId, $type)
    {
        return
            Item::when($text, function ($query) use ($text) {
                $query->where("name", "like", "%$text%");
            })
            ->when($itemCategoryId, function ($query) use ($itemCategoryId) {
                $query->where("item_category_id", $itemCategoryId);
            })
            ->when($type, function ($query) use ($type) {
                $query->where("type", $type);
        })
            ->with(["added_by", "updated_by"])
            ->paginate($pageSize);
    }
    public function getItemCategories()
    {
        return ItemCategory::get();
    }
    public function getUnitOfMeasures()
    {
        return UnitOfMeasure::get();
    }
    public function create($input)
    {
        return Item::create($input);
    }
    public function update($input)
    {
        $item = Item::find($input["id"]);
        $oldImage = $item->getImageName();
        $item->update($input);
        return ["old_image" => $oldImage, "item" => $item];
    }
    public function delete($id)
    {
        Item::destroy($id);
    }
}
