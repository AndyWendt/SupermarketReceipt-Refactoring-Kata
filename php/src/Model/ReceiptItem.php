<?php

declare(strict_types=1);

namespace Supermarket\Model;

use Supermarket\Amount;

class ReceiptItem
{
    public static function fakeInstance(?Product $product = null, float $quantity = 5, float $price = 10)
    {
        $product = $product ?: Product::fakeInstance();
        $productQuantity = new ProductQuantity($product, $quantity);

        return new self(product: $product, quantity: $quantity, price: $price, productQuantity: $productQuantity);
    }

    public function __construct(
        private Product $product,
        private float $quantity,
        private float $price,
        private ?ProductQuantity $productQuantity = null
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

    public function amount(): Amount
    {
        return new Amount(amount: $this->getPrice());
    }

    public function getTotalPrice(): float
    {
        return $this->quantity * $this->price;
    }

    public function description(): string
    {
        return $this->getProduct()->getName();
    }

    public function totalAmount(): Amount
    {
        return new Amount(amount: $this->getTotalPrice());
    }

    public function quantityRepresentation(): string
    {
        return $this->getProduct()->getUnit()->equals(ProductUnit::EACH()) ?
            sprintf('%x', $this->getQuantity()) :
            sprintf('%.3F', $this->getQuantity());
    }

    public function quantityString(): string
    {
        return $this->amount() . ' * ' . $this->quantityRepresentation();
    }

    public function quantityIsOne()
    {
        return $this->getQuantity() === 1.0;
    }
}
