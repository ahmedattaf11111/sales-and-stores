<?php

namespace App\Services;

use App\Repositories\StoreRepository;

class StoreService
{
    private $storeRepository;
    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }
    public function getStores($pageSize, $text)
    {
        return $this->storeRepository->getStores($pageSize, $text);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["store" => $this->storeRepository->create($input), "user" => $user];
    }
    public function delete($id)
    {
        $this->storeRepository->delete($id);
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["store" => $this->storeRepository->update($input), "user" => $user];
    }
}
