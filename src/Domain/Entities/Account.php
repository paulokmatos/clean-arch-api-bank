<?php

namespace App\Domain\Entities;

readonly class Account
{
    /**
     * @throws \RuntimeException
     */
    public function __construct(
        public int $id,
        public string $accountNumber
    ) {
        if($this->accountNumber <= 0) {
            throw new \RuntimeException("Account number must be greater than 0", 422);
        }
    }
}
