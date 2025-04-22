<?php

namespace Tests;

use Supermarket\Model\Discount;
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

        $instance = ReceiptPrinter::instance();

        $line = ReceiptLineItem::instance()->formatLine(name: 'Total:', value: '0.00');
        $result = $instance->printReceipt($receipt);

        $this->assertSame("\n$line", $result);
    }

    public function test_it_presents_items_and_discounts()
    {
        $product = Product::fakeInstance();
        $fooDiscount = Discount::fakeInstance('Foo', 20000.00);
        $barDiscount = Discount::fakeInstance('Bar', 1.00);

        $receipt = new Receipt();

        $receipt->addProduct(product: $product, quantity: 5, price: 5.00);
        $receipt->addProduct(product: $product, quantity: 0, price: 5.00);
        $receipt->addProduct(product: $product, quantity: 1, price: 1000.00);
        $receipt->addDiscount($fooDiscount);
        $receipt->addDiscount($barDiscount);


        $instance = ReceiptPrinter::instance();

        $result = $instance->printReceipt($receipt);

        $receiptLineItem = ReceiptLineItem::instance();

        $expected = $receiptLineItem->formatLine('Foo', '25.00')
            . "\n" .
            $receiptLineItem->indented("5.00 * 5")
            . "\n" .
            $receiptLineItem->formatLine('Foo', '0.00')
            . "\n" .
            $receiptLineItem->indented("5.00 * 0")
            . "\n" .
            $receiptLineItem->formatLine('Foo', '1000.00') .
            "\n" .
            $receiptLineItem->formatLine('Foo Discount(Foo)', '20000.00') .
            "\n" .
            $receiptLineItem->formatLine('Bar Discount(Bar)', '1.00') .
            "\n" .
            "\n" .
            $receiptLineItem->formatLine('Total:', '21026.00')
        ;

        $this->assertSame($expected, $result);
    }

}
