<?php

namespace App\Domain\ValueObjects;

readonly class AccountBalance
{
    public function __construct(
        public string $accountId,
        public Amount $amount
    ) {
        if($this->amount->value < 0) {
            throw new \RuntimeException("Account balance must not be negative", 403);
        }
    }
}
