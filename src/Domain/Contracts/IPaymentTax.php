<?php

namespace App\Domain\Contracts;

use App\Domain\ValueObjects\Amount;

interface IPaymentTax
{
    public function apply(Amount $amount): Amount;
}
