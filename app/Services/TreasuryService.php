<?php

namespace App\Services;

use App\Repositories\TreasuryRepository;

class TreasuryService
{
    private $treasuryRepository;
    public function __construct(TreasuryRepository $treasuryRepository)
    {
        $this->treasuryRepository = $treasuryRepository;
    }
    public function getTreasuries($pageSize, $text)
    {
        return $this->treasuryRepository->getTreasuries($pageSize, $text);
    }
    public function getAllTreasuries()
    {
        return $this->treasuryRepository->getAllTreasuries();
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["treasury" => $this->treasuryRepository->create($input), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["treasury" => $this->treasuryRepository->update($input), "user" => $user];
    }
}
