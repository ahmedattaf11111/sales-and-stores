<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Services\StoreService;

class StoreController extends Controller
{
    private $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->middleware("auth:admin");
        $this->storeService = $storeService;
    }
    public function index()
    {
        return $this->storeService->getStores(request()->page_size, request()->text);
    }
    public function create(CreateStoreRequest $request)
    {
        $user = $request->user();
        return $this->storeService->create($user, $request->validated());
    }
    public function update(UpdateStoreRequest $request)
    {
        $user = $request->user();
        return $this->storeService->update($user, $request->validated());
    }
    public function delete($id)
    {
        $this->storeService->delete($id);
    }
}
