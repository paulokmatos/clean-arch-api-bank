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

    public function find(string $accountNumber): Account
    {
        if (isset($this->accounts[$accountNumber])) {
            return $this->accounts[$accountNumber];
        }

        throw new \RuntimeException("Account $accountNumber does not exist", 404);
    }

    public function getBalance(string $accountId): AccountBalance
    {
        return $this->accountBalances[$accountId];
    }

    /**
     * @throws \Exception
     */
    public function store(Account $account, float $amount): Account
    {
        $this->accounts[$account->accountNumber] = $account;
        $accountBalance = new AccountBalance($account->id, Amount::fromAmountFloat($amount));

        $this->accountBalances[$account->id] = $accountBalance;

        return $account;
    }
}
