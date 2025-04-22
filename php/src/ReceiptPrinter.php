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
            $line = $this->receiptLineItem->formatLine(name: $item->description(), value: (string)$item->totalAmount()) . "\n";

            if (!$item->quantityIsOne()) {
                $line .= $this->receiptLineItem->indented($item->quantityString()) . "\n";
            }
            $itemPresentation = $line;
            $result .= $itemPresentation;
        }



        foreach ($receipt->getDiscounts() as $discount) {
            $discountPresentation = $this->receiptLineItem->formatLine(name: $discount->lineDescription(), value: (string)$discount->amount()) . "\n";
            $result .= $discountPresentation;
        }

        $result .= "\n";
        $result .= $this->receiptLineItem->formatLine(name: 'Total: ', value: (string)$receipt->amount());
        return $result;
    }

}
