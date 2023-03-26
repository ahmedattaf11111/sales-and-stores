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
