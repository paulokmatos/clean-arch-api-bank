<?php

namespace App\Application\Repositories;

use App\Domain\Entities\Account;
use App\Domain\ValueObjects\AccountBalance;

interface IAccountRepository
{
    public function find(string $accountNumber): Account;

    public function store(Account $account, float $amount): Account;

    public function getBalance(string $accountId): AccountBalance;
}
