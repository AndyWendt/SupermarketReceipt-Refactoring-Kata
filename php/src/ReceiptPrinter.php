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
        $lines = array_merge(
            $this->itemLines($receipt),
            $this->discountLines($receipt),
            $this->blankLine(),
            $this->totalLine($receipt)
        );

        return implode("\n", $lines);
    }

    private function itemLines(Receipt $receipt): array
    {
        $itemLines = [];
        foreach ($receipt->getItems() as $item) {
            $line = $this->receiptLineItem->formatLine(name: $item->description(), value: (string)$item->totalAmount());
            array_push($itemLines, $line);

            if (!$item->quantityIsOne()) {
                $quantityLine = $this->receiptLineItem->indented($item->quantityString());
                array_push($itemLines, $quantityLine);
            }
        }
        return $itemLines;
    }

    private function discountLines(Receipt $receipt): array
    {
        $discountLines = [];

        foreach ($receipt->getDiscounts() as $discount) {
            $discountPresentation = $this->receiptLineItem->formatLine(name: $discount->lineDescription(), value: (string)$discount->amount());
            array_push($discountLines, $discountPresentation);
        }
        return $discountLines;
    }

    private function blankLine(): array
    {
        return [''];
    }

    /**
     * @param Receipt $receipt
     * @return array
     */
    public function totalLine(Receipt $receipt): array
    {
        return [$this->receiptLineItem->formatLine(name: 'Total: ', value: (string)$receipt->amount())];
    }
}
