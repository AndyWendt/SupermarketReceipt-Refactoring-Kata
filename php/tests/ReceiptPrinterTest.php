<?php

namespace Tests;

use Supermarket\Model\Discount;
use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\Receipt;
use Supermarket\ReceiptLineItem;
use Supermarket\ReceiptPrinter;
use PHPUnit\Framework\TestCase;

class ReceiptPrinterTest extends TestCase
{
    public function test_it_prints_a_blank_receipt()
    {
        $receipt = new Receipt();

        $instance = ReceiptPrinter::instance();

        $line = (new ReceiptLineItem(columns: 40))->formatLine(name: 'Total:', value: '0.00');
        $result = $instance->printReceipt($receipt);

        $this->assertSame("\n$line", $result);
    }

    public function test_it_presents_a_discount()
    {
        $discount = Discount::fakeInstance('Fizz', 5.00);
        $result = ReceiptPrinter::instance()->presentDiscount($discount);
        $this->assertSame('Fizz Discount(Fizz)                 5.00
', $result);
    }

    public function test_it_presents_items_and_discounts()
    {
        $product = new Product('Foo', ProductUnit::EACH());
        $fooDiscount = Discount::fakeInstance('Foo', 20000.00);
        $barDiscount = Discount::fakeInstance('Bar', 1.00);

        $receipt = new Receipt();

        $receipt->addProduct(product: $product, quantity: 5, price: 5.00, totalPrice: 25.00);
        $receipt->addProduct(product: $product, quantity: 1, price: 1000.00, totalPrice: 1000.00);
        $receipt->addDiscount($fooDiscount);
        $receipt->addDiscount($barDiscount);


        $instance = ReceiptPrinter::instance();

        $result = $instance->printReceipt($receipt);

        $this->assertSame('Foo                                25.00
  5.00 * 5
Foo                              1000.00
Foo Discount(Foo)               20000.00
Bar Discount(Bar)                   1.00

Total:                          21026.00', $result);
    }

}
