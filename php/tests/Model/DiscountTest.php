<?php

namespace Tests\Model;

use Supermarket\Model\Discount;
use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase
{
    public function test_it_returns_a_discount_description()
    {
        $instance = Discount::fakeInstance('Foo', 1.00);

        $this->assertSame('Foo Discount(Foo)', $instance->lineDescription());
    }

    public function test_it_returns_a_price_amount()
    {
        $instance = Discount::fakeInstance('Foo', 1.00);
        $result = $instance->amount();

        $this->assertSame('1.00',(string) $result);
    }
}
