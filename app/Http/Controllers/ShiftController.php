<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Services\ShiftService;

class ShiftController extends Controller
{
    private $shiftService;
    public function __construct(ShiftService $shiftService)
    {
        $this->middleware("auth:admin");
        $this->shiftService = $shiftService;
    }
    public function index()
    {
        return $this->shiftService->getShifts(request()->page_size);
    }
    public function create(ShiftRequest $request)
    {
        $user = $request->user();
        return $this->shiftService->create($user, $request->validated());
    }
    public function getAdminTreasuries()
    {
        $user = request()->user();
        return $this->shiftService->getAdminTreasuries($user);
    }
    public function getCurrentShift()
    {
        $admin = request()->user();
        return $this->shiftService->getCurrentShift($admin);
    }
}
