<?php

namespace App\Repositories;

use App\Models\Treasury;

class TreasuryRepository
{
    public function getTreasuries($pageSize, $text)
    {
        return Treasury::where("name", "like", "%$text%")
            ->with(["added_by", "updated_by"])
            ->orderByDesc("id")
            ->paginate($pageSize);
    }
    public function getAllTreasuries()
    {
        return Treasury::get();
    }
    public function create($input)
    {
        $treasury = Treasury::create($input);
        return $treasury;
    }
    public function update($input)
    {
        $treasury = Treasury::find($input["id"]);
        $treasury->update($input);
        return $treasury;
    }
}
