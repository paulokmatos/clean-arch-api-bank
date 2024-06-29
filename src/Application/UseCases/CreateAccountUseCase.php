<?php

namespace App\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Domain\Entities\Account;

readonly class CreateAccountUseCase
{
    public function __construct(private IAccountRepository $accountRepository)
    {
        //
    }

    public function execute(string $accountNumber, float $amount): void
    {
        $account = new Account(
            id: uniqid('', true),
            accountNumber:  $accountNumber
        );

        $this->accountRepository->store($account);
    }
}
