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
        $this->middleware("permission:super admin|create collect_exchange_money")->only("create");
        $this->middleware("permission:super admin|view collect_exchange_money")->only(["index","getAccounts"
        ,"getGeneralInfo"]);

        $this->middleware("auth:admin");
    }

    public function index()
    {
        return  $this->treasuryTransactionService->getTreasuryTransactions(
            request()->page_size,
            request()->type,
            request()->text,
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
