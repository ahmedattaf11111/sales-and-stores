<?php

namespace App\Services;

use App\Repositories\AdminPanelSettingRepository;
use Illuminate\Support\Facades\Storage;

class AdminPanelSettingService
{
    private $adminPanelSettingRepository;
    public function __construct(AdminPanelSettingRepository $adminPanelSettingRepository)
    {
        $this->adminPanelSettingRepository = $adminPanelSettingRepository;
    }

    public function getAdminPanelSettings()
    {
        return $this->adminPanelSettingRepository->getAdminPanelSettings();
    }
    public function update($image, $user, $input)
    {
        $this->mergeToInput($input, $image, $user);
        $result = $this->adminPanelSettingRepository->update($input);
        if ($image && $result["old_image"]) Storage::delete($result["old_image"]);
        return ["admin_panel_settings" => $result["admin_panel_settings"], "user" => $user];
    }
    public function getGeneralAccounts()
    {
        return $this->adminPanelSettingRepository->getGeneralAccounts();
    }
    //Commons
    private function mergeToInput(&$input, $image, $user)
    {
        if ($image) $input["image"] = $image->store("");
        $input["updated_by_id"] = $user->id;
        $input["general_alert"] = isset($input["general_alert"]) ? $input["general_alert"] : null;
    }
}
