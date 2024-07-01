<?php

namespace App\Infra\Repositories;

use App\Application\Repositories\IAccountRepository;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\AccountBalance;
use App\Domain\ValueObjects\Amount;

class AccountRepositoryInMemory implements IAccountRepository
{
    /** @var Account[] */
    private array $accounts = [];
    /** @var AccountBalance[] */
    private array $accountBalances = [];

    public function find(string $accountNumber): ?Account
    {
        return $this->accounts[$accountNumber] ?? null;
    }

    public function findOrFail(string $accountNumber): Account
    {
        $account = $this->find($accountNumber);

        if(!$account) {
            throw new \RuntimeException("Account $accountNumber does not exist", 404);
        }

        return $account;

    }

    public function getBalance(string $accountId): AccountBalance
    {
        return $this->accountBalances[$accountId];
    }

    /**
     * @throws \Exception
     */
    public function store(Account $account, Amount $amount): Account
    {
        $this->accounts[$account->accountNumber] = $account;
        $accountBalance = new AccountBalance($account->id, $amount);

        $this->createOrUpdateBalance($accountBalance);

        return $account;
    }

    public function createOrUpdateBalance(AccountBalance $accountBalance): AccountBalance
    {
        return $this->accountBalances[$accountBalance->accountId] = $accountBalance;
    }
}
