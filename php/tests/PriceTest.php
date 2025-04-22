<?php

namespace Tests;

use Supermarket\Amount;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    /**
     * @dataProvider priceDataProvider
     */
    public function test_it_presents_a_price($price, $expected)
    {
        $result = (string) new Amount($price);
        $this->assertSame($expected, $result);
    }

    public function priceDataProvider()
    {
        return [
            "more than 2 decimals" => [500.001, '500.00'],
            'no decimals' => [1, '1.00'],
            'with two decimals' => [1.12, '1.12'],
            'with one decimal' => [1.1, '1.10'],
        ];
    }
}
