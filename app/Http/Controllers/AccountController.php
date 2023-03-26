<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $accountService;
    public function __construct(AccountService $accountService)
    {
        $this->middleware("auth:admin");
        $this->accountService = $accountService;
    }
    public function index()
    {
        return $this->accountService->getAccounts(
            request()->page_size,
            request()->text,
            request()->account_type_id,
            request()->is_master,
        );
    }
    public function create(CreateAccountRequest $request)
    {
        $user = $request->user();
        return $this->accountService->create($user, $request->validated());
    }
    public function update(UpdateAccountRequest $request)
    {
        $user = $request->user();
        return $this->accountService->update($user, $request->validated());
    }
    public function getAllAccounts()
    {
        //To select from them a parent account
        return $this->accountService->getAllAccounts();
    }
    public function getTypes()
    {
        //To select from them a type
        return $this->accountService->getTypes();
    }
}
