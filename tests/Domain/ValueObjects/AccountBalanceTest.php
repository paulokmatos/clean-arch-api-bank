<?php

namespace Tests\Domain\ValueObjects;

use App\Domain\ValueObjects\AccountBalance;
use App\Domain\ValueObjects\Amount;
use PHPUnit\Framework\TestCase;

class AccountBalanceTest extends TestCase
{
    public function testShouldInstantiate(): void
    {
        $amount = new Amount(value: 100);
        $accountBalance = new AccountBalance(accountId: 1, amount: $amount);

        $this->assertEquals(1, $accountBalance->accountId);
        $this->assertEquals(100, $accountBalance->amount->value);
    }
}
