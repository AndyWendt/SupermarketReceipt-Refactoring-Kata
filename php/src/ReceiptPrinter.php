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
        $out = [];

        foreach ($receipt->getItems() as $item) {
            $line = $this->receiptLineItem->formatLine(name: $item->description(), value: (string)$item->totalAmount());
            array_push($out, $line);

            if (!$item->quantityIsOne()) {
                $quantityLine = $this->receiptLineItem->indented($item->quantityString());
                array_push($out, $quantityLine);
            }
        }



        foreach ($receipt->getDiscounts() as $discount) {
            $discountPresentation = $this->receiptLineItem->formatLine(name: $discount->lineDescription(), value: (string)$discount->amount());
            array_push($out, $discountPresentation);
        }

        array_push($out, '');

        $resultLine = $this->receiptLineItem->formatLine(name: 'Total: ', value: (string)$receipt->amount());
        array_push($out, $resultLine);

        return implode("\n", $out);
    }

}
