<?php

namespace App\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Application\Repositories\ITransactionRepository;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Transaction;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;

class CreateTransactionUseCase
{
    public function __construct(
        protected ITransactionRepository $transactionRepository,
        protected IAccountRepository $accountRepository,
        protected IPaymentTax $paymentTax
    ) {
        //
    }

    public function execute(
        string $accountNumber,
        TransactionTypeEnum $transactionType,
        Amount $amount
    ): Transaction {
        $transaction = new Transaction(uniqid('', true), $accountNumber, $transactionType, $amount);
        $transaction = $transaction->applyTax($this->paymentTax);

        $account = $this->accountRepository->findOrFail($accountNumber);
        $accountBalance = $this->accountRepository->getBalance($account->id);

        $accountBalance = $accountBalance->subtract($transaction->amount);

        $this->accountRepository->createOrUpdateBalance($accountBalance);

        return $this->transactionRepository->create($transaction);
    }
}
