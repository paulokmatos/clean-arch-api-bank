<?php

namespace Domain\ValueObjects;

use App\Domain\ValueObjects\Amount;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    public function test_ShouldInstantiate(): void
    {
        $amount = new Amount(value: 100);

        $this->assertEquals(100, $amount->value);
    }
}
