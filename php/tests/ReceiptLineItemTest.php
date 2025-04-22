<?php

namespace Tests;

use Supermarket\ReceiptLineItem;
use PHPUnit\Framework\TestCase;

class ReceiptLineItemTest extends TestCase
{
    /**
     * @dataProvider lineDataProvider
     */
    public function test_it_formats_a_line_with_the_correct_column_count($columns, $name, $value, $expected)
    {
        $instance = (new ReceiptLineItem(columns: $columns))->formatLine(name: $name, value: $value);

        $this->assertSame($expected, $instance);
    }

    public function test_it_formats_an_indented_line()
    {
        $result = ReceiptLineItem::instance()->indented('one two');

        $this->assertSame('  one two', $result);
    }

    public function test_it_raises_an_error_when_there_are_too_few_columns_for_the_name_and_value()
    {
        $this->expectException(\ValueError::class);
        (new ReceiptLineItem(columns: 9))->formatLine(name: 'Total:', value: '0.00');
    }

    public function lineDataProvider()
    {
        return [
            '40 columns, base example' => [40, 'Total:', '0.00', 'Total:                              0.00'],
            'bare minimum columns for name and value' => [10, 'Total:', '0.00', 'Total:0.00'],
            'different name' => [10, 'Name:', '1.23', 'Name: 1.23'],
        ];
    }
}
