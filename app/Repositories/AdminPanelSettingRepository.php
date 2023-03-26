<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AdminPanelSetting;

class AdminPanelSettingRepository
{
    public function getAdminPanelSettings()
    {
        return AdminPanelSetting::with("updated_by")->first();
    }
    public function update($input)
    {
        $adminPanelSettings = AdminPanelSetting::first();
        $oldImage = $adminPanelSettings->getImageName();
        $adminPanelSettings->update($input);
        return ["old_image" => $oldImage, "admin_panel_settings" => $adminPanelSettings];
    }
    public function getGeneralAccounts()
    {
        return Account::where("account_type_id", 6)->get();
    }
}
