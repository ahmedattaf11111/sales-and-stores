<?php

namespace App\Services;

use App\Repositories\UnitOfMeasureRepository;

class UnitOfMeasureService
{
    private $unitOfMeasureRepository;
    public function __construct(UnitOfMeasureRepository $unitOfMeasureRepository)
    {
        $this->unitOfMeasureRepository = $unitOfMeasureRepository;
    }
    public function getUnitOfMeasures($pageSize, $text)
    {
        return $this->unitOfMeasureRepository->getUnitOfMeasures($pageSize, $text);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["unit_of_measure" => $this->unitOfMeasureRepository->create($input), "user" => $user];
    }
    public function delete($id)
    {
        $this->unitOfMeasureRepository->delete($id);
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["unit_of_measure" => $this->unitOfMeasureRepository->update($input), "user" => $user];
    }
}
