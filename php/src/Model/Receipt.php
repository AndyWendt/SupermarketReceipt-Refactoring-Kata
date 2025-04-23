<?php

declare(strict_types=1);

namespace Supermarket\Model;

use Supermarket\Amount;

class Receipt
{
    /**
     * @var Discount[]
     */
    private array $discounts = [];

    /**
     * @var ReceiptItem[]
     */
    private array $items = [];

    public function getTotalPrice(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->getTotalPrice();
        }
        foreach ($this->discounts as $discount) {
            $total += $discount->getDiscountAmount();
        }
        return $total;
    }

    public function amount(): Amount
    {
        return new Amount(amount: $this->getTotalPrice());
    }

    public function addProduct(Product $product, float $quantity, float $price, ?ProductQuantity $productQuantity = null): void
    {
        if ($productQuantity) {
            $product = $productQuantity->getProduct();
            $quantity = $productQuantity->getQuantity();
        }

        $this->items[] = new ReceiptItem($product, $quantity, $price);
    }

    /**
     * @return ReceiptItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addDiscount(Discount $discount): void
    {
        $this->discounts[] = $discount;
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }
}
