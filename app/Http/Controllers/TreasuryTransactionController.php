<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreasuryTransactionRequest;
use App\Services\TreasuryTransactionService;

class TreasuryTransactionController extends Controller
{
    private $treasuryTransactionService;
    public function __construct(TreasuryTransactionService $treasuryTransactionService)
    {
        $this->treasuryTransactionService = $treasuryTransactionService;
        $this->middleware("auth:admin");
    }

    public function index()
    {
        return  $this->treasuryTransactionService->getTreasuryTransactions(
            request()->page_size,
            request()->type
        );
    }
    public function getAccounts()
    {
        return $this->treasuryTransactionService->getAccounts();
    }
    public function create(TreasuryTransactionRequest $request)
    {
        return $this->treasuryTransactionService->create($request->user(),$request->validated());
    }
    public function getGeneralInfo(){
        return $this->treasuryTransactionService->getGeneralInfo(request()->user());
    }
}
