<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Shift;
use App\Models\Treasury;

class ShiftRepository
{
    public function getShifts($pageSize, $text)
    {
        return Shift::with(["admin", "treasury"])
            ->when($text, function ($q) use ($text) {
                $q->where("id", $text);
            })
            ->orderByDesc("id")->paginate($pageSize);
    }
    public function getAdminTreasuries($adminId)
    {
        $admin = Admin::with(["treasuries" => function ($query) {
            $query->with(["shifts" => function ($query) {
                $query->with("admin")->where("is_finished", 0);
            }]);
        }])->find($adminId);
        //Replace shifts array with open_shift object
        $treasuries = $admin->email == "superadmin@admin.com" ? Treasury::with(["shifts" => function ($query) {
            $query->with("admin")->where("is_finished", 0);
        }])->get() : $admin->treasuries;
        return $treasuries->map(function ($treasury) {
            $treasury->open_shift = $treasury->shifts->count() ? $treasury->shifts[0] : null;
            unset($treasury->shifts);
            return $treasury;
        });
    }
    public function create($input)
    {
        return Shift::create($input);
    }
    public function getCurrentShift($admin)
    {
        return Shift::with("treasury")->where("admin_id", $admin->id)->where("is_finished", 0)->first();
    }
}
