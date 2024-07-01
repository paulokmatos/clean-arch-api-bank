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
        $accountBalance = new AccountBalance(accountId: $id = "uid", amount: $amount);

        $this->assertEquals($id, $accountBalance->accountId);
        $this->assertEquals(100, $accountBalance->amount->value);
    }

    public function testShouldThrowsExceptionWhenBalanceIsNegative(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Account balance must not be negative");
        $this->expectExceptionCode(404);

        $amount = new Amount(value: -100);
        new AccountBalance(accountId: "uid", amount: $amount);
    }
}
