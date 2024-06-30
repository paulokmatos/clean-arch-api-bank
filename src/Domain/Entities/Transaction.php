<?php

namespace App\Domain\Entities;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;

readonly class Transaction
{
    public function __construct(
        public string $accountNumber,
        public TransactionTypeEnum $transactionType,
        public Amount $amount
    ) {
        //
    }

    public function applyTax(IPaymentTax $paymentTax): self
    {
        return new self(
            accountNumber: $this->accountNumber,
            transactionType: $this->transactionType,
            amount: $paymentTax->apply($this->amount)
        );
    }
}
