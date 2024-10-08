<?php

namespace App\Application\Repositories;

use App\Domain\Entities\Account;
use App\Domain\ValueObjects\AccountBalance;
use App\Domain\ValueObjects\Amount;

interface IAccountRepository
{
    public function find(string $accountNumber): ?Account;

    public function findOrFail(string $accountNumber): Account;

    public function store(Account $account, Amount $amount): Account;

    public function getBalance(string $accountId): AccountBalance;

    public function createOrUpdateBalance(AccountBalance $accountBalance): AccountBalance;
}
