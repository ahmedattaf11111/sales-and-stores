<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Services\Supplier\SupplierService;

class SupplierController extends Controller
{
    private $supplierService;
    public function __construct(SupplierService $supplierService)
    {
        $this->middleware("auth:admin");
        $this->supplierService = $supplierService;
    }
    public function index()
    {
        return $this->supplierService->getSuppliers(
            request()->page_size,
            request()->text,
        );
    }
    public function getCategories(){
        return $this->supplierService->getCategories();
    }
    public function create(CreateSupplierRequest $request)
    {
        $user = $request->user();
        return $this->supplierService->create($user, $request->validated());
    }
    public function update(UpdateSupplierRequest $request)
    {
        $user = $request->user();
        return $this->supplierService->update($user, $request->validated());
    }
}
