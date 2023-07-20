<?php

namespace App\Services;

use App\Repositories\AdminRepository;

class AdminService
{
    private $userRepository;
    public function __construct(AdminRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function getUsers($pageSize, $text)
    {
        return $this->userRepository->getUsers($pageSize, $text);
    }
    public function getAllTreasuries()
    {
        return $this->userRepository->getAllTreasuries(request()->id);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        $treasuries_ids = $input["treasuries_ids"];
        unset($input["treasuries_ids"]);
        $permissions = $input["permissions"];
        unset($input["permissions"]);
        return ["admin" => $this->userRepository->create($input, $treasuries_ids,$permissions), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        $treasuries_ids = $input["treasuries_ids"];
        $permissions = $input["permissions"];
        unset($input["permissions"]);
        unset($input["treasuries_ids"]);
        return ["admin" => $this->userRepository->update($input, $treasuries_ids,$permissions), "user" => $user];
    }
}
