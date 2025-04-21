<?php

namespace Tests;

use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\Receipt;
use Supermarket\Model\ReceiptItem;
use Supermarket\ReceiptLineItem;
use Supermarket\ReceiptPrinter;
use PHPUnit\Framework\TestCase;

class ReceiptPrinterTest extends TestCase
{
    public function test_it_prints_a_blank_receipt()
    {
        $receipt = new Receipt();

        $instance = new ReceiptPrinter();

        $line = (new ReceiptLineItem(columns: 40))->formatLine(name: 'Total:', value: '0.00');
        $result = $instance->printReceipt($receipt);

        $this->assertSame("\n$line", $result);
    }

    public function test_it_presents_items()
    {
        $product = new Product('Foo', ProductUnit::EACH());

        $receipt = new Receipt();
        $receipt->addProduct(product: $product, quantity: 5, price: 5.00, totalPrice: 25.00);
        $receipt->addProduct(product: $product, quantity: 1, price: 1000.00, totalPrice: 1000.00);
        $instance = new ReceiptPrinter();

        $result = $instance->printReceipt($receipt);

        $this->assertSame('Foo                                25.00
  5.00 * 5
Foo                              1000.00

Total:                           1025.00', $result);
    }
}
