<?php

namespace Supermarket;

class Amount
{
    public function __construct(private float $amount)
    {
    }

    public function __toString(): string
    {
        return sprintf('%.2F', $this->amount);
    }
}