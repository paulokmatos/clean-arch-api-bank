<?php

namespace App\Infra\Repositories;

use App\Application\Repositories\IAccountRepository;
use App\Domain\Entities\Account;

class AccountRepositoryInMemory implements IAccountRepository
{
    /** @var Account[] */
    private array $accounts = [];

    public function find(string $accountNumber): Account
    {
        if (isset($this->accounts[$accountNumber])) {
            return $this->accounts[$accountNumber];
        }

        throw new \RuntimeException("Account $accountNumber does not exist", 404);
    }

    public function store(Account $account): Account
    {
        $this->accounts[$account->accountNumber] = $account;

        return $account;
    }
}
