<?php

namespace App\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Application\Repositories\ITransactionRepository;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Transaction;
use App\Domain\ValueObjects\Amount;

class CreateTransactionUseCase
{
    public function __construct(
        protected ITransactionRepository $transactionRepository,
        protected IAccountRepository $accountRepository,
    ) {
        //
    }

    public function execute(
        string $accountNumber,
        IPaymentTax $paymentTax,
        Amount $amount
    ): Transaction {
        $transaction = new Transaction(uniqid('', true), $accountNumber, $paymentTax->getType(), $amount);
        $transaction = $transaction->applyTax($paymentTax);

        $account = $this->accountRepository->findOrFail($accountNumber);
        $accountBalance = $this->accountRepository->getBalance($account->id);

        $accountBalance = $accountBalance->subtract($transaction->amount);

        $this->accountRepository->createOrUpdateBalance($accountBalance);

        return $this->transactionRepository->create($transaction);
    }
}
