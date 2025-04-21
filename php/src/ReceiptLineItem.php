<?php

namespace Supermarket;
class ReceiptLineItem
{
    public function __construct(private int $columns)
    {
    }

    public function formatLine(string $name, string $value): string
    {
        $whitespaceSize = $this->columns - strlen($name) - strlen($value);
        return $name . str_repeat(' ', $whitespaceSize) . $value;
    }
}