<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use App\Services\ShiftService;
use Carbon\Carbon;

class ShiftController extends Controller
{
    private $shiftService;
    public function __construct(ShiftService $shiftService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create shift")->only("create");
        $this->middleware("permission:super admin|view shift")->only(["index", "getAdminTreasuries", "getCurrentShift"]);
        $this->middleware("permission:super admin|close shift")->only("close");
        $this->shiftService = $shiftService;
    }
    public function index()
    {
        return $this->shiftService->getShifts(request()->page_size,request()->text);
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
    public function close()
    {
        $admin = request()->user();
        $shift = $this->shiftService->getCurrentShift($admin);
        $shift->update(["is_finished" => 1, "closed_at" => Carbon::now()]);
    }
}
