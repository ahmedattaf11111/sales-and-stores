<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Services\AdminService;

class AdminController extends Controller
{
    private $userService;
    public function __construct(AdminService $userService)
    {
        $this->middleware("auth:admin");
        $this->userService = $userService;
    }
    public function index()
    {
        return $this->userService->getUsers(request()->page_size, request()->text);
    }
    public function create(CreateAdminRequest $request)
    {
        $user = $request->user();
        return $this->userService->create($user, $request->validated());
    }
    public function update(UpdateAdminRequest $request)
    {
        $user = $request->user();
        return $this->userService->update($user, $request->validated());
    }
    public function getAllTreasuries()
    {
        //To select from them a sub treasury
        return $this->userService->getAllTreasuries();
    }
}
