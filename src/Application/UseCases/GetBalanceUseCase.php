<?php

namespace App\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Domain\ValueObjects\AccountBalance;

readonly class GetBalanceUseCase
{
    public function __construct(private IAccountRepository $accountRepository)
    {
        //
    }

    public function execute(string $accountNumber): AccountBalance
    {
        $account = $this->accountRepository->findOrFail($accountNumber);

        return $this->accountRepository->getBalance($account->id);
    }
}
