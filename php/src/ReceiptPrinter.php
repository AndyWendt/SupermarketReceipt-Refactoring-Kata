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
            $discountPresentation = $this->receiptLineItem->formatLine(name: $discount->lineDescription(), value: (string)$discount->amount()) . "\n";
            $result .= $discountPresentation;
        }

        $result .= "\n";
        $result .= $this->presentTotal($receipt);
        return $result;
    }

    protected function presentReceiptItem(ReceiptItem $item): string
    {
        $price = (string)new Amount(amount: $item->getTotalPrice());
        $name = $item->getProduct()->getName();

        $line = $this->receiptLineItem->formatLine(name: $name, value: $price) . "\n";

        if ($item->getQuantity() !== 1.0) {
            $line .= '  ' . (string)new Amount(amount: $item->getPrice()) . ' * ' . self::presentQuantity($item) . "\n";
        }
        return $line;
    }

    public function presentTotal(Receipt $receipt): string
    {
        return $this->receiptLineItem->formatLine(name: 'Total: ', value: (string) $receipt->amount());
    }

    private static function presentQuantity(ReceiptItem $item): string
    {
        return $item->getProduct()->getUnit()->equals(ProductUnit::EACH()) ?
            sprintf('%x', $item->getQuantity()) :
            sprintf('%.3F', $item->getQuantity());
    }
}
