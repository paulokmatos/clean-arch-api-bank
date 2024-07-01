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
        $exploded = explode('.', (string) $value);
        $integer = (int) $exploded[0];
        $cents = $exploded[1] ?? "0";

        if(strlen($cents) > 2) {
            throw new \Exception("The amount value must be lesser than 2 decimals", 422);
        }

        $cents = str_pad($cents, 2, "0");

        return new self((int) ($integer.$cents));
    }

    public function sum(int $value): self
    {
        return new self($this->value + $value);
    }

    public function subtract(int $value): self
    {
        return new self($this->value - $value);
    }

    public function parseFloat(): string
    {
        return number_format(($this->value / 100), 2);
    }
}
