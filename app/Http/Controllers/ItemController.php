<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\ItemService;

class ItemController extends Controller
{
    private $itemService;
    public function __construct(ItemService $itemService)
    {
        $this->middleware("auth:admin");
        $this->itemService = $itemService;
    }
    public function index()
    {
        return $this->itemService->getItems(
            request()->page_size,
            request()->text,
            request()->item_category_id,
            request()->type,
        );
    }
    public function getItemCategories()
    {
        return $this->itemService->getItemCategories();
    }
    public function getUnitOfMeasures()
    {
        return $this->itemService->getUnitOfMeasures();
    }
    public function create(CreateItemRequest $request)
    {
        return $this->itemService->create(
            $request->file("image"),
            request()->user(),
            $request->validated()
        );
    }
    public function update(UpdateItemRequest $request)
    {
        return $this->itemService->update(
            $request->file("image"),
            request()->user(),
            $request->validated()
        );
    }
    public function delete($id)
    {
        $this->itemService->delete($id);
    }
}
