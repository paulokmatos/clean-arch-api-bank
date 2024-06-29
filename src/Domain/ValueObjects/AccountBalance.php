<?php

namespace App\Domain\ValueObjects;

readonly class AccountBalance
{
    public function __construct(
        public int $accountId,
        public Amount $amount
    ) {
        //
    }
}
