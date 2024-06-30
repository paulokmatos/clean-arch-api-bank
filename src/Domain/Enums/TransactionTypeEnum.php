<?php

namespace App\Domain\Enums;

enum TransactionTypeEnum: string
{
    case PIX = "P";
    case CREDIT = "C";
    case DEBIT = "D";
}
