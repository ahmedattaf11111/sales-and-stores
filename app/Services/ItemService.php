<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    private $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
    public function getItems($pageSize, $text, $itemCategoryId, $type)
    {
        return $this->itemRepository->getItems($pageSize, $text, $itemCategoryId, $type);
    }
    public function getItemCategories()
    {
        return $this->itemRepository->getItemCategories();
    }
    public function getUnitOfMeasures()
    {
        return $this->itemRepository->getUnitOfMeasures();
    }
    public function create($image, $user, $input)
    {
        if ($image) $input["image"] = $image->store("");
        $input["added_by_id"] = $user->id;
        return ["item" =>  $this->itemRepository->create($input), "user" => $user];
    }
    public function update($image, $user, $input)
    {
        if ($image) $input["image"] = $image->store("");
        $result = $this->itemRepository->update($input);
        if ($image && $result["old_image"]) Storage::delete($result["old_image"]);
        return ["item" => $result["item"], "user" => $user];
    }
    public function delete($id)
    {
        $this->itemRepository->delete($id);
    }
}
