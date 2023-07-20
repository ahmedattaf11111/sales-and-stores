<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceCategoryRequest;
use App\Http\Requests\UpdateInvoiceCategoryRequest;
use App\Services\InvoiceCategoryService;

class SupplierCategoryController extends Controller
{
    private $invoiceCategoryService;
    public function __construct(InvoiceCategoryService $invoiceCategoryService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create supplier_category")->only("create");
        $this->middleware("permission:super admin|update supplier_category")->only("update");
        $this->middleware("permission:super admin|delete supplier_category")->only("delete");
        $this->middleware("permission:super admin|view supplier_category")->only("index");
        $this->invoiceCategoryService = $invoiceCategoryService;
    }
    public function index()
    {
        return $this->invoiceCategoryService->getInvoiceCategories(
            request()->page_size,
            request()->text
        );
    }
    public function create(CreateInvoiceCategoryRequest $request)
    {
        $user = $request->user();
        return $this->invoiceCategoryService->create($user, $request->validated());
    }
    public function update(UpdateInvoiceCategoryRequest $request)
    {
        $user = $request->user();
        return $this->invoiceCategoryService->update($user, $request->validated());
    }
    public function delete($id)
    {
        return $this->invoiceCategoryService->delete($id);
    }
}
