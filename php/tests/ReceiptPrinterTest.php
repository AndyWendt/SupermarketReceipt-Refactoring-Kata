<?php

namespace Tests;

use Supermarket\Model\Receipt;
use Supermarket\ReceiptPrinter;
use PHPUnit\Framework\TestCase;

class ReceiptPrinterTest extends TestCase
{
    public function test_it_prints_a_blank_receipt()
    {
        $receipt = new Receipt();

        $instance = new ReceiptPrinter();

        $this->assertSame('
Total:                              0.00', $instance->printReceipt($receipt));
    }

    public function test_it_formats_a_line_item()
    {
        $instance = new ReceiptPrinter();

        $this->assertSame('Total:                              0.00', $instance->formatLineWithWhiteSpace('Total:', '0.00'));
    }
}
