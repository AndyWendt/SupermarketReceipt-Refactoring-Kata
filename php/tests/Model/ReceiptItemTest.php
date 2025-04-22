<?php

namespace Tests\Model;

use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\ReceiptItem;
use PHPUnit\Framework\TestCase;

class ReceiptItemTest extends TestCase
{
    /**
     * @dataProvider kiloDataProvider
     */
    public function test_it_returns_the_right_quantity_string_for_kilo($quantity, $expected)
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::KILO());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: $quantity);

        $result = $receiptItem->quantityRepresentation();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider eachDataProvider
     */
    public function test_it_returns_the_right_quantity_string_for_each($quantity, $expected)
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::EACH());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: $quantity);

        $result = $receiptItem->quantityRepresentation();

        $this->assertSame($expected, $result);
    }

    public function kiloDataProvider()
    {
        return [
            [50.1, '50.100'],
            [50, '50.000'],
            [50.0001, '50.000'],
        ];
    }

    public function eachDataProvider()
    {
        return [
            [255, 'ff'],
            [1234, '4d2'],
            [16, '10'],
            [12, 'c'],
        ];
    }
}
