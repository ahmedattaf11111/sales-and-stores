<?php

namespace App\Services;

use App\Repositories\ShiftRepository;

class ShiftService
{
    private $shiftRepository;
    public function __construct(ShiftRepository $shiftRepository)
    {
        $this->shiftRepository = $shiftRepository;
    }
    public function getShifts($pageSize)
    {
        return $this->shiftRepository->getShifts($pageSize);
    }
    public function getAdminTreasuries($admin)
    {
        return $this->shiftRepository->getAdminTreasuries($admin->id);
    }
    public function create($user, $input)
    {
        $input["admin_id"] = $user->id;
        return ["shift" => $this->shiftRepository->create($input), "user" => $user];
    }
    public function getCurrentShift($admin)
    {
       return $this->shiftRepository->getCurrentShift($admin);
    }
}
