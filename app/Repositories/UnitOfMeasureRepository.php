<?php

namespace App\Repositories;

use App\Models\UnitOfMeasure;

class UnitOfMeasureRepository
{
    public function getUnitOfMeasures($pageSize, $text)
    {
        return UnitOfMeasure::where("name", "like", "%$text%")->with(["added_by", "updated_by"])
            ->paginate($pageSize);
    }
    public function create($input)
    {
        return UnitOfMeasure::create($input);
    }
    public function update($input)
    {
        $unitOfMeasure = UnitOfMeasure::find($input["id"]);
        $unitOfMeasure->update($input);
        return $unitOfMeasure;
    }
    public function delete($id)
    {
        UnitOfMeasure::destroy($id);
    }
}
