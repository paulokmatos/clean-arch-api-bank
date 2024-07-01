<?php

namespace App\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\Amount;

readonly class CreateAccountUseCase
{
    public function __construct(private IAccountRepository $accountRepository)
    {
        //
    }

    /**
     * @throws \Exception
     */
    public function execute(string $accountNumber, float $amount): Account
    {
        $exists = $this->accountRepository->find($accountNumber);

        if($exists) {
            throw new \Exception("Account number already exists", 400);
        }

        $account = new Account(
            id: uniqid('', true),
            accountNumber:  $accountNumber
        );

        return $this->accountRepository->store($account, Amount::fromAmountFloat($amount));
    }
}
