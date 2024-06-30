<?php

namespace Tests\Domain\ValueObjects;

use App\Domain\ValueObjects\Amount;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    public function test_ShouldInstantiate(): void
    {
        $amount = new Amount(value: 100);

        $this->assertEquals(100, $amount->value);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldConvertToCents(): void
    {
        $amount = Amount::fromAmountFloat(50.85);

        $this->assertEquals(5085, $amount->value);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldThrowExceptionWhenDoesHaveMoreThan2Decimals(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("The amount value must be lesser than 2 decimals");
        $this->expectExceptionCode(422);

        Amount::fromAmountFloat(150.222);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldAcceptWithoutDecimals(): void
    {
        $amount = Amount::fromAmountFloat(150);

        $this->assertEquals(15000, $amount->value);
    }
}
