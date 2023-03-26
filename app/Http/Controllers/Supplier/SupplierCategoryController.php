<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\CreateSupplierCategoryRequest;
use App\Http\Requests\Supplier\UpdateSupplierCategoryRequest;
use App\Services\Supplier\SupplierCategoryService;

class SupplierCategoryController extends Controller
{
    private $supplierCategoryService;
    public function __construct(SupplierCategoryService $supplierCategoryService)
    {
        $this->middleware("auth:admin");
        $this->supplierCategoryService = $supplierCategoryService;
    }
    public function index()
    {
        return $this->supplierCategoryService->getSupplierCategories(
            request()->page_size,
            request()->text
        );
    }
    public function create(CreateSupplierCategoryRequest $request)
    {
        $user = $request->user();
        return $this->supplierCategoryService->create($user, $request->validated());
    }
    public function update(UpdateSupplierCategoryRequest $request)
    {
        $user = $request->user();
        return $this->supplierCategoryService->update($user, $request->validated());
    }
}
