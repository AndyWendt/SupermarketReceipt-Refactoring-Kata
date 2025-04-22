<?php

declare(strict_types=1);

namespace Supermarket;

use Supermarket\Model\Discount;
use Supermarket\Model\ProductUnit;
use Supermarket\Model\Receipt;
use Supermarket\Model\ReceiptItem;

class ReceiptPrinter
{
    public static function instance(int $columns = 40)
    {
        return new self(receiptLineItem: new ReceiptLineItem($columns));
    }

    public function __construct(
        private readonly ReceiptLineItem $receiptLineItem
    ) {
    }

    public function printReceipt(Receipt $receipt): string
    {
        $result = '';
        foreach ($receipt->getItems() as $item) {
            $itemPresentation = $this->presentReceiptItem($item);
            $result .= $itemPresentation;
        }

        foreach ($receipt->getDiscounts() as $discount) {
            $discountPresentation = $this->presentDiscount($discount);
            $result .= $discountPresentation;
        }

        $result .= "\n";
        $result .= $this->presentTotal($receipt);
        return $result;
    }

    protected function presentReceiptItem(ReceiptItem $item): string
    {
        $price = (string)new Price(price: $item->getTotalPrice());
        $name = $item->getProduct()->getName();

        $line = $this->receiptLineItem->formatLine(name: $name, value: $price) . "\n";

        if ($item->getQuantity() !== 1.0) {
            $line .= '  ' . (string)new Price(price: $item->getPrice()) . ' * ' . self::presentQuantity($item) . "\n";
        }
        return $line;
    }

    public function presentDiscount(Discount $discount): string
    {
        $name = "{$discount->getDescription()}({$discount->getProduct()->getName()})";
        $value = (string)new Price(price: $discount->getDiscountAmount());

        return $this->receiptLineItem->formatLine(name: $name, value: $value) . "\n";
    }

    protected function presentTotal(Receipt $receipt): string
    {
        $name = 'Total: ';
        $value = (string)new Price(price: $receipt->getTotalPrice());

        return $this->receiptLineItem->formatLine(name: $name, value: $value);
    }

    private static function presentQuantity(ReceiptItem $item): string
    {
        return $item->getProduct()->getUnit()->equals(ProductUnit::EACH()) ?
            sprintf('%x', $item->getQuantity()) :
            sprintf('%.3F', $item->getQuantity());
    }
}
