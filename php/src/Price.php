<?php

namespace Supermarket;

class Price
{
    public function __construct(private float $price)
    {
    }

    public function __toString(): string
    {
        return sprintf('%.2F', $this->price);
    }
}