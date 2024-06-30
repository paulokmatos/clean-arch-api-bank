<?php

namespace App\Infra\Repositories;

use App\Application\Repositories\ITransactionRepository;
use App\Domain\Entities\Transaction;

class TransactionRepositoryInMemory implements ITransactionRepository
{
    /** @var Transaction[]  */
    private array $transactions = [];

    public function create(Transaction $transaction): Transaction
    {
        $this->transactions[$transaction->id] = $transaction;

        return $transaction;
    }

    public function find(string $transactionId): Transaction
    {
        return $this->transactions[$transactionId];
    }
}
