<?php

namespace Tests\Domain\Entities;

use App\Application\PaymentTaxes\CreditCardTax;
use App\Application\PaymentTaxes\DebitCardTax;
use App\Application\PaymentTaxes\PixTax;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Transaction;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('taxDataProvider')]
    public function test_ShouldApplyTax(IPaymentTax $paymentTax, int $value, int $valueWithTax): void
    {
        $amount = new Amount($value);

        $transaction = new Transaction(
            uniqid('', true),
            accountNumber: "2565",
            transactionType: TransactionTypeEnum::PIX,
            amount: $amount
        );

        $this->assertEquals($value, $transaction->amount->value);
        $transaction = $transaction->applyTax($paymentTax);

        $this->assertEquals($valueWithTax, $transaction->amount->value);
    }

    /**
     * @return array<string, array{0: IPaymentTax, 1: int, 2: int}>
     */
    public static function taxDataProvider(): array
    {
        return [
            'should apply credit card tax' => [new CreditCardTax(), 200, 210],
            'should apply debit card tax' => [new DebitCardTax(), 200, 206],
            'should apply pix tax' => [new PixTax(), 200, 200],
        ];
    }
}
