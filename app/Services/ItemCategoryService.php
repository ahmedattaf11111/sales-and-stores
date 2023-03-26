<?php

namespace App\Services;

use App\Repositories\ItemCategoryRepository;

class ItemCategoryService
{
    private $itemCategoryRepository;
    public function __construct(ItemCategoryRepository $itemCategoryRepository)
    {
        $this->itemCategoryRepository = $itemCategoryRepository;
    }
    public function getItemCategories($pageSize, $text)
    {
        return $this->itemCategoryRepository->getItemCategories($pageSize, $text);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["item_category" => $this->itemCategoryRepository->create($input), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["item_category" => $this->itemCategoryRepository->update($input), "user" => $user];
    }
    public function delete($id)
    {
        $this->itemCategoryRepository->delete($id);
    }
}
