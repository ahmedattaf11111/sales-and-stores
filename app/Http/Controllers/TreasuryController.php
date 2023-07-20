<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTreasuryRequest;
use App\Http\Requests\UpdateTreasuryRequest;
use App\Services\TreasuryService;

class TreasuryController extends Controller
{
    private $treasuryService;
    public function __construct(TreasuryService $treasuryService)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|create treasury")->only("create");
        $this->middleware("permission:super admin|update treasury")->only("update");
        $this->middleware("permission:super admin|view treasury")->only(["index", "getAllTreasuries"]);
        $this->treasuryService = $treasuryService;
    }
    public function index()
    {
        return $this->treasuryService->getTreasuries(request()->page_size, request()->text);
    }
    public function create(CreateTreasuryRequest $request)
    {
        $user = $request->user();
        return $this->treasuryService->create($user, $request->validated());
    }
    public function update(UpdateTreasuryRequest $request)
    {
        $user = $request->user();
        return $this->treasuryService->update($user, $request->validated());
    }
    public function getAllTreasuries()
    {
        //To select from them a sub treasury
        return $this->treasuryService->getAllTreasuries();
    }
}
