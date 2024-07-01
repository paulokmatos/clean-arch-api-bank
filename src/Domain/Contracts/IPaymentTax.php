<?php

namespace App\Domain\Contracts;

use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;

interface IPaymentTax
{
    public function apply(Amount $amount): Amount;

    public function getType(): TransactionTypeEnum;
}
