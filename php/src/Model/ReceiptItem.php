<?php

declare(strict_types=1);

namespace Supermarket\Model;

use Supermarket\Amount;

class ReceiptItem
{
    public static function fakeInstance(?Product $product = null, float $quantity = 5, float $price = 10, float $totalPrice = 50)
    {
        $product = $product ?: Product::fakeInstance();
        return new self(product: $product, quantity: $quantity, price: $price, totalPrice: $totalPrice);
    }

    public function __construct(
        private Product $product,
        private float $quantity,
        private float $price,
        private float $totalPrice
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function description(): string
    {
        return $this->getProduct()->getName();
    }

    public function totalAmount(): Amount
    {
        return new Amount(amount: $this->getTotalPrice());
    }

    public function quantityString(): string
    {
        return $this->getProduct()->getUnit()->equals(ProductUnit::EACH()) ?
            sprintf('%x', $this->getQuantity()) :
            sprintf('%.3F', $this->getQuantity());
    }
}
