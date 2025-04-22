<?php

namespace Supermarket;
class ReceiptLineItem
{
    public static function instance(int $columns = 40)
    {
        return new self($columns);
    }

    public function __construct(private int $columns)
    {
    }

    public function formatLine(string $name, string $value): string
    {
        $whitespaceSize = $this->columns - strlen($name) - strlen($value);
        return $name . str_repeat(' ', $whitespaceSize) . $value;
    }
}