<?php

namespace Tests\Model;

use Supermarket\Model\Product;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\ShoppingCart;
use Supermarket\Model\Teller;
use PHPUnit\Framework\TestCase;
use Tests\FakeCatalog;

class TellerTest extends TestCase
{
    public function test_it_creates_a_receipt_from_a_shopping_cart()
    {
        $catalog = new FakeCatalog();
        $toothbrush = new Product('toothbrush', ProductUnit::EACH());
        $catalog->addProduct($toothbrush, 0.99);
        $apples = new Product('apples', ProductUnit::KILO());
        $catalog->addProduct($apples, 1.99);

        $cart = new ShoppingCart();
        $cart->addItemQuantity($apples, 2.5);

        $instance = new Teller($catalog);

        $receipt = $instance->createReceipt($cart);

        $items = $receipt->getItems();

        $this->assertCount(1, $items);

        $item = $items[0];

        $this->assertSame(4.975, $item->getTotalPrice());
        $this->assertSame($apples, $item->getProduct());
        $this->assertSame(2.5, $item->getQuantity());
        $this->assertSame(1.99, $item->getPrice());
    }
}
