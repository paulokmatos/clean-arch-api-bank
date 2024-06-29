<?php

namespace Tests\Domain\Entities;

use App\Domain\Entities\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function test_ShouldInstantiate(): void
    {
        $account = new Account(id: 1, accountNumber: "203");

        $this->assertEquals(1, $account->id);
        $this->assertEquals(203, $account->accountNumber);
    }

    public function test_ShouldThrowsExceptionWhenAccountNumberIsNotUnsigned(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Account number must be greater than 0");
        $this->expectExceptionCode(422);

        new Account(id: 1, accountNumber: "0");
    }

    public function test_ShouldThrowsExceptionWhenAccountNumberIsNotNumeric(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Account number must be numeric");
        $this->expectExceptionCode(422);

        new Account(id: 1, accountNumber: "abc123");
    }
}
