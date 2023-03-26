<?php

namespace App\Services;

use App\Repositories\TreasuryTransactionRepository;
use App\Services\Commons\TransactionCommonService;

class TreasuryTransactionService
{
    private $treasuryTransactionRepository;
    private $transactionCommonService;
    public function __construct(
        TreasuryTransactionRepository $treasuryTransactionRepository,
        TransactionCommonService $transactionCommonService,
    ) {
        $this->treasuryTransactionRepository = $treasuryTransactionRepository;
        $this->transactionCommonService = $transactionCommonService;
    }
    public function getTreasuryTransactions($pageSize, $type)
    {
        return $this->treasuryTransactionRepository->getTreasuryTransactions($pageSize, $type);
    }
    public function getAccounts()
    {
        return $this->treasuryTransactionRepository->getAccounts();
    }
    public function getGeneralInfo($admin)
    {
        return $this->treasuryTransactionRepository->getGeneralInfo($admin);
    }
    public function create($admin, $input)
    {
        return $this->transactionCommonService->createTransaction($admin, $input);
    }
}
