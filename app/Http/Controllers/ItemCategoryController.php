<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemCategoryRequest;
use App\Http\Requests\UpdateItemCategoryRequest;
use App\Services\ItemCategoryService;

class ItemCategoryController extends Controller
{
    private $itemCategoryService;
    public function __construct(ItemCategoryService $itemCategoryService)
    {
        $this->middleware("auth:admin");
        $this->itemCategoryService = $itemCategoryService;
    }
    public function index()
    {
        return $this->itemCategoryService->getItemCategories(
            request()->page_size,
            request()->text
        );
    }
    public function create(CreateItemCategoryRequest $request)
    {
        $user = $request->user();
        return $this->itemCategoryService->create($user, $request->validated());
    }
    public function update(UpdateItemCategoryRequest $request)
    {
        $user = $request->user();
        return $this->itemCategoryService->update($user, $request->validated());
    }
    public function delete($id)
    {
        return $this->itemCategoryService->delete($id);
    }
}
