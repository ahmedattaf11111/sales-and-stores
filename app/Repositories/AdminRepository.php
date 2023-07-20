<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Treasury;

class AdminRepository
{
    public function getUsers($pageSize, $text)
    {
        return Admin::when($text, function ($query) use ($text) {
            $query->where("name", "like", "%$text%")->orWhere("email", $text);
        })
            ->where("id","<>",1)
            ->with(["added_by", "updated_by", "treasuries","permissions"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function getAllTreasuries($id)
    {
        return Treasury::when($id, function ($query) use ($id) {
            $query->orWhere("id", $id);
        })->get();
    }
    public function create($input, $treasuries_ids, $permissions)
    {
        $admin = Admin::create($input);
        $admin->treasuries()->sync($treasuries_ids);
        $admin->syncPermissions($permissions);
        return $admin;
    }
    public function update($input, $treasuries_ids, $permissions)
    {
        $admin = Admin::find($input["id"]);
        $admin->update($input);
        $admin->treasuries()->sync($treasuries_ids);
        $admin->syncPermissions($permissions);
        return $admin;
    }
}
