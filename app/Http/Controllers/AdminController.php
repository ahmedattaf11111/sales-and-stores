<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    private $userService;
    public function __construct(AdminService $userService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create admin")->only("create");
        $this->middleware("permission:super admin|update admin")->only("update");
        $this->middleware("permission:super admin|view admin")->only(["index", "getAllTreasuries"]);

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
