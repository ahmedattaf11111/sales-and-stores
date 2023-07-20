<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDelegateRequest;
use App\Http\Requests\UpdateDelegateRequest;
use App\Services\DelegateService;

class DelegateController extends Controller
{
    private $delegateService;
    public function __construct(DelegateService $delegateService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create delegate_account")->only("create");
        $this->middleware("permission:super admin|update delegate_account")->only("update");
        $this->middleware("permission:super admin|view delegate_account")->only("index");
        $this->delegateService = $delegateService;
    }
    public function index()
    {
        return $this->delegateService->getDelegates(
            request()->page_size,
            request()->text,
        );
    }
    public function create(CreateDelegateRequest $request)
    {
        $user = $request->user();
        return $this->delegateService->create($user, $request->validated());
    }
    public function update(UpdateDelegateRequest $request)
    {
        $user = $request->user();
        return $this->delegateService->update($user, $request->validated());
    }
}
