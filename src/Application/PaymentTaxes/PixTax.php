<?php

namespace App\Application\PaymentTaxes;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\ValueObjects\Amount;

class PixTax implements IPaymentTax
{
    public function apply(Amount $amount): Amount
    {
        return $amount;
    }
}
