<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminPanelSettingRequest;
use App\Services\AdminPanelSettingService;

class AdminPanelSettingController extends Controller
{
    private $adminPanelSettingService;
    public function __construct(AdminPanelSettingService $adminPanelSettingRepository)
    {
        $this->middleware("auth:admin");
        $this->middleware("permission:super admin|update general_setting")->only("update");
        $this->adminPanelSettingService = $adminPanelSettingRepository;
    }
    public function index()
    {
        return $this->adminPanelSettingService->getAdminPanelSettings();
    }
    public function update(AdminPanelSettingRequest $request)
    {
        return $this->adminPanelSettingService->update(
            $request->file("image"),
            request()->user(),
            $request->validated()
        );
    }
    public function getGeneralAccounts(){
        return $this->adminPanelSettingService->getGeneralAccounts();
    }
}
