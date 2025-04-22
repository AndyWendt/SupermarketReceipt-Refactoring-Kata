<?php

namespace Tests\Model;

use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\ReceiptItem;
use PHPUnit\Framework\TestCase;

class ReceiptItemTest extends TestCase
{

    public function test_it_returns_the_calculated_total_price()
    {
        $instance = ReceiptItem::fakeInstance(quantity: 100, price: 2.00);
        $this->assertEquals(200, $instance->getTotalPrice());
    }

    /**
     * @dataProvider kiloDataProvider
     */
    public function test_it_returns_the_right_quantity_representation_for_kilo($quantity, $expected)
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::KILO());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: $quantity);

        $result = $receiptItem->quantityRepresentation();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider eachDataProvider
     */
    public function test_it_returns_the_right_quantity_representation_for_each($quantity, $expected)
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::EACH());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: $quantity);

        $result = $receiptItem->quantityRepresentation();

        $this->assertSame($expected, $result);
    }

    public function test_it_returns_the_right_quantity_string_for_each()
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::EACH());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: 12);

        $result = $receiptItem->quantityString();

        $this->assertSame('10.00 * c', $result);
    }

    /**
     * @dataProvider quantityIsOneDataProvider
     */
    public function test_it_determines_if_it_quantity_equals_one($quantity, $expected)
    {
        $product = Product::fakeInstance(productUnit: ProductUnit::EACH());
        $receiptItem = ReceiptItem::fakeInstance(product: $product, quantity: $quantity);

        $this->assertSame($expected, $receiptItem->quantityIsOne());
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
            [5, '5'],
        ];
    }

    public function quantityIsOneDataProvider()
    {
        return [
            [1.0, true],
            [2.0, false],
            [0.0, false],
            [100.0, false],
            [-1.0, false],
        ];
    }
}
