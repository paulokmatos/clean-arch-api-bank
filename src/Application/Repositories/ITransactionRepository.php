<?php

namespace App\Application\Repositories;

use App\Domain\Entities\Transaction;

interface ITransactionRepository
{
    public function create(Transaction $transaction): Transaction;

    public function find(string $transactionId): Transaction;
}
