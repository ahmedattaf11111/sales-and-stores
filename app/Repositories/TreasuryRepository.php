<?php

namespace App\Repositories;

use App\Models\Treasury;

class TreasuryRepository
{
    public function getTreasuries($pageSize, $text)
    {
        return Treasury::where("name", "like", "%$text%")
            ->with(["added_by", "updated_by", "subTreasuries"])
            ->paginate($pageSize);
    }
    public function getAllTreasuries()
    {
        return Treasury::where("is_master", 0)->get();
    }
    public function create($input, $treasuries_ids)
    {
        $treasury = Treasury::create($input);
        $treasury->subTreasuries()->sync($treasuries_ids);
        return $treasury;
    }
    public function update($input, $treasuries_ids)
    {
        $treasury = Treasury::find($input["id"]);
        $treasury->update($input);
        $treasury->subTreasuries()->sync($treasuries_ids);
        return $treasury;
    }
}
