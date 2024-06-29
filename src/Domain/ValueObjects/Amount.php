<?php

namespace App\Domain\ValueObjects;

readonly class Amount
{
    public function __construct(public int $value)
    {
        //
    }
}
