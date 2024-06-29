<?php

namespace App\Domain\ValueObjects;

readonly class Amount
{
    public function __construct(public int $value)
    {
        //
    }

    /**
     * @throws \Exception
     */
    public static function fromAmountFloat(float $value): self
    {
        [$integer, $cents] = explode('.', (string) $value);

        if(strlen($cents) > 2) {
            throw new \Exception("The amount value must be lesser than 2 decimals", 422);
        }

        $cents = str_pad($cents, 2, "0");

        return new self((int) ($integer.$cents));
    }
}
