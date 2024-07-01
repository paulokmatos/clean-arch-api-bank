<?php

namespace App\Application\PaymentTaxes;

use App\Domain\Contracts\IPaymentTax;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;

class PixTax implements IPaymentTax
{
    public function apply(Amount $amount): Amount
    {
        return $amount;
    }

    public function getType(): TransactionTypeEnum
    {
        return TransactionTypeEnum::PIX;
    }
}
