<?php

namespace App\Application\PaymentTaxes;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\ValueObjects\Amount;

class CreditCardTax implements IPaymentTax
{
    public const float CREDIT_TAX = 0.05;

    public function apply(Amount $amount): Amount
    {
        return $amount->sum((int) ($amount->value * self::CREDIT_TAX));
    }
}
