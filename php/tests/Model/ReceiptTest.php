<?php

namespace Tests\Model;

use Supermarket\Model\Discount;
use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\Receipt;
use PHPUnit\Framework\TestCase;

class ReceiptTest extends TestCase
{
    public function test_it_adds_item_prices_and_discount_amounts_together()
    {
        $product = new Product('Foo', ProductUnit::EACH());
        $fooDiscount = Discount::fakeInstance('Foo', 20000.00);
        $barDiscount = Discount::fakeInstance('Bar', 1.00);

        $receipt = new Receipt();

        $receipt->addProduct(product: $product, quantity: 5, price: 5.00, totalPrice: 25.00);
        $receipt->addProduct(product: $product, quantity: 1, price: 1000.00, totalPrice: 1000.00);
        $receipt->addDiscount($fooDiscount);
        $receipt->addDiscount($barDiscount);

        $this->assertSame(21026.0, $receipt->getTotalPrice());
        $this->assertSame('21026.00', (string) $receipt->amount());
    }
}
