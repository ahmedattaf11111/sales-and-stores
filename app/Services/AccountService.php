<?php

namespace App\Services;

use App\Models\AccountType;
use App\Repositories\AccountRepository;

class AccountService
{
    private $accountRepository;
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }
    public function getAccounts($pageSize, $text, $accountTypeId, $isMaster)
    {
        return $this->accountRepository->getAccounts($pageSize, $text, $accountTypeId, $isMaster);
    }
    public function getAllAccounts()
    {
        return $this->accountRepository->getAllAccounts();
    }
    public function getTypes()
    {
        return $this->accountRepository->getTypes();
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["account" => $this->accountRepository->create($input), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["account" => $this->accountRepository->update($input), "user" => $user];
    }
}
