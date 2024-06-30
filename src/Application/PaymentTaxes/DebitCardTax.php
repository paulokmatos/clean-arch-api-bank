<?php

namespace App\Application\PaymentTaxes;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\ValueObjects\Amount;

class DebitCardTax implements IPaymentTax
{
    public const float DEBIT_TAX = 0.03;

    public function apply(Amount $amount): Amount
    {
        return $amount->sum((int) ($amount->value * self::DEBIT_TAX));
    }
}
