<?php

declare(strict_types=1);

namespace Supermarket\Model;

use Supermarket\Amount;

class Discount
{
    public static function fakeInstance(string $productName, float $discount)
    {
        $product = new Product($productName, ProductUnit::EACH());
        return new Discount($product, "$productName Discount", $discount);
    }

    public function __construct(
        private Product $product,
        private string $description,
        private float $discount
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDiscountAmount(): float
    {
        return $this->discount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function lineDescription()
    {
        return "{$this->getDescription()}({$this->getProduct()->getName()})";
    }

    public function amount(): Amount
    {
        return new Amount(amount: $this->getDiscountAmount());
    }
}
