<?php

namespace App\Application\Repositories;

use App\Domain\Entities\Account;

interface IAccountRepository
{
    public function find(string $accountNumber): Account;

    public function store(Account $account): Account;
}
