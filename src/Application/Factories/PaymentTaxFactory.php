<?php

namespace App\Application\Factories;

use App\Application\PaymentTaxes\CreditCardTax;
use App\Application\PaymentTaxes\DebitCardTax;
use App\Application\PaymentTaxes\PixTax;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Enums\TransactionTypeEnum;

class PaymentTaxFactory
{
    public static function factory(TransactionTypeEnum $transactionType): IPaymentTax
    {
        return match ($transactionType) {
            TransactionTypeEnum::CREDIT => new CreditCardTax(),
            TransactionTypeEnum::DEBIT => new DebitCardTax(),
            TransactionTypeEnum::PIX => new PixTax(),
        };
    }
}
