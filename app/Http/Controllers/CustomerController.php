<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    private $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->middleware("auth:admin");
        $this->customerService = $customerService;
    }
    public function index()
    {
        return $this->customerService->getCustomers(
            request()->page_size,
            request()->text,
        );
    }
    public function create(CreateCustomerRequest $request)
    {
        $user = $request->user();
        return $this->customerService->create($user, $request->validated());
    }
    public function update(UpdateCustomerRequest $request)
    {
        $user = $request->user();
        return $this->customerService->update($user, $request->validated());
    }
}
