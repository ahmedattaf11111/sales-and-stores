<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceCategoryRequest;
use App\Http\Requests\UpdateInvoiceCategoryRequest;
use App\Services\InvoiceCategoryService;

class InvoiceCategoryController extends Controller
{
    private $invoiceCategoryService;
    public function __construct(InvoiceCategoryService $invoiceCategoryService)
    {
        $this->middleware("auth:admin");
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
