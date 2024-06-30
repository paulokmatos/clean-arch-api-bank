<?php

namespace Tests\Domain\Entities;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Transaction;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function test_ShouldInstantiate(): void
    {
        $amount = new Amount(200);

        $transaction = new Transaction(
            uniqid('', true),
            accountNumber: "2565",
            transactionType: TransactionTypeEnum::PIX,
            amount: $amount
        );

        $this->assertEquals("2565", $transaction->accountNumber);
        $this->assertEquals(200, $transaction->amount->value);
        $this->assertEquals("P", TransactionTypeEnum::PIX->value);
    }

    /**
     * @throws Exception
     */
    public function test_ShouldApplyTax(): void
    {
        $amount = new Amount(200);

        $paymentTaxMock =  $this->createMock(IPaymentTax::class);
        $paymentTaxMock->expects($this->once())
            ->method('apply')
            ->with($amount)
            ->willReturn(new Amount(210));

        $transaction = new Transaction(
            uniqid('', true),
            accountNumber: "2565",
            transactionType: TransactionTypeEnum::PIX,
            amount: $amount
        );

        $this->assertEquals(200, $transaction->amount->value);
        $transaction = $transaction->applyTax($paymentTaxMock);

        $this->assertEquals(210, $transaction->amount->value);
    }
}
