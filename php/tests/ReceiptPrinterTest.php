<?php

namespace Tests;

use Supermarket\Model\Receipt;
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
        $this->assertSame("\n$line", $instance->printReceipt($receipt));
    }
}
